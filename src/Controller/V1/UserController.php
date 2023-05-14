<?php

namespace App\Controller\V1;

use App\Dto\Request\ChangePasswordDto;
use App\Dto\Request\ForgotPasswordDto;
use App\Dto\Request\ResetPasswordDto;
use App\RequestManager\Account\UserRequestManager;
use App\RequestManager\Notification\NotificationRequestManager;
use App\Service\SessionManagementService;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class UserController
 * @package App\Controller\V1
 */
class UserController extends BaseController
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
     * UserController constructor.
     * @param UserRequestManager $userService
     * @param NotificationRequestManager $notificationService
     * @param RouterInterface $router
     */
    public function __construct(UserRequestManager $userService,
                                NotificationRequestManager $notificationService,
                                RouterInterface $router)
    {
        $this->userService = $userService;
        $this->notificationService = $notificationService;
        $this->router = $router;
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
        $dto = $this->process($request, ChangePasswordDto::class);

        $this->validate($dto);

        $token = $this->getUser()->getToken();

        $result = $this->userService->changePassword($dto, $token);

        return $this->success(['success' => $result === null,]);
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
        /** @var ForgotPasswordDto $dto */
        $dto = $this->process($request, ForgotPasswordDto::class);

        /** @var array $forgottenPassword */
        $forgottenPassword = $this->userService->createForgottenPasswordToken($dto);
        $dto->setToken($forgottenPassword['token']);

        $token = $dto->getToken();

        $link = $this->router->generate('link_password_reset', compact('token'), UrlGeneratorInterface::ABSOLUTE_URL);

        if (getenv('APP_ENV') == 'prod' && !preg_match('/\.(localhost|hostloopback)$/', $request->getHost())) {
            $link = preg_replace('/^http:/', 'https:', $link);
        }

        $email = $dto->getEmail();
        $this->notificationService->sendPasswordResetEmail(compact('email', 'link'));

        return $this->success(['success' => true]);
    }

    /**
     * @param string $token
     * @return Response
     */
    public function resetPasswordForm(string $token): Response
    {
        return new RedirectResponse($this->userService->getResetPasswordLink($token));
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
        $dto = $this->process($request, ForgotPasswordDto::class, ['groups' => ForgotPasswordDto::GROUP_SET_NEW_PASSWORD]);

        $this->userService->setNewPasswordForToken($dto);

        return $this->success(['success' => true]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return JsonResponse
     * @throws Exception
     */
    public function loadLoyalApps(): JsonResponse
    {

        return $this->success([
            ['name' => 'Phyre', 'type' => 'market', 'target' => 'com.phyreapp', 'logo' => 'https://techweek.ro/2019/wp-content/uploads/2019/05/phyre.png', 'description' => 'Wallet']
        ]);
    }

    /**
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
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
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param SessionManagementService $sessionManagementService
     * @param string $deviceId
     * @return JsonResponse
     */
    public function forceLogoutDevice(SessionManagementService $sessionManagementService, string $deviceId): JsonResponse
    {
        $sessionManagementService->invalidateDeviceSessions($deviceId);

        return $this->success(['success' => true,]);
    }

}
