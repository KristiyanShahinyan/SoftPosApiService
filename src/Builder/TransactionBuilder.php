<?php

namespace App\Builder;

use App\Dto\ConfigurationDto;
use App\Dto\TimezoneDto;
use App\Dto\TransactionDto;
use App\Security\User;
use DateTime;
use DateTimeZone;
use Exception;

class TransactionBuilder
{
    /**
     * @throws Exception
     */
    public function buildCreate(
        User $user,
        ConfigurationDto $config,
        int $type,
        float $amount,
        string $currency,
        ?string $deviceId = null,
        ?string $latitude = null,
        ?string $longitude = null,
        ?int $nfcTime = 0,
        ?string $cardType = null,
        ?string $channel = null,
        ?array $metadata = null,
        ?int $scaType = null,
        ?string $orderReference = null,
        ?array $appDetails = null,
        ?int $paymentMethod = null
    ): TransactionDto {
        $transactionDto = new TransactionDto();
        $transactionDto->setUserToken($user->getToken());
        $transactionDto->setMerchantIdent($user->getCurrentMerchantToken());
        $transactionDto->setTerminalToken($config->getTerminalToken());
        $transactionDto->setTransactionType($type);
        $transactionDto->setProduct('phos');
        $transactionDto->setChannel($channel);
        $transactionDto->setDeviceId($deviceId);

        // Config
        $transactionDto->setPosAcquiringInstitutionCode($config->getAcquiringInstitutionIdentificationCode());
        $transactionDto->setTerminalId($config->getPosTerminalIdCode());
        $transactionDto->setPosCardAcceptorIdentCode($config->getCardAcceptorIdentificationCode());
        $transactionDto->setPosCardAcceptorName($config->getCardAcceptorName());
        $transactionDto->setPosCardAcceptorCity($config->getCardAcceptorCity());
        $transactionDto->setPosCardAcceptorCountry($config->getCardAcceptorCountry());
        $transactionDto->setPosSystemTraceAuditNumber($config->getSystemTraceAuditNumber());
        $transactionDto->setMcc($config->getMerchantCategoryCode());
        $transactionDto->setAffiliate($config->getAffiliate());
        $transactionDto->setStoreToken($config->getStoreToken());
        $transactionDto->setInstance($config->getInstance());

        $transactionDto->setAmount($amount);
        $transactionDto->setCurrency($currency);

        /** @var TimezoneDto $timezone */
        $timezoneDto = $config->getTimezone();
        $localDateTime = new DateTime('now', new DateTimeZone($timezoneDto->getName()));
        $transactionDto->setPosLocalDateTime($localDateTime);
        $transactionDto->setTimezoneName($timezoneDto->getName());

        // Fill location Data
        $transactionDto->setLatitude($latitude);
        $transactionDto->setLongitude($longitude);

        $transactionDto->setNfcTime($nfcTime);
        $transactionDto->setCardType($cardType);
        $transactionDto->setMetadata($metadata);
        $transactionDto->setScaType($scaType);
        $transactionDto->setOrderReference($orderReference);
        if ($metadata && array_key_exists('order_reference', $metadata)) {
            $transactionDto->setOrderReference($metadata['order_reference']);
        }
        $transactionDto->setAppDetails($appDetails);
        if ($paymentMethod) {
            $transactionDto->setPaymentMethod($paymentMethod);
        }

        return $transactionDto;
    }
}
