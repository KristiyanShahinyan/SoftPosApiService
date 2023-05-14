<?php

namespace App\Security;

use App\Builder\SecurityBuilder;
use App\Dto\Request\LoginDto;
use App\EntityManager\RefreshTokenManager;
use App\Helper\Utils;
use App\RequestManager\Account\UserRequestManager;
use App\RequestManager\Security\SecurityRequestManager;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Phos\Exception\ApiException;
use Phos\Helper\SerializationTrait;
use Phos\Helper\ValidationTrait;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use UnexpectedValueException;

/**
 * Class TokenAuthenticator
 * @package App\Security
 */
class TokenAuthenticator extends AbstractAuthenticator
{
    use ValidationTrait, SerializationTrait;

    /**
     * @var JWTTokenManagerInterface
     */
    protected JWTTokenManagerInterface $tokenManager;

    /**
     * @var EventDispatcherInterface
     */
    protected EventDispatcherInterface $dispatcher;

    /**
     * @var RefreshTokenManager
     */
    protected RefreshTokenManager $refreshTokenManager;

    /**
     * @var UserRequestManager
     */
    protected UserRequestManager $userService;

    /**
     * @var SecurityRequestManager
     */
    private SecurityRequestManager $securityRequestManager;

    /**
     * @var SecurityBuilder
     */
    private SecurityBuilder $builder;

    /**
     * TokenAuthenticator constructor.
     * @param EventDispatcherInterface $dispatcher
     * @param JWTTokenManagerInterface $tokenManager
     * @param RefreshTokenManager $refreshTokenManager
     * @param UserRequestManager $userService
     * @param SecurityRequestManager $securityRequestManager
     * @param SecurityBuilder $builder
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        JWTTokenManagerInterface $tokenManager,
        RefreshTokenManager $refreshTokenManager,
        UserRequestManager $userService,
        SecurityRequestManager $securityRequestManager,
        SecurityBuilder $builder)
    {
        $this->dispatcher = $dispatcher;
        $this->tokenManager = $tokenManager;
        $this->refreshTokenManager = $refreshTokenManager;
        $this->userService = $userService;
        $this->securityRequestManager = $securityRequestManager;
        $this->builder = $builder;
    }

    /**
     * Does the authenticator support the given Request?
     *
     * If this returns true, authenticate() will be called. If false, the authenticator will be skipped.
     *
     * Returning null means authenticate() can be called lazily when accessing the token storage.
     */
    public function supports(Request $request): ?bool
    {
        return true;
    }

    /**
     * Get the authentication credentials from the request and return them
     * as any type (e.g. an associate array).
     *
     * @param Request $request
     * @return LoginDto
     * @throws UnexpectedValueException If null is returned
     * @throws Exception
     * @throws GuzzleException
     */
    public function getCredentials(Request $request): LoginDto
    {
        $content = $request->getContent();

        /**
         * @var LoginDto $data
         */
        $data = $this->deserialize($content, LoginDto::class);

        $this->validate($data);

        $data->setDeviceId(Utils::parseDeviceId($request));
        $data->setType($this->container->getParameter('type'));

        if (getenv('ENCRYPTION') !== 'no') {
            //Decrypt username and password
            $dto = $this->builder->build($data->getUsername());
            $username = $this->securityRequestManager->decryptRequest($dto);
            $dto->setData($data->getPassword());
            $password = $this->securityRequestManager->decryptRequest($dto);

            $data->setUsername($username['content']);
            $data->setPassword($password['content']);
        }

        return $data;
    }

    /**
     * Return a UserInterface object based on the credentials.
     *
     * The *credentials* are the return value from getCredentials()
     *
     * You may throw an AuthenticationException if you wish. If you return
     * null, then a UsernameNotFoundException is thrown for you.
     *
     * @param mixed $credentials
     * @return User
     * @throws ApiException
     * @throws ExceptionInterface
     * @throws GuzzleException
     */
    public function getUser($credentials): User
    {
        $data = $this->userService->login($credentials);

        return $this->denormalizer->denormalize($data, User::class);
    }

    /**
     * Called when authentication executed and was successful!
     *
     * This should return the Response sent back to the user, like a
     * RedirectResponse to the last page they visited.
     *
     * If you return null, the current request will continue, and the user
     * will be authenticated. This makes sense, for example, with an API.
     * @param Request $request
     * @param TokenInterface $token
     * @param string $firewallName
     * @return JWTAuthenticationSuccessResponse|Response|null
     * @throws ApiException
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        /**
         * @var $user User
         */
        $user = $token->getUser();

        $jwt = $this->tokenManager->create($user);

        $response = new JWTAuthenticationSuccessResponse($jwt);

        $event = new AuthenticationSuccessEvent([
            'token' => $jwt,
        ], $user, $response);

        $this->dispatcher->dispatch($event, Events::AUTHENTICATION_SUCCESS);

        $data = $event->getData();

        $deviceId = Utils::parseDeviceId($request);

        $this->refreshTokenManager->addDeviceId($data['refresh_token'], $deviceId);

        $data['change_password'] = false;

        $response->setData($data);

        return $response;
    }

    /**
     * Called when authentication executed, but failed (e.g. wrong username password).
     *
     * This should return the Response sent back to the user, like a
     * RedirectResponse to the login page or a 403 response.
     *
     * If you return null, the request will continue, but the user will
     * not be authenticated. This is probably not what you want to do.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     *
     * @return Response|null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse(['code' => -1, 'message' => strtr($exception->getMessageKey(), $exception->getMessageData())], 200);
    }

    /**
     * Create a passport for the current request.
     *
     * The passport contains the user, credentials and any additional information
     * that has to be checked by the Symfony Security system. For example, a login
     * form authenticator will probably return a passport containing the user, the
     * presented password and the CSRF token value.
     *
     * You may throw any AuthenticationException in this method in case of error (e.g.
     * a UserNotFoundException when the user cannot be found).
     *
     * @param Request $request
     * @throws ApiException
     * @throws ExceptionInterface
     * @throws GuzzleException
     * @throws AuthenticationException
     * @return Passport
     */
    public function authenticate(Request $request)
    {
        $data = $this->getCredentials($request);

        $user = $this->getUser($data);
        if (!$user)
            throw new AuthenticationException('User not found.');

        return new SelfValidatingPassport(new UserBadge($user->getToken()));
    }
}
