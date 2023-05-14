<?php

namespace App\Builder;

use App\Constants\TransactionTypes;
use App\Dto\ConfigurationDto;
use App\Dto\Request\TransactionFilterDto;
use App\Dto\Request\TransactionsDto;

/**
 * Class TransactionFilterBuilder
 * @package App\Builder
 */
class TransactionFilterBuilder
{
    /**
     * @param TransactionsDto $transactionsDto
     * @param string $userToken
     * @return TransactionFilterDto
     */
    public function buildTransactionFilterDto(TransactionsDto $transactionsDto, string $userToken): TransactionFilterDto
    {
        $transactionFilterDto = new TransactionFilterDto();

        $transactionFilterDto->setUser($userToken);

        if ($transactionsDto->getPage() !== null) {
            $transactionFilterDto->setPage($transactionsDto->getPage());
        }

        if ($transactionsDto->getLimit() !== null) {
            $transactionFilterDto->setLimit($transactionsDto->getLimit());
        }

        if ($transactionsDto->getDate() !== null) {
            $transactionFilterDto->setStartDate($transactionsDto->getDate());
            $transactionFilterDto->setEndDate($transactionsDto->getDate());
        }

        if ($transactionsDto->getStartDate()) {
            $transactionFilterDto->setStartDate($transactionsDto->getStartDate());
        }

        if ($transactionsDto->getEndDate()) {
            $transactionFilterDto->setEndDate($transactionsDto->getEndDate());
        }

        $transactionFilterDto->setExactDate($transactionsDto->getExactDate());

        if ($transactionsDto->getType() !== null) {
            $transactionFilterDto->setTrnType(TransactionTypes::getTrnType($transactionsDto->getType()));
        }

        if ($transactionsDto->getStatus() !== null) {
            $transactionFilterDto->setStatus($transactionsDto->getStatus());
        }

        if ($transactionsDto->getThreeDs() !== null)
            $transactionFilterDto->setThreeDs($transactionsDto->getThreeDs());

        if (!empty($transactionsDto->getSort())) {
            $transactionFilterDto->setSort($transactionsDto->getSort());
        }

        if ($transactionsDto->getTrnTypes()) {
            $trnTypes = array_map('App\Constants\TransactionTypes::getTrnType', $transactionsDto->getTrnTypes());
            $transactionFilterDto->setTrnTypes($trnTypes);
        }

        return $transactionFilterDto;
    }

    public function unfinishedTransactionsFilter(ConfigurationDto $configurationDto): TransactionFilterDto
    {
        $transactionFilterDto = new TransactionFilterDto();
        $transactionFilterDto->setAcquirerCode($configurationDto->getAcquiringInstitutionIdentificationCode());
        $transactionFilterDto->setMid($configurationDto->getCardAcceptorIdentificationCode());
        $transactionFilterDto->setTid($configurationDto->getPosTerminalIdCode());
        $transactionFilterDto->setStatus(0);

        return $transactionFilterDto;
    }
}
