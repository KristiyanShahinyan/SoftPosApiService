<?php

namespace App\Helper;

use App\Dto\Request\TransactionFilterDto;
use App\EntityManager\RefreshTokenManager;
use App\RequestManager\Transaction\TransactionRequestManager;
use App\Security\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class SdkAuthenticationHelper
{
    private ?array $specialAuthentications;

    private JWTTokenManagerInterface $JWTTokenManager;

    private EventDispatcherInterface $dispatcher;

    private RefreshTokenManager $refreshTokenManager;

    private TransactionRequestManager $transactionRequestManager;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(ContainerBagInterface $params,
                                JWTTokenManagerInterface $JWTTokenManager,
                                EventDispatcherInterface $dispatcher,
                                RefreshTokenManager $refreshTokenManager,
                                TransactionRequestManager $transactionRequestManager)
    {
        $this->specialAuthentications = $params->get('special_authentications');
        $this->JWTTokenManager = $JWTTokenManager;
        $this->dispatcher = $dispatcher;
        $this->refreshTokenManager = $refreshTokenManager;
        $this->transactionRequestManager = $transactionRequestManager;
    }

    public function specialAuthUser(string $issuer, string $deviceId, bool $withRefreshToken = false)
    {
        if (!array_key_exists($issuer, $this->specialAuthentications))
            return null;
        $token = $this->getToken($this->specialAuthentications[$issuer], $deviceId);

        return $withRefreshToken ? $token : $token['token'];
    }

    public function getToken(string $userToken, string $deviceId): array
    {
        $user = new User();
        $user->setRoles([]);
        $user->setToken($userToken);

        $token = $this->JWTTokenManager->create($user);

        $response = new JWTAuthenticationSuccessResponse($token);

        $event = new AuthenticationSuccessEvent([
            'token' => $token,
        ], $user, $response);
        $this->dispatcher->dispatch($event, Events::AUTHENTICATION_SUCCESS);

        $data = $event->getData();

        $this->refreshTokenManager->addDeviceId($data['refresh_token'], $deviceId);
        $data['trn_hash'] = $this->getUserLastTransaction($userToken);

        return $data;
    }

    private function getUserLastTransaction(string $userToken): string
    {
        $transactionFilterDto = new TransactionFilterDto();
        $transactionFilterDto->setUser($userToken);
        $transactionFilterDto->setLimit(1);
        $transactionFilterDto->setSort(['create_date' => 'DESC']);

        $transactionsResponse = $this->transactionRequestManager->getAll($transactionFilterDto);

        return !empty($transactionsResponse['items']) ? $transactionsResponse['items'][0]['trn_key'] : '';
    }
}