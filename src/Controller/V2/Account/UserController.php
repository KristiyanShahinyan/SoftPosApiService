<?php

namespace App\Controller\V2\Account;

use App\Dto\Request\ChangePasswordDto;
use App\Dto\Request\ForgotPasswordDto;
use App\Dto\Request\ResetPasswordDto;
use App\EntityManager\RefreshTokenManager;
use App\RequestManager\Account\UserRequestManager;
use App\RequestManager\Notification\NotificationRequestManager;
use App\Security\User;
use App\Service\SessionManagementService;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Phos\Controller\AbstractApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class UserController
 * @package App\Controller\V2
 *
 *
 */
class UserController extends AbstractApiController
{

    /**
     * @var UserRequestManager
     */
    protected UserRequestManager $userService;

    /**
     * @var NotificationRequestManager
     */
    private NotificationRequestManager $notificationService;

    /**
     * @var RouterInterface
     */
    private RouterInterface $router;

    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parameterBag;

    /**
     * @var JWTTokenManagerInterface
     */
    private JWTTokenManagerInterface $tokenManager;

    /**
     * @var EventDispatcherInterface
     */
    private EventDispatcherInterface $dispatcher;

    /**
     * @var RefreshTokenManager
     */
    private RefreshTokenManager $refreshTokenManager;

    /**
     * UserController constructor.
     * @param UserRequestManager $userService
     * @param ParameterBagInterface $parameterBag
     * @param NotificationRequestManager $notificationService
     * @param RouterInterface $router
     * @param JWTTokenManagerInterface $tokenManager
     * @param EventDispatcherInterface $dispatcher
     * @param RefreshTokenManager $refreshTokenManager
     */
    public function __construct(
        UserRequestManager $userService,
        ParameterBagInterface $parameterBag,
        NotificationRequestManager $notificationService,
        RouterInterface $router,
        JWTTokenManagerInterface $tokenManager,
        EventDispatcherInterface $dispatcher,
        RefreshTokenManager $refreshTokenManager)
    {
        $this->userService = $userService;
        $this->notificationService = $notificationService;
        $this->router = $router;
        $this->parameterBag = $parameterBag;
        $this->tokenManager = $tokenManager;
        $this->dispatcher = $dispatcher;
        $this->refreshTokenManager = $refreshTokenManager;
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     * @throws GuzzleException
     */
    public function changePassword(Request $request): JsonResponse
    {
        /**
         * @var ChangePasswordDto $dto
         */
        $dto = $this->deserialize($request->getContent(), ChangePasswordDto::class);

        $this->validate($dto);

        $token = $this->getUser()->getToken();

        $this->userService->changePassword($dto, $token);

        return $this->success();
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws Exception
     * @throws GuzzleException
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        /**
         * @var ForgotPasswordDto $dto
         */
        $dto = $this->deserialize($request->getContent(), ForgotPasswordDto::class);

        $this->validate($dto);

        $token = $this->userService->createForgottenPasswordToken($dto);

        $link = $this->router->generate('link_password_reset', compact('token'), RouterInterface::ABSOLUTE_URL);

        $email = $dto->getEmail();
        $result = $this->notificationService->sendPasswordResetEmail(compact('email', 'link'));

        return $this->success([
            'success' => $result['success'],
        ]);
    }

    /**
     * @param $token
     * @return Response
     */
    public function resetPasswordForm($token): Response
    {
        return new RedirectResponse('https://phos.cloud/forgotten-password/' . $token);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws Exception
     * @throws GuzzleException
     */
    public function resetPassword(Request $request): JsonResponse
    {

        $dto = $this->deserialize($request->getContent(), ResetPasswordDto::class);

        $this->validate($dto);

        //TODO: reset token method in service

        return $this->success();
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return JsonResponse
     * @throws Exception
     * @throws GuzzleException
     */
    public function loadLoyalApps(): JsonResponse
    {
        return $this->success([
            ['name' => 'Phos', 'target' => 'https://play.google.com/store/apps/details?id=digital.paynetics.phos', 'logo' => 'https://paynetics.digital/wp-content/uploads/2019/07/phossss-2.png', 'description' => 'Payment',]
        ]);
    }

    /**
     * @param SessionManagementService $sessionManagementService
     * @param string $username
     * @return JsonResponse
     */
    public function forceLogoutUser(SessionManagementService $sessionManagementService, string $username): JsonResponse
    {
        $sessionManagementService->invalidateUserSessions($username);

        return $this->success(['success' => true]);
    }

    /**
     * @param SessionManagementService $sessionManagementService
     * @param string $deviceId
     * @return JsonResponse
     */
    public function forceLogoutDevice(SessionManagementService $sessionManagementService, string $deviceId): JsonResponse
    {
        $sessionManagementService->invalidateDeviceSessions($deviceId);

        return $this->success(['success' => true,]);
    }

    /**
     * @param string $userToken
     * @param string $deviceId
     * @return JsonResponse
     */
    public function login(string $userToken, string $deviceId): JsonResponse
    {
        $user = new User();
        $user->setToken($userToken);
        $user->setRoles([]);

        $jwt = $this->tokenManager->create($user);

        $response = new JWTAuthenticationSuccessResponse($jwt);

        $event = new AuthenticationSuccessEvent([
            'token' => $jwt,
        ], $user, $response);

        $this->dispatcher->dispatch($event, Events::AUTHENTICATION_SUCCESS);

        $data = $event->getData();

        $this->refreshTokenManager->addDeviceId($data['refresh_token'], $deviceId);

        $data['change_password'] = false;

        return $this->success($data);
    }

}
