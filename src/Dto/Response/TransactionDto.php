<?php

namespace App\Dto\Response;

use App\Dto\DtoInterface;
use DateTime;

/**
 * Class TransactionDto
 * @package App\Dto\Response
 */
class TransactionDto implements DtoInterface
{
    /**
     * @var string
     */
    protected $transaction_key;

    /**
     * @var string
     */
    protected $operation;

    /**
     * @var double
     */
    protected $amount;

    /**
     * @var int|null
     */
    protected $user;

    /**
     * @var string
     */
    protected $currency;

    /**
     * @var DateTime
     * H:i:s'>")
     */
    protected $addDate;

    /**
     * @var DateTime|null
     */
    protected $executeDate;

    /**
     * @var int
     */
    protected $status;

    /**
     * @var string
     */
    protected $card;

    /**
     * @var string
     */
    protected $cardType;

    /**
     * @var int
     */
    protected $actionCode;

    /**
     * @var string
     */
    protected $stan;

    /**
     * @var string
     */
    protected $authCode;

    /**
     * @var string
     */
    protected $applicationId;

    /**
     * @var float
     */
    protected $refundableAmount;

    /**
     * @var bool
     */
    protected $voidable;

    /**
     * @var int
     */
    protected $scaType;

    /**
     * @var string|null
     */
    protected $retrievalReferenceNumber;

    /**
     * @var double
     */
    protected $tipAmount;

    /**
     * @var array|null
     */
    protected $metadata;

    protected ?string $orderReference = null;

    private ?float $surchargeAmount = null;

    private ?string $accountNumber = null;

    private ?int $paymentMethod = null;

    /**
     * @return string
     */
    public function getTransactionKey(): string
    {
        return $this->transaction_key;
    }

    /**
     * @param string $transaction_key
     */
    public function setTransactionKey(string $transaction_key): void
    {
        $this->transaction_key = $transaction_key;
    }

    /**
     * @return string
     */
    public function getOperation(): string
    {
        return $this->operation;
    }

    /**
     * @param string $operation
     */
    public function setOperation(string $operation): void
    {
        $this->operation = $operation;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getUser(): ?int
    {
        return $this->user;
    }

    /**
     * @param int $user
     */
    public function setUser(int $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return DateTime
     */
    public function getAddDate(): DateTime
    {
        return $this->addDate;
    }

    /**
     * @param DateTime $addDate
     */
    public function setAddDate(DateTime $addDate): void
    {
        $this->addDate = $addDate;
    }

    /**
     * @return DateTime
     */
    public function getExecuteDate(): ?DateTime
    {
        return $this->executeDate;
    }

    /**
     * @param DateTime $executeDate
     */
    public function setExecuteDate(DateTime $executeDate): void
    {
        $this->executeDate = $executeDate;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getCard(): string
    {
        return $this->card;
    }

    /**
     * @param string $card
     */
    public function setCard(string $card): void
    {
        $this->card = $card;
    }

    /**
     * @return string
     */
    public function getCardType(): string
    {
        return $this->cardType;
    }

    /**
     * @param string $cardType
     */
    public function setCardType(string $cardType): void
    {
        $this->cardType = $cardType;
    }

    /**
     * @return int
     */
    public function getActionCode(): int
    {
        return $this->actionCode;
    }

    /**
     * @param int $actionCode
     */
    public function setActionCode(int $actionCode): void
    {
        $this->actionCode = $actionCode;
    }

    /**
     * @return string
     */
    public function getStan(): string
    {
        return $this->stan;
    }

    /**
     * @param string $stan
     */
    public function setStan(string $stan): void
    {
        $this->stan = $stan;
    }

    /**
     * @return string
     */
    public function getAuthCode(): string
    {
        return $this->authCode;
    }

    /**
     * @param string $authCode
     */
    public function setAuthCode(string $authCode): void
    {
        $this->authCode = $authCode;
    }

    /**
     * @return string
     */
    public function getApplicationId(): string
    {
        return $this->applicationId;
    }

    /**
     * @param string $applicationId
     */
    public function setApplicationId(string $applicationId): void
    {
        $this->applicationId = $applicationId;
    }

    /**
     * @return float
     */
    public function getRefundableAmount(): float
    {
        return $this->refundableAmount;
    }

    /**
     * @param float $refundableAmount
     */
    public function setRefundableAmount(float $refundableAmount): void
    {
        $this->refundableAmount = $refundableAmount;
    }

    /**
     * @return bool
     */
    public function isVoidable(): bool
    {
        return $this->voidable;
    }

    /**
     * @param bool $voidable
     */
    public function setVoidable(bool $voidable): void
    {
        $this->voidable = $voidable;
    }

    /**
     * @return int
     */
    public function getScaType(): int
    {
        return $this->scaType;
    }

    /**
     * @param string $scaType
     */
    public function setScaType(string $scaType): void
    {
        $this->scaType = $scaType;
    }

    /**
     * @return string|null
     */
    public function getRetrievalReferenceNumber(): ?string
    {
        return $this->retrievalReferenceNumber;
    }

    /**
     * @param string|null $retrievalReferenceNumber
     */
    public function setRetrievalReferenceNumber(?string $retrievalReferenceNumber): void
    {
        $this->retrievalReferenceNumber = $retrievalReferenceNumber;
    }

    /**
     * @return float
     */
    public function getTipAmount(): float
    {
        return $this->tipAmount;
    }

    /**
     * @param float $tipAmount
     */
    public function setTipAmount(float $tipAmount): void
    {
        $this->tipAmount = $tipAmount;
    }

    /**
     * @return array|null
     */
    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    /**
     * @param array|null $metadata
     */
    public function setMetadata(?array $metadata): void
    {
        $this->metadata = $metadata;
    }

    /**
     * @return string|null
     */
    public function getOrderReference(): ?string
    {
        return $this->orderReference;
    }

    /**
     * @param string|null $orderReference
     */
    public function setOrderReference(?string $orderReference): void
    {
        $this->orderReference = $orderReference;
    }

    /**
     * @return float|null
     */
    public function getSurchargeAmount(): ?float
    {
        return $this->surchargeAmount;
    }

    /**
     * @param float|null $surchargeAmount
     */
    public function setSurchargeAmount(?float $surchargeAmount): void
    {
        $this->surchargeAmount = $surchargeAmount;
    }

    /**
     * @return string|null
     */
    public function getAccountNumber(): ?string
    {
        return $this->accountNumber;
    }

    /**
     * @param string|null $accountNumber
     */
    public function setAccountNumber(?string $accountNumber): void
    {
        $this->accountNumber = $accountNumber;
    }

    /**
     * @return int|null
     */
    public function getPaymentMethod(): ?int
    {
        return $this->paymentMethod;
    }

    /**
     * @param int|null $paymentMethod
     */
    public function setPaymentMethod(?int $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }
}
