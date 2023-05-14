<?php

namespace App\RequestManager\Account;

use App\RequestManager\AbstractRequestManager;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Phos\Exception\ApiException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class MerchantService
 * @package App\Request\Account
 */
class MerchantRequestManager extends AbstractRequestManager
{
    protected const SERVICE = 'ACCOUNT_SERVICE';

    /**
     * @param int $userId
     * @return mixed|ResponseInterface
     * @throws GuzzleException
     * @throws JsonException
     * @throws ApiException
     */
    public function find(int $userId)
    {
        return $this->get('/api/merchants', [], ['user' => $userId, 'isDeleted' => false, 'groups' => 'instance,show']);
    }
}
