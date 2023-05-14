<?php

namespace App\EntityManager;

use App\Entity\RefreshToken;
use Doctrine\Persistence\ObjectManager;
use Gesdinet\JWTRefreshTokenBundle\Doctrine\RefreshTokenManager as BaseRefreshTokenManager;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class RefreshTokenManager
 * @package App\EntityManager
 */
class RefreshTokenManager extends BaseRefreshTokenManager
{
    /**
     * RefreshTokenManager constructor.
     * @param ObjectManager $om
     */
    public function __construct(ObjectManager $om)
    {

        $this->objectManager = $om;
        $this->repository = $om->getRepository(RefreshToken::class);
        $metadata = $om->getClassMetadata(RefreshToken::class);
        $this->class = $metadata->getName();
    }

    /**
     * @param string $token
     * @param string $deviceId
     */
    public function addDeviceId(string $token, string $deviceId): void
    {
        /**
         * @var RefreshToken $refreshToken
         */
        $refreshToken = $this->get($token);

        if ($refreshToken === null) {
            throw new AuthenticationException('Refresh token that was just created was not found');
        }

        $refreshToken->setDeviceId($deviceId);
        $this->objectManager->flush();
    }

    /**
     * @param string $username
     * @param bool $andFlush
     */
    public function deleteByUsername(string $username, bool $andFlush = true): void
    {
        $this->deleteByField('username', $username, $andFlush);
    }

    /**
     * @param string $fieldName
     * @param string $fieldValue
     * @param bool $andFlush
     */
    protected function deleteByField(string $fieldName, string $fieldValue, bool $andFlush = true): void
    {
        $refreshTokens = $this->repository->findBy([
            $fieldName => $fieldValue,
        ]);

        /**
         * @var RefreshTokenInterface $refreshToken
         */
        foreach ($refreshTokens as $refreshToken) {
            $this->objectManager->remove($refreshToken);
        }

        if ($andFlush) {
            $this->objectManager->flush();
        }
    }

    /**
     * @param string $deviceId
     * @param bool $andFlush
     */
    public function deleteByDeviceId(string $deviceId, bool $andFlush = true): void
    {
        $this->deleteByField('deviceId', $deviceId, $andFlush);
    }

}
