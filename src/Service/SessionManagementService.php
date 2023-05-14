<?php

namespace App\Service;

use App\EntityManager\RefreshTokenManager;

/**
 * Class SessionManagementService
 * @package App\Service
 */
class SessionManagementService
{
    /**
     * @var RefreshTokenManager
     */
    private $refreshTokenManager;

    /**
     * @var CacheService
     */
    private $cacheService;

    /**
     * SessionManagementService constructor.
     * @param RefreshTokenManager $refreshTokenManager
     * @param CacheService $cacheService
     */
    public function __construct(RefreshTokenManager $refreshTokenManager, CacheService $cacheService)
    {
        $this->refreshTokenManager = $refreshTokenManager;
        $this->cacheService = $cacheService;
    }

    /**
     * @param string $username
     */
    public function invalidateUserSessions(string $username): void
    {
        $this->refreshTokenManager->deleteByUsername($username);
        $this->cacheService->setLoggedOutUserTs($username);
    }

    /**
     * @param string $deviceId
     */
    public function invalidateDeviceSessions(string $deviceId): void
    {
        $this->refreshTokenManager->deleteByDeviceId($deviceId);
        $this->cacheService->setLoggedOutDeviceTs($deviceId);
    }
}
