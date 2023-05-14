<?php

namespace App\RequestManager\Transaction;

use App\Dto\Request\AnalyticsDto;
use App\RequestManager\AbstractRequestManager;
use GuzzleHttp\Exception\GuzzleException;
use Phos\Exception\ApiException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TransactionRequestManager extends AbstractRequestManager
{
    protected const SERVICE = 'TRANSACTION_SERVICE';

    /**
     * @param $filters
     *
     * @return mixed|ResponseInterface
     *
     * @throws GuzzleException
     * @throws ApiException
     */
    public function getAll($filters)
    {
        return $this->post('/transactions', $filters);
    }

    /**
     * @param string $trnKey
     *
     * @return array
     *
     * @throws ApiException
     * @throws GuzzleException
     */
    public function find(string $trnKey): array
    {
        return $this->get('/transaction/{trnKey}', compact('trnKey'));
    }

    /**
     * Returns transaction analytics for the provided type/timeframe.
     *
     * @param string $userToken The user API token.
     * @param AnalyticsDto $analyticsDto A DTO describing the necessary analytics.
     *
     * @return array|null
     *
     * @throws ApiException
     * @throws GuzzleException
     */
    public function getAnalytics(string $userToken, AnalyticsDto $analyticsDto): ?array
    {
        return $this->post('/analytics/{userToken}', $analyticsDto, compact('userToken'));
    }

    public function create($data)
    {
        return $this->post('/transaction', $data);
    }

    public function update($data, string $trnKey)
    {
        return $this->put('/transaction/{trnKey}', $data, compact('trnKey'));
    }

    /**
     * @param $data
     * @param string $trnKey
     *
     * @return mixed|ResponseInterface|HttpException
     *
     * @throws ApiException
     * @throws GuzzleException
     */
    public function update_refund($data, string $trnKey)
    {
        return $this->put('/transaction/update/refund/{trnKey}', $data, compact('trnKey'));
    }
}
