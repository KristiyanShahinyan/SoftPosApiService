<?php

namespace App\Controller\V2\Transaction;

use App\Dto\Request\AnalyticsDto;
use App\RequestManager\Terminal\ConfigurationRequestManager;
use App\RequestManager\Transaction\TransactionRequestManager;
use DateTime;
use DateTimeZone;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Phos\Controller\AbstractApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AnalyticsController
 * @package App\Controller\V2
 *
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class AnalyticsController extends AbstractApiController
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
    public function __construct(ConfigurationRequestManager $configurationService, TransactionRequestManager $transactionService)
    {
        $this->configurationService = $configurationService;
        $this->transactionService = $transactionService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     * @throws GuzzleException
     */
    public function getSalesTotal(Request $request): JsonResponse
    {
        /**
         * @var AnalyticsDto $analyticsDto
         */
        $analyticsDto = $this->deserialize($request->getContent(), AnalyticsDto::class);

        $this->validate($analyticsDto, ['groups' => 'sales']);

        $analyticsDto->setDate($analyticsDto->getDate() ?: new DateTime('now', new DateTimeZone($analyticsDto->getTimeZone())));

        $analyticsDto->setType('daily');
        $dailyData = $this->transactionService->getAnalytics($this->getUser()->getToken(), $analyticsDto);

        $analyticsDto->setType('monthly');
        $monthlyData = $this->transactionService->getAnalytics($this->getUser()->getToken(), $analyticsDto);

        return $this->success([
            'daily'   => $dailyData['sales'],
            'monthly' => $monthlyData['sales'],
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
        $dto = $this->deserialize($request->getContent(), AnalyticsDto::class);

        $this->validate($dto, ['groups' => 'analytics']);

        $data = $this->transactionService->getAnalytics($this->getUser()->getToken(), $dto);

        return $this->success($data);
    }
}
