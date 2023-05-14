<?php

namespace App\Controller\V2\Terminal;

use App\Helper\Utils;
use App\RequestManager\Terminal\ConfigurationRequestManager;
use GuzzleHttp\Exception\GuzzleException;
use Phos\Controller\AbstractApiController;
use Phos\Exception\ApiException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class TerminalController
 * @package App\Controller\V2
 *
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class ConfigurationController extends AbstractApiController
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
     * @param string $token
     * @return JsonResponse
     *
     * @throws ApiException
     * @throws GuzzleException
     */
    public function show(string $token): JsonResponse
    {

        $terminalConfig = $this->configurationService->find($token);

        return $this->success(Utils::base64Encode(gzcompress($terminalConfig, 9)));
    }
}
