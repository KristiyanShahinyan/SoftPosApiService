<?php

namespace App\Service;

use App\Builder\ConfigurationBuilder;
use App\Constants\TransactionTypes;
use App\Dto\AffiliateDto;
use App\Dto\ConfigurationDto;
use App\Dto\CurrencyDto;
use App\Dto\InstanceDto;
use App\Dto\MerchantCategoryCodeDto;
use App\Dto\StoreDto;
use App\Dto\TerminalDto;
use App\Dto\TimezoneDto;
use App\Dto\TransactionDto;
use App\Exception\ExceptionCodes as LocalExceptionCodes;
use App\Helper\MerchantPermissionsHelper;
use App\Helper\Utils;
use App\RequestManager\Account\UserRequestManager;
use App\RequestManager\Terminal\TerminalRequestManager;
use App\Security\Merchant;
use App\Security\Merchants;
use App\Security\User;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Phos\Exception\ApiException;
use Phos\Exception\ExceptionCodes;
use Phos\Helper\LoggerTrait;
use Phos\Helper\SerializationTrait;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class GatewayOperation
{
    use SerializationTrait, LoggerTrait;

    private UserRequestManager $userManager;

    private TerminalRequestManager $terminalManager;

    private ConfigurationBuilder $configurationBuilder;

    public function __construct(
        UserRequestManager $userManager,
        TerminalRequestManager $terminalManager,
        ConfigurationBuilder $configurationBuilder
    ) {
        $this->userManager = $userManager;
        $this->terminalManager = $terminalManager;
        $this->configurationBuilder = $configurationBuilder;
    }

    /**
     * @throws ApiException
     */
    public function getConfigurationFromTransaction(TransactionDto $transactionDto, User $user, int $operation): ConfigurationDto
    {
        $merchantCategoryCode = new MerchantCategoryCodeDto();
        $merchantCategoryCode->setCode($transactionDto->getMcc());

        $store = new StoreDto();
        $store->setCardAcceptorCity($transactionDto->getPosCardAcceptorCity());
        $store->setCardAcceptorCountry($transactionDto->getPosCardAcceptorCountry());
        $store->setCardAcceptorName($transactionDto->getPosCardAcceptorName());
        $store->setCardAcceptorIdentificationCode($transactionDto->getPosCardAcceptorIdentCode());
        // this doesn't matter for void or refund, but we need backward compatibility
        // in the configurationBuilder it switches the TID depends on sca type so it will miss if not set and sca_type = 3
	    $store->setCardAcceptorIdentificationCode3ds($transactionDto->getPosCardAcceptorIdentCode());
	    $store->setMerchantCategoryCode($merchantCategoryCode);
        $store->setToken($transactionDto->getStoreToken());

        $timezoneDto = new TimezoneDto();
        $timezoneDto->setName($transactionDto->getTimezoneName());
        // the time difference is not used, but I need a value
        $timezoneDto->setTimeDifference('+00');
        $store->setTimezone($timezoneDto);

        $terminal = new TerminalDto();
        $terminal->setPosTerminalIdCode($transactionDto->getTerminalId());
        $terminal->setScaType($transactionDto->getScaType());
        $terminal->setToken($transactionDto->getTerminalToken());

        $instance = new InstanceDto();
        $instance->setAcquiringInstitutionIdentificationCode($transactionDto->getPosAcquiringInstitutionCode());
	    // For void transactions we do not care of sca type
	    $instance->setScaType(1);
        if ($transactionDto->getInstance())
            $instance->setName($transactionDto->getInstance());

        $merchant = new Merchant();
        $merchant->setInstance($instance);
        $merchant->setToken($transactionDto->getMerchantIdent());
        if ($transactionDto->getAffiliate()) {
            $affiliate = new AffiliateDto();
            $affiliate->setName($transactionDto->getAffiliate());
            $merchant->setAffiliate($affiliate);
        }

        $user->setCurrentMerchantToken($transactionDto->getMerchantIdent());

        $roles = MerchantPermissionsHelper::getPermissions($user->getMerchants(), $transactionDto->getMerchantIdent());

        $this->hasRights($operation, $roles);

        $currency = Utils::getCurrencyInfo($transactionDto->getCurrency());

        return $this->configurationBuilder->build($merchant, $store, $terminal, $currency);
    }

    /**
     * @throws ApiException
     * @throws ExceptionInterface
     * @throws GuzzleException|JsonException
     */
    public function getConfiguration(User $user, int $operation, ?string $terminalToken = null, ?int $scaType = null, string $currencyCode): ConfigurationDto
    {
        if ($terminalToken) {
            $terminal = $this->terminalManager->find(['token' => $terminalToken, 'user' => $user->getId()]);

            /**
             * @var TerminalDto $terminal
             */
            $terminal = $this->denormalize($terminal, TerminalDto::class);

            /**
             * @var StoreDto $store
             */
            $store = $this->denormalize($terminal['store'], StoreDto::class);

            $merchant = $this->userManager->getMerchantByIdAndUser($store->getMerchant(), $user->getId());

            /**
             * @var Merchant $merchant
             */
            $merchant = $this->denormalize($merchant, Merchant::class);

            $roles = array_filter($user->getMerchants(), function ($value) use ($merchant) {
                return $value->getMerchant()->getId() === $merchant->getId();
            });

            $roles = end($roles)->getRoles();

        } else {
            //Get first active for user. Fallback for app v1
            $terminalArr = $this->terminalManager->findByUser(['user' => $user->getId(), 'is_deleted' => 'false']);

            /** @var StoreDto $store */
            $store = $this->denormalize($terminalArr['store'], StoreDto::class);

            /** @var TerminalDto $terminal */
            $terminal = $this->denormalize($terminalArr, TerminalDto::class);

            $merchantId = $store->getMerchant();

            $merchants = array_filter($user->getMerchants(), function (Merchants $merchants) use ($merchantId) {
                /** @var Merchant $merchant */
                $merchant = $merchants->getMerchant();

                return $merchant->getId() === $merchantId;
            });

            $roles = end($merchants)->getRoles();

            /** @var Merchant $merchant */
            $merchant = end($merchants)->getMerchant();
        }

        $currency = Utils::getCurrencyInfo($currencyCode);

        //If one of the elements is missing terminate
        if ($terminal->getToken() === null || $merchant->getToken() === null || $store->getToken() === null) {
            throw new ApiException(ExceptionCodes::ENTITY_NOT_FOUND['general']);
        }

        //If terminate or store is not active terminate
        if ($terminal->getIsActive() === false || $store->getIsActive() === false) {
            throw new ApiException($terminal->getIsActive() ? LocalExceptionCodes::TERMINAL_NOT_ACTIVE : LocalExceptionCodes::STORE_NOT_ACTIVE, [$terminal->getIsActive() ? 'Store is not active' : 'Terminal is not active']);
        }

        $user->setCurrentMerchantToken($merchant->getToken());

        $this->hasRights($operation, $roles);

        $configurationDto = $this->configurationBuilder->build($merchant, $store, $terminal, $currency);

        if (TransactionDto::SCA_TYPE['SCA_TYPE_3DS_PROTECTED'] == $scaType) {
            $this->configure3dsTerminal($configurationDto, $terminal, $merchant);
        }

        return $configurationDto;
    }

    /** @throws ApiException */
    protected function hasRights(int $operation, array $roles): void
    {
        $hasRole = false;

        switch ($operation) {
            case TransactionTypes::NUAPAY_SALE:
            case TransactionTypes::POCKYT_SALE:
            case TransactionTypes::AUTH:
                $hasRole = in_array('ROLE_SALE', $roles) || in_array('ROLE_ADMIN', $roles);
                break;
            case TransactionTypes::NUAPAY_REFUND:
            case TransactionTypes::POCKYT_REFUND:
            case TransactionTypes::REFUND:
                $hasRole = in_array('ROLE_REFUND', $roles) || in_array('ROLE_ADMIN', $roles);
                break;
            case TransactionTypes::VOID:
            case TransactionTypes::POCKYT_VOID:
                $hasRole = in_array('ROLE_VOID', $roles) || in_array('ROLE_ADMIN', $roles);
                break;
        }

        if (!$hasRole) {
            throw new ApiException(LocalExceptionCodes::INSUFFICIENT_RIGHTS, ['Operation' => ucfirst($operation)]);
        }
    }

    private function configure3dsTerminal(ConfigurationDto $configurationDto, TerminalDto $terminal, Merchant $merchant) :void
    {
        if (!empty($terminal->getPosTerminalIdCode3ds())) {
            $configurationDto->setPosTerminalIdCode($terminal->getPosTerminalIdCode3ds());
            return;
        }

        if (!empty($merchant->getPosTerminalIdCode3ds())) {
            $configurationDto->setPosTerminalIdCode($merchant->getPosTerminalIdCode3ds());
        }
    }
}
