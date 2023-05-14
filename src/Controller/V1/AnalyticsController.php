<?php

namespace App\Controller\V1;

use App\Dto\Request\AnalyticsDto;
use App\RequestManager\Terminal\ConfigurationRequestManager;
use App\RequestManager\Transaction\TransactionRequestManager;
use DateTime;
use DateTimeZone;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class AnalyticsController
 * @package App\Controller\V1
 *
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class AnalyticsController extends BaseController
{

    /**
     * @var ConfigurationRequestManager
     */
    protected ConfigurationRequestManager $configurationService;

    /**
     * @var TransactionRequestManager
     */
    protected TransactionRequestManager $transactionService;

    /**
     * AnalyticsController constructor.
     * @param ConfigurationRequestManager $configurationService
     * @param TransactionRequestManager $transactionService
     */
    public function __construct(ConfigurationRequestManager $configurationService,
                                TransactionRequestManager $transactionService)
    {
        $this->configurationService = $configurationService;
        $this->transactionService = $transactionService;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws Exception
     * @throws GuzzleException
     */
    public function getSalesTotal(Request $request): JsonResponse
    {

        /**
         * @var AnalyticsDto $analyticsDto
         */
        $analyticsDto = $this->process($request, AnalyticsDto::class, 'sales');

        // This if check is unnecessary, the android app only sends timezone
        if ($analyticsDto->getDate() === null) {
            $analyticsDto->setDate(new DateTime('now', new DateTimeZone($analyticsDto->getTimeZone())));
        }

        $userToken = $this->getUser()->getToken();

        $analyticsDto->setType('daily');
        $dailyData = $this->transactionService->getAnalytics($userToken, $analyticsDto);

        $analyticsDto->setType('monthly');
        $monthlyData = $this->transactionService->getAnalytics($userToken, $analyticsDto);

        $terminalConfig = $this->configurationService->getConfigByUser($this->getUser()->getId());

        return $this->success([
            'daily'    => $dailyData['sales'],
            'monthly'  => $monthlyData['sales'],
            'currency' => $terminalConfig['currency'] ?? null,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws Exception
     * @throws GuzzleException
     */
    public function getAnalyticsData(Request $request): JsonResponse
    {

        /**
         * @var AnalyticsDto $dto
         */
        $dto = $this->process($request, AnalyticsDto::class, 'analytics');
        $data = $this->transactionService->getAnalytics($this->getUser()->getToken(), $dto);

        $terminalConfig = $this->configurationService->getConfigByUser($this->getUser()->getId());

        $data['currency'] = $terminalConfig['currency'] ?? null;

        return $this->success($data);
    }
}
