<?php

namespace App\RequestManager\Account;

use App\Dto\Request\AccessRequestDto;
use App\RequestManager\AbstractRequestManager;
use GuzzleHttp\Exception\GuzzleException;
use Phos\Exception\ApiException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class AccessRequestManager
 * @package App\RequestManager\Account
 */
class AccessRequestManager extends AbstractRequestManager
{
    /**
     *
     */
    protected const SERVICE = 'ACCOUNT_SERVICE';

    /**
     * @param string $token
     * @return mixed|ResponseInterface
     * @throws GuzzleException
     * @throws ApiException
     */
    public function find(string $token)
    {
        return $this->post('/api/access-request', compact('token'));
    }

    /**
     * @param AccessRequestDto $dto
     * @return mixed|ResponseInterface
     * @throws ApiException
     * @throws GuzzleException
     */
    public function create(AccessRequestDto $dto)
    {
        return $this->post('/api/access-request/create', $dto);
    }

    /**
     * @return mixed|ResponseInterface
     * @throws GuzzleException
     * @throws ApiException
     */
    public function update()
    {
        return $this->put('/api/access-request/{id}');
    }

    /**
     * @return mixed|ResponseInterface
     * @throws GuzzleException
     * @throws ApiException
     */
    public function destroy()
    {
        return $this->delete('/api/access-request/{id}');

    }
}
