<?php

namespace App\Controller\V2\Transaction;

use App\Builder\TransactionFilterBuilder;
use App\Builder\TransactionListBuilder;
use App\Dto\Request\TransactionsDto;
use App\RequestManager\Transaction\TransactionRequestManager;
use App\Security\User;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Phos\Controller\AbstractApiController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * Class TransactionController
 * @package App\Controller\V2
 *
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class TransactionController extends AbstractApiController
{

    /**
     * @var TransactionRequestManager
     */
    private TransactionRequestManager $transactionService;

    /**
     * @var TransactionFilterBuilder
     */
    private TransactionFilterBuilder $transactionFilterBuilder;

    /**
     * @var TransactionListBuilder
     */
    private TransactionListBuilder $transactionListBuilder;

    /**
     * TransactionController constructor.
     * @param TransactionRequestManager $transactionService
     * @param TransactionFilterBuilder $transactionFilterBuilder
     * @param TransactionListBuilder $transactionListBuilder
     */
    public function __construct(TransactionRequestManager $transactionService,
                                TransactionFilterBuilder $transactionFilterBuilder,
                                TransactionListBuilder $transactionListBuilder)
    {
        $this->transactionService = $transactionService;
        $this->transactionFilterBuilder = $transactionFilterBuilder;
        $this->transactionListBuilder = $transactionListBuilder;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws GuzzleException
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function index(Request $request): JsonResponse
    {
        /**
         * @var TransactionsDto $transactionsRequestDto
         */
        $transactionsRequestDto = $this->deserialize($request->getContent(), TransactionsDto::class);

        /**
         * @var User $user
         */
        $user = $this->getUser();

        $transactionFilterDto = $this->transactionFilterBuilder->buildTransactionFilterDto($transactionsRequestDto, $user->getToken());
        $transactionsResponse = $this->transactionService->getAll($transactionFilterDto);
        $transactionList = $this->transactionListBuilder->buildListDto($transactionsResponse);

        return $this->success($transactionList);
    }
}
