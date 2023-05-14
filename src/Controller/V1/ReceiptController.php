<?php

namespace App\Controller\V1;

use App\Dto\Request\NotificationServiceReceiptSendDto;
use App\Dto\Request\ReceiptGenerateDto;
use App\Dto\Request\ReceiptSendDto;
use App\Dto\Request\ReceiptServiceReceiptGenerateDto;
use App\RequestManager\Notification\NotificationRequestManager;
use App\RequestManager\Receipt\ReceiptRequestManager;
use App\RequestManager\Transaction\TransactionRequestManager;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ReceiptController
 * @package App\Controller\V1
 *
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class ReceiptController extends BaseController
{
    /**
     * @var TransactionRequestManager
     */
    private TransactionRequestManager $transactionService;

    /**
     * @var ReceiptRequestManager
     */
    private ReceiptRequestManager $receiptService;

    /**
     * @var NotificationRequestManager
     */
    private NotificationRequestManager $notificationService;

    /**
     * ReceiptController constructor.
     * @param TransactionRequestManager $transactionService
     * @param ReceiptRequestManager $receiptService
     * @param NotificationRequestManager $notificationService
     */
    public function __construct(TransactionRequestManager $transactionService, ReceiptRequestManager $receiptService, NotificationRequestManager $notificationService)
    {
        $this->transactionService = $transactionService;
        $this->receiptService = $receiptService;
        $this->notificationService = $notificationService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     * @throws GuzzleException
     */
    public function generate(Request $request): JsonResponse
    {
        /**
         * @var ReceiptGenerateDto $dto
         */
        $dto = $this->process($request, ReceiptGenerateDto::class);

        $transaction = $this->transactionService->find($dto->getTransactionKey());

        $transaction['amount'] = (float)$transaction['amount']; // TODO fix in transaction service

        $receiptDto = new ReceiptServiceReceiptGenerateDto();
        $receiptDto->setTransaction($transaction);
        $receiptDto->setType($dto->getType());
        $receiptDto->setTimeZone($dto->getTimeZone());
        $receiptDto->setFormat($dto->getFormat());

        $result = $this->receiptService->generate($receiptDto);

        return $this->success($result);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Exception
     * @throws GuzzleException
     */
    public function send(Request $request): JsonResponse
    {
        /**
         * @var ReceiptSendDto $dto
         */
        $dto = $this->process($request, ReceiptSendDto::class);

        $transaction = $this->transactionService->find($dto->getTransactionKey());

        $transaction['amount'] = (float)$transaction['amount']; // TODO fix in transaction service
        if (array_key_exists('tipAmount', $transaction)) {
            $transaction['tipAmount'] = (float)$transaction['tipAmount']; // TODO fix in transaction service
        }

        $receiptDto = new NotificationServiceReceiptSendDto();
        $receiptDto->setTransaction($transaction);
        $receiptDto->setType($dto->getType());
        $receiptDto->setTimeZone($dto->getTimeZone());
        $receiptDto->setRecipient($dto->getRecipient());

        $result = $this->notificationService->sendReceipt($receiptDto);

        return $this->success($result);
    }
}
