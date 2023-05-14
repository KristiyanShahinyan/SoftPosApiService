<?php

namespace App\Security;

use App\RequestManager\Account\UserRequestManager;
use Exception;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

/**
 * Class UserProvider
 * @package App\Security
 */
class UserProvider implements UserProviderInterface
{
    /**
     * @var UserRequestManager
     */
    private UserRequestManager $userService;

    /**
     * @var DenormalizerInterface
     */
    private DenormalizerInterface $denormalizer;

    /**
     * UserProvider constructor.
     * @param UserRequestManager $userService
     * @param DenormalizerInterface $denormalizer
     */
    public function __construct(UserRequestManager $userService, DenormalizerInterface $denormalizer)
    {
        $this->userService = $userService;
        $this->denormalizer = $denormalizer;
    }

    /**
     * The loadUserByIdentifier() method was introduced in Symfony 5.3.
     * In previous versions it was called loadUserByUsername()
     *
     * Symfony calls this method if you use features like switch_user
     * or remember_me. If you're not using these features, you do not
     * need to implement this method.
     *
     * @param string $identifier
     * @return UserInterface
     * @throws ExceptionInterface
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        // Load a User object from your data source or throw UserNotFoundException.
        // The $identifier argument is whatever value is being returned by the
        // getUserIdentifier() method in your User class.
        try {
            $user = $this->userService->getUserByToken($identifier);
        } catch (Exception $exception) {
            throw new UserNotFoundException('Wrong Credentials');
        }
        return $this->denormalizer->denormalize($user, User::class);
    }

    /**
     * Tells Symfony to use this provider for this User class.
     * @param $class
     * @return bool
     */
    public function supportsClass($class): bool
    {
        return User::class === $class;
    }

    /**
     * @param UserInterface $user
     * @return UserInterface
     * @throws ExceptionInterface
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    /**
     * @throws ExceptionInterface
     */
    public function loadUserByUsername(string $username)
    {
        return $this->loadUserByIdentifier($username);
    }
}
