<?php

namespace App\RequestManager\Account;

use GuzzleHttp\Exception\GuzzleException;
use Phos\Exception\ApiException;
use Phos\HttpRequest\ApiHttpRequestService;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class InstanceRequestManager
 * @package App\RequestManager\Account
 */
class InstanceRequestManager extends ApiHttpRequestService
{

    protected const SERVICE = 'ACCOUNT_SERVICE';

    /**
     * @param string $name
     * @return mixed|ResponseInterface|HttpException
     * @throws GuzzleException
     * @throws ApiException
     */
    public function findByName(string $name, string $groups = 'configuration')
    {
        return $this->get('/api/instances', [], compact('name', 'groups'));
    }

    public function findByAlias(string $alias, string $groups = 'show')
    {
        return $this->get('/api/instances', [], compact('alias', 'groups'));
    }
}
