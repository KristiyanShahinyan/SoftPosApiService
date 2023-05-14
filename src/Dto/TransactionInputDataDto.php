<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class TransactionInputDataDto
{
    /** @Assert\NotBlank(groups={"refund", "void", "pockyt_refund", "pockyt_void"}) */
    protected ?string $transactionKey;

    /**
     * @Assert\NotBlank(groups={"sale", "refund", "nuapay_sale", "nuapay_refund", "pockyt_sale", "pockyt_refund"})
     * @Assert\GreaterThanOrEqual(0.001, groups={"sale", "refund", "nuapay_sale", "nuapay_refund", "pockyt_sale", "pockyt_refund"})
     */
    protected float $amount;

    /**
     * @Assert\NotBlank(groups={"sale"})
     * @Assert\AtLeastOneOf({@Assert\EqualTo(0), @Assert\GreaterThanOrEqual(0.001)}, groups={"sale"})
     */
    protected ?float $tipAmount = null;

    /** @Assert\NotBlank(groups={"sale", "refund", "nuapay_sale", "nuapay_refund", "pockyt_sale", "pockyt_refund"}) */
    protected string $currency;

    protected ?string $terminalToken = null;

    protected ?string $latitude = null;

    protected ?string $longitude = null;

    /** @Assert\NotBlank(groups={"sale"}) */
    protected int $nfcTime = 0;

    /** @Assert\Regex("/^[a-zA-Z ]+$/i", groups={"sale", "refund", "nuapay_sale", "nuapay_refund", "pockyt_sale", "pockyt_refund"}) */
    protected string $cardType = "";

    protected ?array $metadata = null;

    protected ?int $scaType = 1;

    protected ?string $orderReference = null;

    /** @Assert\AtLeastOneOf({@Assert\EqualTo(0), @Assert\GreaterThanOrEqual(0.001)}, groups={"sale"}) */
    private ?float $surchargeAmount = null;

    protected ?int $merchantId = null;

    /** @Assert\NotBlank(groups={"pockyt_sale"}) */
    protected ?int $paymentMethod = null;

    public function getTransactionKey(): ?string
    {
        return $this->transactionKey;
    }

    public function setTransactionKey(?string $transactionKey): void
    {
        $this->transactionKey = $transactionKey;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }

    public function getTipAmount()
    {
        return $this->tipAmount;
    }

    public function setTipAmount($tipAmount): void
    {
        $this->tipAmount = $tipAmount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): void
    {
        $this->currency = $currency;
    }

    public function getTerminalToken(): ?string
    {
        return $this->terminalToken;
    }

    public function setTerminalToken(?string $terminalToken): void
    {
        $this->terminalToken = $terminalToken;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): void
    {
        $this->longitude = $longitude;
    }

    public function getNfcTime(): int
    {
        return $this->nfcTime;
    }

    public function setNfcTime(int $nfcTime): void
    {
        $this->nfcTime = $nfcTime;
    }

    public function getCardType(): string
    {
        return $this->cardType;
    }

    public function setCardType(string $cardType): void
    {
        $this->cardType = $cardType;
    }

    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function setMetadata(?array $metadata): void
    {
        $this->metadata = $metadata;
    }

    /**
     * @return int|null
     */
    public function getScaType(): ?int
    {
        return $this->scaType;
    }

    /**
     * @param int|null $scaType
     */
    public function setScaType(?int $scaType): void
    {
        $this->scaType = $scaType;
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
     * @return int|null
     */
    public function getMerchantId(): ?int
    {
        return $this->merchantId;
    }

    /**
     * @param int|null $merchantId
     */
    public function setMerchantId(?int $merchantId): void
    {
        $this->merchantId = $merchantId;
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
