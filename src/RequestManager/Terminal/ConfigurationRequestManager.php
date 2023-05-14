<?php

namespace App\RequestManager\Terminal;

use App\RequestManager\AbstractRequestManager;
use GuzzleHttp\Exception\GuzzleException;
use Phos\Exception\ApiException;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ConfigurationServer
 * @package App\Request\Terminal
 */
class ConfigurationRequestManager extends AbstractRequestManager
{
    /**
     *
     */
    protected const SERVICE = 'TERMINAL_SERVICE';

    /**
     * @param int $user
     * @return mixed|ResponseInterface
     * @throws GuzzleException
     * @throws ApiException
     */
    public function getConfigByUser(int $user)
    {
        return $this->get('/api/configurations', [], ['groups' => 'show', 'user' => $user]);
    }

    /**
     * @param string $token
     * @return false|string
     */
    public function find(string $token)
    {
        return $this->cache->get('configurations:' . $token, function () use ($token) {
            return json_encode($this->get('/api/configurations/{token}', compact('token')));
        }, false);
    }
}
