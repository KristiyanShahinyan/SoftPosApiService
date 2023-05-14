<?php

namespace App\Controller\V1;

use App\Helper\Utils;
use App\RequestManager\Terminal\ConfigurationRequestManager;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TerminalController
 * @package App\Controller\V1
 *
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class TerminalController extends BaseController
{
    /**
     * @var ConfigurationRequestManager
     */
    protected ConfigurationRequestManager $configurationService;

    /**
     * TerminalController constructor.
     * @param ConfigurationRequestManager $configurationService
     */
    public function __construct(ConfigurationRequestManager $configurationService)
    {
        $this->configurationService = $configurationService;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws Exception
     * @throws GuzzleException
     */
    public function terminalConfig(Request $request): JsonResponse
    {
        $terminalConfig = $this->configurationService->getConfigByUser($this->getUser()->getId());

        $terminalConfig['currencyInfo'] = Utils::getCurrencyInfo($terminalConfig['currency']);

        return $this->success($terminalConfig);
    }
}
