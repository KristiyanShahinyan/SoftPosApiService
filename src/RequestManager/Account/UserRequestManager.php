<?php

namespace App\RequestManager\Account;

use App\Dto\Request\ChangePasswordDto;
use App\Dto\Request\ForgotPasswordDto;
use App\RequestManager\AbstractRequestManager;
use GuzzleHttp\Exception\GuzzleException;
use Phos\Exception\ApiException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class UserService
 * @package App\Request\Account
 */
class UserRequestManager extends AbstractRequestManager
{
    protected const SERVICE = 'ACCOUNT_SERVICE';

    /**
     * @param $credentials
     * @return mixed|ResponseInterface
     * @throws GuzzleException
     * @throws ApiException
     */
    public function login($credentials)
    {
        return $this->post('/api/login/mobile', $credentials);
    }

    /**
     * @param string $token
     * @return bool|false|mixed|string
     */
    public function getUserByToken(string $token)
    {

        return $this->cache->get('users:' . $token, function () use ($token) {
            return $this->get('/api/users/{token}', compact('token'), ['groups' => 'merchant,show']);
        });
    }

    /**
     * @param ChangePasswordDto $dto
     * @param $token
     * @return mixed|ResponseInterface
     * @throws ApiException
     * @throws GuzzleException
     */
    public function changePassword(ChangePasswordDto $dto, $token)
    {
        return $this->post('/api/change-password/{token}', $dto, compact('token'));
    }

    /**
     * @param ForgotPasswordDto $dto
     * @return mixed|ResponseInterface
     * @throws ApiException
     * @throws GuzzleException
     */
    public function createForgottenPasswordToken(ForgotPasswordDto $dto)
    {
        return $this->post('/api/forgotten-password/token', $dto);
    }

    public function setNewPasswordForToken(ForgotPasswordDto $dto)
    {
        return $this->post('/api/forgotten-password/set-new', $dto);
    }

    public function getResetPasswordLink(string $token)
    {
        return $this->get('/api/forgotten-password/get-link/{token}', compact('token'));
    }

    public function getMerchant(string $token)
    {
        return $this->get('/api/merchants/{token}', compact('token'), ['groups' => 'instance']);
    }
}
