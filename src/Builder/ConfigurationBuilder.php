<?php

namespace App\Builder;

use App\Dto\ConfigurationDto;
use App\Dto\CurrencyDto;
use App\Dto\InstanceDto;
use App\Dto\StoreDto;
use App\Dto\TerminalDto;
use App\Dto\TransactionDto;
use App\Security\Merchant;

class ConfigurationBuilder
{
    public function build(Merchant $merchant, StoreDto $store, TerminalDto $terminal, CurrencyDto $currency): ConfigurationDto
    {
        $configurationDto = new ConfigurationDto();

        /** @var InstanceDto $instance */
        $instance = $merchant->getInstance();

        $configurationDto->setAcquiringInstitutionIdentificationCode($instance->getAcquiringInstitutionIdentificationCode());
        $configurationDto->setCardAcceptorName($store->getCardAcceptorName());
        $configurationDto->setCardAcceptorCity($store->getCardAcceptorCity());
        $configurationDto->setCardAcceptorCountry($store->getCardAcceptorCountry());
        $configurationDto->setCardAcceptorIdentificationCode($store->getCardAcceptorIdentificationCode());
        $configurationDto->setCardAcceptorIdentificationCode3ds($store->getCardAcceptorIdentificationCode3ds());
        $configurationDto->setPosTerminalIdCode($terminal->getPosTerminalIdCode());

        $configurationDto->setSystemTraceAuditNumber($terminal->getSystemTraceAuditNumber());
        $configurationDto->setTimezone($store->getTimezone());
        $configurationDto->setCurrency($currency);
        $configurationDto->setTerminalScaType($this->getTerminalScaType($terminal, $merchant));
        $configurationDto->setAffiliate($merchant->getAffiliate() ? $merchant->getAffiliate()->getName() : '');
        $configurationDto->setMerchantToken($merchant->getToken());
        $configurationDto->setTerminalToken($terminal->getToken());
        $configurationDto->setStoreToken($store->getToken());
        $configurationDto->setAcquirerSpecificData($terminal->getAcquirerSpecificData());
        $configurationDto->setInstance($instance->getName());

        if ($mcc = $store->getMerchantCategoryCode())
            $configurationDto->setMerchantCategoryCode($mcc->getCode());

        return $configurationDto;
    }

    private function getTerminalScaType(TerminalDto $terminal, Merchant $merchant): int
    {
        if (TransactionDto::SCA_TYPE['SCA_TYPE_INHERIT_FROM_MERCHANT'] != $terminal->getScaType())
            return $terminal->getScaType();

        if (TransactionDto::SCA_TYPE['SCA_TYPE_INHERIT_FROM_MERCHANT'] != $merchant->getScaType())
            return $merchant->getScaType();

        return $merchant->getInstance()->getScaType();
    }
}
