<?php /** @noinspection DuplicatedCode */

namespace App\Builder;

use App\Constants\TransactionTypes;
use App\Dto\DtoInterface;
use App\Dto\Response\TransactionDto;
use App\Dto\Response\TransactionListDto;
use DateTime;

/**
 * Class TransactionListBuilder
 * @package App\Builder
 */
class TransactionListBuilder extends ResponseListBuilder
{
    protected const DTO_CLASS_NAME = TransactionListDto::class;

    /**
     * @param array $transaction
     * @return DtoInterface
     */
    public function buildDto(array $transaction): DtoInterface
    {
        $transactionDto = new TransactionDto();
        $transactionDto->setTransactionKey($transaction['trn_key'] ?? '');
        $transactionDto->setAmount((float)($transaction['amount'] ?? 0));
        $transactionDto->setCurrency($transaction['currency'] ?? '');

        if (isset($transaction['pos_local_date_time']) && $transaction['pos_local_date_time']) {
            $transactionDto->setAddDate(DateTime::createFromFormat('Y-m-d H:i:s', $transaction['pos_local_date_time']));
        }

        $transactionDto->setStatus($transaction['status'] ?? 0);
        $transactionDto->setCard($transaction['card_pan_obfuscated'] ?? $transaction['card_pan'] ?? '');
        $transactionDto->setCardType($transaction['card_type'] ?? '');
        $transactionDto->setOperation(TransactionTypes::getOperationType($transaction['transaction_type']));

        if ($transactionDto->getStatus() == 0) { //TODO Remove this when we no longer need backwards compatibility. See PHOS-2441
            $transactionDto->setActionCode(-1);
        } else {
            $transactionDto->setActionCode((int)($transaction['error_code'] ?? -1));
        }
        $transactionDto->setStan($transaction['stan'] ?? $transaction['pos_system_trace_audit_number'] ?? '');
        $transactionDto->setAuthCode($transaction['auth_code'] ?? $transaction['pos_auth_code'] ?? '');
        $transactionDto->setApplicationId($transaction['application_id'] ?? $transaction['pos_application_id'] ?? '');
        $transactionDto->setRefundableAmount((float)($transaction['refundable_amount'] ?? 0));
        $transactionDto->setVoidable($transaction['voidable'] ?? false);
        $transactionDto->setScaType($transaction['sca_type']);
        $transactionDto->setRetrievalReferenceNumber($transaction['retrieval_reference_number'] ?? '');
        $transactionDto->setTipAmount((float)($transaction['tip_amount'] ?? 0));
        $transactionDto->setMetadata($transaction['metadata'] ?? null);
        $transactionDto->setOrderReference($transaction['order_reference'] ?? null);
        $transactionDto->setSurchargeAmount($transaction['surcharge_amount'] ?? null);

        $accountNumber = null;
        if (!empty($transaction['acquirer_specific_data']['debtorAccount'])) {
            $accountNumber = '*' . substr($transaction['acquirer_specific_data']['debtorAccount'], -4, 4);
        }
        $transactionDto->setAccountNumber($accountNumber);
        $transactionDto->setPaymentMethod($transaction['payment_method']);

        return $transactionDto;
    }

}
