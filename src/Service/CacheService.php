<?php

namespace App\Service;

/**
 * Class CacheService
 * @package App\Service
 */
class CacheService
{
    /**
     * @var RedisService
     */
    private $redisService;

    /**
     * @var int
     */
    private $ttl;

    /**
     * CacheService constructor.
     * @param RedisService $redisService
     */
    public function __construct(RedisService $redisService)
    {
        $this->redisService = $redisService;
        $this->ttl = (int)getenv('REFRESH_TOKEN_TTL');
    }

    /**
     * @param string $username
     * @return bool|string
     */
    public function getLoggedOutUserTs(string $username)
    {
        return $this->redisService->get('loggedout_user_' . $username);
    }

    /**
     * @param string $deviceId
     * @return bool|string
     */
    public function getLoggedOutDeviceTs(string $deviceId)
    {
        return $this->redisService->get('loggedout_device_' . $deviceId);
    }

    /**
     * @param string $username
     */
    public function setLoggedOutUserTs(string $username): void
    {
        $this->setLoggedOutTs('loggedout_user_' . $username);
    }

    /**
     * @param string $key
     */
    protected function setLoggedOutTs(string $key): void
    {
        $this->redisService->setex($key, $this->ttl, time());
    }

    /**
     * @param string $deviceId
     */
    public function setLoggedOutDeviceTs(string $deviceId): void
    {
        $this->setLoggedOutTs('loggedout_device_' . $deviceId);
    }
}
