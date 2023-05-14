<?php

namespace App\Security;

use App\Builder\SecurityBuilder;
use App\Dto\Request\StoreFilterDto;
use App\Dto\Request\TerminalFilterDto;
use App\EntityManager\RefreshTokenManager;
use App\RequestManager\Account\UserRequestManager;
use App\RequestManager\Security\SecurityRequestManager;
use App\RequestManager\Terminal\StoreRequestManager;
use App\RequestManager\Terminal\TerminalRequestManager;
use GuzzleHttp\Exception\GuzzleException;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Phos\Exception\ApiException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * Class TokenAuthenticatorV2
 * @package App\Security
 */
class TokenAuthenticatorV2 extends TokenAuthenticator
{
    /**
     * @var StoreRequestManager
     */
    private $storeRequestManager;

    /**
     * @var TerminalRequestManager
     */
    private $terminalRequestManager;

    /**
     * TokenAuthenticatorV2 constructor.
     * @param EventDispatcherInterface $dispatcher
     * @param JWTTokenManagerInterface $tokenManager
     * @param RefreshTokenManager $refreshTokenManager
     * @param StoreRequestManager $storeRequestManager
     * @param TerminalRequestManager $terminalRequestManager
     * @param SecurityRequestManager $securityRequestManager
     * @param SecurityBuilder $builder
     * @param UserRequestManager $userService
     */
    public function __construct(EventDispatcherInterface $dispatcher,
                                JWTTokenManagerInterface $tokenManager,
                                RefreshTokenManager $refreshTokenManager,
                                StoreRequestManager $storeRequestManager,
                                TerminalRequestManager $terminalRequestManager,
                                SecurityRequestManager $securityRequestManager,
                                SecurityBuilder $builder,
                                UserRequestManager $userService)
    {
        parent::__construct($dispatcher, $tokenManager, $refreshTokenManager, $userService, $securityRequestManager, $builder);
        $this->storeRequestManager = $storeRequestManager;
        $this->terminalRequestManager = $terminalRequestManager;
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     *
     * @return JWTAuthenticationSuccessResponse|Response|null
     *
     * @throws GuzzleException
     * @throws ApiException
     * @throws ExceptionInterface
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?Response
    {

        /**
         * @var JWTAuthenticationSuccessResponse $response
         */
        $response = parent::onAuthenticationSuccess($request, $token, $providerKey);

        $user = $token->getUser();

        foreach ($user->getMerchants() as &$merchant) {

            $storeFilter = new StoreFilterDto();
            $storeFilter->setMerchant($merchant->getId());

            $stores = $this->storeRequestManager->getAll(0, 0, $storeFilter);

            foreach ($stores['items'] as $i => &$store) {

                $terminalFilter = new TerminalFilterDto();
                $terminalFilter->setStore($store['id']);
                $terminalFilter->setUser($user->getId());

                $terminals = $this->terminalRequestManager->getAll(0, 0, $terminalFilter);

                if (count($terminals['items']) === 0) {
                    unset($stores['items'][$i]);
                    continue;
                }

                $store['terminals'] = $terminals['items'];
            }

            $merchant->setStores($stores['items']);
        }

        $data['merchants'] = $this->normalize($user->getMerchants());

        $data = [
            'code' => 0,
            'data' => array_merge($data, json_decode($response->getContent(), true))
        ];
        $response->setData($data);

        return $response;

    }
}
