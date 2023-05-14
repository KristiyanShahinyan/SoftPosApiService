<?php

namespace App\Controller\V1;

use App\Builder\TransactionBuilder;
use App\Builder\TransactionFilterBuilder;
use App\Builder\TransactionListBuilder;
use App\Constants\TransactionTypes;
use App\Dto\ConfigurationDto;
use App\Dto\Request\TransactionsDto;
use App\Dto\TerminalDto;
use App\Dto\TransactionDto;
use App\Dto\TransactionInputDataDto;
use App\Exception\ExceptionCodes;
use App\Helper\PendingReversalsHelper;
use App\Helper\Utils;
use App\RequestManager\Terminal\TerminalRequestManager;
use App\RequestManager\Transaction\NuapayRequestManager;
use App\RequestManager\Transaction\PockytRequestManager;
use App\RequestManager\Transaction\TransactionRequestManager;
use App\Security\User;
use App\Service\GatewayOperation;
use App\Service\MonitoringService;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use Phos\Exception\ApiException;
use Phos\Helper\LoggerTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class TransactionController extends BaseController
{
    use LoggerTrait;

    private TransactionRequestManager $transactionRequestManager;

    private TransactionFilterBuilder $transactionFilterBuilder;

    private TransactionListBuilder $transactionListBuilder;

    private GatewayOperation $gatewayOperation;

    private TransactionBuilder $transactionBuilder;

    protected MonitoringService $monitoringService;

    private TerminalRequestManager $terminalRequestManager;

    private PendingReversalsHelper $pendingReversalsHelper;

    private NuapayRequestManager $nuapayRequestManager;

    private PockytRequestManager $pockytRequestManager;

    public function __construct(
        TransactionRequestManager $transactionRequestManager,
        TransactionFilterBuilder $transactionFilterBuilder,
        TransactionListBuilder $transactionListBuilder,
        GatewayOperation $gatewayOperation,
        TransactionBuilder $transactionBuilder,
        MonitoringService $monitoringService,
        TerminalRequestManager $terminalRequestManager,
        PendingReversalsHelper $pendingReversalsHelper,
        NuapayRequestManager $nuapayRequestManager,
        PockytRequestManager $pockytRequestManager
    ) {
        $this->transactionRequestManager = $transactionRequestManager;
        $this->transactionFilterBuilder = $transactionFilterBuilder;
        $this->transactionListBuilder = $transactionListBuilder;
        $this->gatewayOperation = $gatewayOperation;
        $this->transactionBuilder = $transactionBuilder;
        $this->monitoringService = $monitoringService;
        $this->terminalRequestManager = $terminalRequestManager;
        $this->pendingReversalsHelper = $pendingReversalsHelper;
        $this->nuapayRequestManager = $nuapayRequestManager;
        $this->pockytRequestManager = $pockytRequestManager;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     * @throws Exception
     */
    public function index(Request $request): JsonResponse
    {
        /**
         * @var TransactionsDto $transactionsRequestDto
         */
        $transactionsRequestDto = $this->process($request, TransactionsDto::class);

        /**
         * @var User $user
         */
        $user = $this->getUser();

        $transactionFilterDto = $this->transactionFilterBuilder->buildTransactionFilterDto($transactionsRequestDto, $user->getToken());
        $transactionsResponse = $this->transactionRequestManager->getAll($transactionFilterDto);

        $transactionList = $this->transactionListBuilder->buildListDto($transactionsResponse);

        return $this->success($transactionList);
    }

    /**
     * @param string $token
     * @return JsonResponse
     * @throws GuzzleException
     * @throws ApiException
     */
    public function show(string $token): JsonResponse
    {
        $transactionsResponse = $this->transactionRequestManager->find($token);
        $transaction = $this->transactionListBuilder->buildDto($transactionsResponse);

        return $this->success($transaction);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ApiException
     * @throws ExceptionInterface
     * @throws GuzzleException
     * @throws JsonException
     */
    public function sale(Request $request): JsonResponse
    {
        $deviceId = Utils::parseDeviceId($request);
        $appDetails = Utils::parseAppDetails($request);

        /** @var TransactionInputDataDto $inputDataDto */
        $inputDataDto = $this->process($request, TransactionInputDataDto::class, 'sale');

        /** @var User $user */
        $user = $this->getUser();

        $configurationDto = $this->gatewayOperation->getConfiguration(
            $user,
            TransactionTypes::AUTH,
            $inputDataDto->getTerminalToken(),
            $inputDataDto->getScaType(),
            $inputDataDto->getCurrency()
        );
        
        $transactionDto = $this->transactionBuilder->buildCreate(
            $user,
            $configurationDto,
            TransactionTypes::AUTH,
            $inputDataDto->getAmount(),
            $inputDataDto->getCurrency(),
            $deviceId,
            $inputDataDto->getLatitude(),
            $inputDataDto->getLongitude(),
            $inputDataDto->getNfcTime(),
            $inputDataDto->getCardType(),
            $this->getChannel($request),
            $inputDataDto->getMetadata(),
            $inputDataDto->getScaType(),
            $inputDataDto->getOrderReference(),
            $appDetails
        );

        $transactionDto->setTipAmount($inputDataDto->getTipAmount());
        $transactionDto->setSurchargeAmount($inputDataDto->getSurchargeAmount());

        //$this->monitoringCheck($transactionDto);

        $createdTransactionArr = $this->transactionRequestManager->create($transactionDto);
        $unfinishedTransactions = $this->findUnfinishedTransactions($configurationDto);

        return $this->success([
            'transaction' => $createdTransactionArr,
            'user' => $user,
            'configuration' => $configurationDto,
            'unfinished_transactions' => $unfinishedTransactions
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ApiException
     * @throws ExceptionInterface
     * @throws GuzzleException
     * @throws JsonException
     */
    public function refund(Request $request): JsonResponse
    {
        $deviceId = Utils::parseDeviceId($request);
        $appDetails = Utils::parseAppDetails($request);

        /** @var TransactionInputDataDto $inputDataDto */
        $inputDataDto = $this->process($request, TransactionInputDataDto::class, 'refund');

        /** @var User $user */
        $user = $this->getUser();

        $linkedTransactionArr = $this->transactionRequestManager->find($inputDataDto->getTransactionKey());

        /** @var TransactionDto $linkedTransactionDto */
        $linkedTransactionDto = $this->denormalize($linkedTransactionArr, TransactionDto::class);

        if ($linkedTransactionDto->getStatus() !== 1) {
            throw new ApiException(ExceptionCodes::UNVOIDABLE_TRANSACTION, ['Transaction was not successful']);
        }

        $configurationDto = $this->gatewayOperation->getConfigurationFromTransaction($linkedTransactionDto, $user, TransactionTypes::REFUND);

        $terminal = $this->findTerminal($user, $linkedTransactionDto);
        $configurationDto->setSystemTraceAuditNumber($terminal->getSystemTraceAuditNumber());
        $configurationDto->setAcquirerSpecificData($terminal->getAcquirerSpecificData());

        $transactionDto = $this->transactionBuilder->buildCreate(
            $user,
            $configurationDto,
            TransactionTypes::REFUND,
            $inputDataDto->getAmount(),
            $inputDataDto->getCurrency(),
            $deviceId,
            $inputDataDto->getLatitude(),
            $inputDataDto->getLongitude(),
            0,
            null,
            $this->getChannel($request),
            $inputDataDto->getMetadata(),
            $linkedTransactionDto->getScaType(),
            $linkedTransactionDto->getOrderReference(),
            $appDetails
        );

        $refundableAmount = $linkedTransactionDto->getRefundableAmount() - $transactionDto->getAmount();
        if ($refundableAmount < 0) {
            throw new ApiException(
                ExceptionCodes::TRANSACTION_ALREADY_REFUNDED,
                [sprintf('Transaction %s already voided or fully refunded', $linkedTransactionDto->getTrnKey())]
            );
        }

        $refundUpdate = new TransactionDto();
        $refundUpdate->setRefundableAmount($transactionDto->getAmount());
        $updatedTransaction = $this->transactionRequestManager->update_refund($refundUpdate, $linkedTransactionDto->getTrnKey());
        /** @var TransactionDto $linkedTransactionDto */
        $linkedTransactionDto = $this->denormalize($updatedTransaction, TransactionDto::class);

        $transactionDto->setLinkedTransaction($linkedTransactionDto->getId());

        $createdTransactionArr = $this->transactionRequestManager->create($transactionDto);
        $unfinishedTransactions = $this->findUnfinishedTransactions($configurationDto);

        return $this->success([
            'transaction' => $createdTransactionArr,
            'linkedTransaction' => $linkedTransactionDto,
            'user' => $user,
            'configuration' => $configurationDto,
            'unfinished_transactions' => $unfinishedTransactions
        ]);
    }

    /**
     * @param string $partner
     * @param Request $request
     * @return JsonResponse
     * @throws ApiException
     * @throws ExceptionInterface
     * @throws GuzzleException
     * @throws JsonException
     */
    public function void(string $partner, Request $request): JsonResponse
    {
        $operation = $partner === 'pockyt' ? TransactionTypes::POCKYT_VOID : TransactionTypes::VOID;

        $deviceId = Utils::parseDeviceId($request);
        $appDetails = Utils::parseAppDetails($request);

        /** @var TransactionInputDataDto $inputDataDto */
        $inputDataDto = $this->process($request, TransactionInputDataDto::class, TransactionTypes::getOperationType($operation));

        /** @var User $user */
        $user = $this->getUser();

        $linkedTransactionArr = $this->transactionRequestManager->find($inputDataDto->getTransactionKey());

        /** @var TransactionDto $linkedTransactionDto */
        $linkedTransactionDto = $this->denormalize($linkedTransactionArr, TransactionDto::class);

        if ($linkedTransactionDto->getStatus() !== 1) {
            throw new ApiException(ExceptionCodes::UNVOIDABLE_TRANSACTION, ['Transaction was not successful']);
        }

        if (!in_array($linkedTransactionDto->getTransactionType(), [TransactionTypes::AUTH, TransactionTypes::POCKYT_SALE])) {
            throw new ApiException(ExceptionCodes::UNVOIDABLE_TRANSACTION, ['Only sales trx can be voided']);
        }

        if ($linkedTransactionDto->getVoidable() !== true) {
            throw new ApiException(ExceptionCodes::UNVOIDABLE_TRANSACTION, ['Transaction already voided']);
        }

        $configurationDto = $this->gatewayOperation->getConfigurationFromTransaction($linkedTransactionDto, $user, $operation);

        $terminal = $this->findTerminal($user, $linkedTransactionDto);
        $configurationDto->setSystemTraceAuditNumber($terminal->getSystemTraceAuditNumber());
        $configurationDto->setAcquirerSpecificData($terminal->getAcquirerSpecificData());

        $transactionDto = $this->transactionBuilder->buildCreate(
            $user,
            $configurationDto,
            $operation,
            $linkedTransactionDto->getAmount(),
            $linkedTransactionDto->getCurrency(),
            $deviceId,
            null,
            null,
            0,
            null,
            $this->getChannel($request),
            $inputDataDto->getMetadata(),
            $linkedTransactionDto->getScaType(),
            $linkedTransactionDto->getOrderReference(),
            $appDetails,
            $linkedTransactionDto->getPaymentMethod()
        );

        $transactionDto->setLinkedTransaction($linkedTransactionDto->getId());

        $refundUpdate = new TransactionDto();
        $refundUpdate->setRefundableAmount($transactionDto->getAmount());
        $updatedTransaction = $this->transactionRequestManager->update_refund($refundUpdate, $linkedTransactionDto->getTrnKey());
        /** @var TransactionDto $linkedTransactionDto */
        $linkedTransactionDto = $this->denormalize($updatedTransaction, TransactionDto::class);

        $createdTransactionArr = $this->transactionRequestManager->create($transactionDto);

        // Should return it with true, in order the check to pass,
        // but it should be set to false if someone tries to refund/void again during the call
        $linkedTransactionDto->setVoidable(true);

        if ($partner !== 'phos') {
            $externalTransaction = $this->createExternalTransaction($partner, 'void', $createdTransactionArr);
            if (array_key_exists('result', $externalTransaction)) {
                $createdTransactionArr = array_merge($createdTransactionArr, $externalTransaction['result']);
            }
        }

        return $this->success([
            'transaction' => $createdTransactionArr,
            'linkedTransaction' => $linkedTransactionDto,
            'user' => $user,
            'configuration' => $configurationDto,
        ]);
    }

    public function saleExternal(string $partner, Request $request): JsonResponse
    {
        $operation = $partner === 'nuapay' ? TransactionTypes::NUAPAY_SALE : TransactionTypes::POCKYT_SALE;

        $deviceId = Utils::parseDeviceId($request);
        $appDetails = Utils::parseAppDetails($request);

        /** @var TransactionInputDataDto $inputDataDto */
        $inputDataDto = $this->process($request, TransactionInputDataDto::class, TransactionTypes::getOperationType($operation));

        /** @var User $user */
        $user = $this->getUser();

        $configurationDto = $this->gatewayOperation->getConfiguration(
            $user,
            $operation,
            $inputDataDto->getTerminalToken(),
            null,
            $inputDataDto->getCurrency()
        );

        $transactionDto = $this->transactionBuilder->buildCreate(
            $user,
            $configurationDto,
            $operation,
            $inputDataDto->getAmount(),
            $inputDataDto->getCurrency(),
            $deviceId,
            $inputDataDto->getLatitude(),
            $inputDataDto->getLongitude(),
            null,
            null,
            $this->getChannel($request),
            $inputDataDto->getMetadata(),
            null,
            $inputDataDto->getOrderReference(),
            $appDetails,
            $inputDataDto->getPaymentMethod()
        );

        $transactionDto->setTipAmount($inputDataDto->getTipAmount());
        if ($inputDataDto->getTipAmount()) {
            $transactionDto->setAmount($inputDataDto->getAmount() + $inputDataDto->getTipAmount());
        }

        $createdTransactionArr = $this->transactionRequestManager->create($transactionDto);

        return $this->success($this->createExternalTransaction($partner, 'sale', $createdTransactionArr));
    }

    public function refundExternal(string $partner, Request $request): JsonResponse
    {
        $operation = $partner === 'nuapay' ? TransactionTypes::NUAPAY_REFUND : TransactionTypes::POCKYT_REFUND;
        $deviceId = Utils::parseDeviceId($request);
        $appDetails = Utils::parseAppDetails($request);

        /** @var TransactionInputDataDto $inputDataDto */
        $inputDataDto = $this->process($request, TransactionInputDataDto::class, TransactionTypes::getOperationType($operation));

        /** @var User $user */
        $user = $this->getUser();

        $linkedTransactionArr = $this->transactionRequestManager->find($inputDataDto->getTransactionKey());

        /** @var TransactionDto $linkedTransactionDto */
        $linkedTransactionDto = $this->denormalize($linkedTransactionArr, TransactionDto::class);

        if ($linkedTransactionDto->getStatus() !== 1) {
            throw new ApiException(ExceptionCodes::UNVOIDABLE_TRANSACTION, ['Transaction was not successful']);
        }

        $configurationDto = $this->gatewayOperation->getConfigurationFromTransaction($linkedTransactionDto, $user, $operation);

        $terminal = $this->findTerminal($user, $linkedTransactionDto);
        $configurationDto->setSystemTraceAuditNumber($terminal->getSystemTraceAuditNumber());
        $configurationDto->setAcquirerSpecificData($terminal->getAcquirerSpecificData());

        $transactionDto = $this->transactionBuilder->buildCreate(
            $user,
            $configurationDto,
            $operation,
            $inputDataDto->getAmount(),
            $inputDataDto->getCurrency(),
            $deviceId,
            $inputDataDto->getLatitude(),
            $inputDataDto->getLongitude(),
            0,
            null,
            $this->getChannel($request),
            $inputDataDto->getMetadata(),
            $linkedTransactionDto->getScaType(),
            $linkedTransactionDto->getOrderReference(),
            $appDetails,
            $linkedTransactionDto->getPaymentMethod()
        );

        $refundableAmount = $linkedTransactionDto->getRefundableAmount() - $transactionDto->getAmount();
        if ($refundableAmount < 0) {
            throw new ApiException(
                ExceptionCodes::TRANSACTION_ALREADY_REFUNDED,
                [sprintf('Transaction %s already voided or fully refunded', $linkedTransactionDto->getTrnKey())]
            );
        }

        $refundUpdate = new TransactionDto();
        $refundUpdate->setRefundableAmount($transactionDto->getAmount());
        $updatedTransaction = $this->transactionRequestManager->update_refund($refundUpdate, $linkedTransactionDto->getTrnKey());
        /** @var TransactionDto $linkedTransactionDto */
        $linkedTransactionDto = $this->denormalize($updatedTransaction, TransactionDto::class);

        $transactionDto->setLinkedTransaction($linkedTransactionDto->getId());

        $createdTransactionArr = $this->transactionRequestManager->create($transactionDto);

        $externalTransaction = $this->createExternalTransaction($partner, 'refund', $createdTransactionArr);
        if (array_key_exists('result', $externalTransaction)) {
            $createdTransactionArr = array_merge($createdTransactionArr, $externalTransaction['result']);
        }

        return $this->success([
            'transaction' => $createdTransactionArr,
            'linkedTransaction' => $linkedTransactionDto,
            'user' => $user,
            'configuration' => $configurationDto,
            'unfinished_transactions' => null
        ]);
    }

    private function getChannel(Request $request): string
    {
        return $request->headers->get('X-channel') ?: 'mobile'; 
    }

    /**
     * @param ConfigurationDto $configurationDto
     * @return array|null
     * @throws ApiException
     * @throws GuzzleException
     */
    private function findUnfinishedTransactions(ConfigurationDto $configurationDto): ?array
    {
        $shouldClearReversals = $this->pendingReversalsHelper->shouldClearReversals($configurationDto->getAcquiringInstitutionIdentificationCode());

        if (!$shouldClearReversals)
            return null;

        $filter = $this->transactionFilterBuilder->unfinishedTransactionsFilter($configurationDto);
        $transactionsResponse = $this->transactionRequestManager->getAll($filter);

        return array_map(function ($transaction) { return $transaction['trn_key']; }, $transactionsResponse['items']);
    }

    /**
     * @param $user
     * @param TransactionDto $linkedTransactionDto
     * @return TerminalDto
     * @throws ApiException
     * @throws ExceptionInterface
     * @throws GuzzleException
     * @throws JsonException
     */
    private function findTerminal($user, TransactionDto $linkedTransactionDto): TerminalDto
    {
        $criteria = [
            'posTerminalIdCode' => $linkedTransactionDto->getTerminalId(),
            'user' => $user->getId()
        ];
        if (!empty($linkedTransactionDto->getTerminalToken()))
            $criteria['token'] = $linkedTransactionDto->getTerminalToken();

        $terminal = $this->terminalRequestManager->find($criteria);

        return $this->denormalize($terminal, TerminalDto::class);
    }

    private function createExternalTransaction(string $partner, string $operation, array $transaction): array
    {
        $method = 'create' . ucfirst($operation);
        if ($partner === 'nuapay') {
            return call_user_func_array([$this->nuapayRequestManager, $method], [$transaction]);
        } elseif ($partner === 'pockyt') {
            return call_user_func_array([$this->pockytRequestManager, $method], [$transaction]);
        }

        return [];
    }

    /**
     * @param TransactionDto $transactionDto
     * @return void
     * @throws ApiException
     * @throws GuzzleException
     */
    private function monitoringCheck(TransactionDto $transactionDto): void
    {
        $monitoringValidation = $this->monitoringService->highSeverityMonitoring($transactionDto);
        if ($monitoringValidation->passes() === false){
            $this->logger->critical("Monitoring rules conditions not met!", $monitoringValidation->getErrors());

            throw new ApiException(ExceptionCodes::MONITORING_RULES_VIOLATION);
        }
    }
}
