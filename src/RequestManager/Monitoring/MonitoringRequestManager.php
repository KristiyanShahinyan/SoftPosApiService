<?php

namespace App\RequestManager\Monitoring;

use App\Dto\TransactionDto;
use App\RequestManager\AbstractRequestManager;
use Phos\Exception\ApiException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class MonitoringRequestManager extends AbstractRequestManager
{
    protected const SERVICE = 'MONITORING_SERVICE';

    /**
     * @param TransactionDto $transaction
     * @param array $severity
     * @return mixed|ResponseInterface|HttpException
     * @throws ApiException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function checkTransaction(TransactionDto $transaction, array $severity)
    {
        return $this->post('/transaction/check', compact('transaction', 'severity'));
    }
}