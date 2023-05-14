<?php

namespace App\Dto;

use DateTime;

class TransactionDto implements DtoInterface
{
    public const PAYMENT_METHOD = [
        'CARD'   => 1,
        'PAYPAL' => 2,
        'VENMO' => 3,
        'CASHAPP' => 4,
    ];

    public const SCA_TYPE = [
        'SCA_TYPE_INHERIT_FROM_MERCHANT' => 0,
        'SCA_TYPE_REGULAR'               => 1,
        'SCA_TYPE_PIN_PROTECTED'         => 2,
        'SCA_TYPE_3DS_PROTECTED'         => 3
    ];

    /**
     * @var int|null
     */
    protected $id;

    /**
     * @var string|null
     */
    protected $trnKey;

    /**
     * @var string|null
     */
    protected $merchantIdent;

    /**
     * @var DateTime|null
     */
    protected $createDate;

    /**
     * @var string|null
     */
    protected $userToken;

    /**
     * @var string|null
     */
    protected $amount;

    /**
     * @var string|null
     */
    protected $currency;

    /**
     * @var string|null
     */
    protected $tipAmount;

    /**
     * @var int|null
     */
    protected $paymentMethod;

    /**
     * @var int|null
     *
     * 1 - deposit
     * 2 - withdraw
     * 3 - send
     * 4 - request
     * 5 - payment
     * 6 - gps
     * 7 - bank deposit
     * 8 - receive
     * 9 - transfer
     * 11 - atm
     * 10 - pos
     * 12 - auth
     * 13 - order card
     * 14 - revert
     * 15 - fee
     * 16 - refund
     * 17 - reversal
     * 18 - nuapay
     * 19 - nuapay_refund
     * 20 - pockyt
     * 21 - pockyt_refund
     * 22 - pockyt_void
     */
    protected $transactionType;

    /**
     * @var int|null
     *
     * -2 - cancelled
     * -1 - failed
     * 0 - pending
     * 1 - successful.
     */
    protected $status;

    /**
     * @var string|null
     */
    protected $terminalId;

    /**
     * @var string|null
     */
    protected $deviceId;

    /**
     * @var string|null
     */
    protected $cardToken;

    /**
     * @var string|null
     */
    protected $cardPanObfuscated;

    /**
     * @var string|null
     */
    protected $cardType;

    /**
     * @var string|null
     */
    protected $cctiId;

    /**
     * @var string|null
     */
    protected $posSystemTraceAuditNumber;

    /**
     * @var string|null
     */
    protected $posAuthCode;

    /**
     * @var string|null
     */
    protected $posApplicationCryptogram;

    /**
     * @var string|null
     */
    protected $posApplicationId;

    /**
     * @var string|null
     */
    protected $posTransactionStamp;

    /**
     * @var string|null
     */
    protected $posAcquiringInstitutionCode;

    /**
     * @var string|null
     */
    protected $posCardAcceptorIdentCode;

    /**
     * @var string|null
     */
    protected $posCardAcceptorName;

    /**
     * @var string|null
     */
    protected $posCardAcceptorCity;

    /**
     * @var string|null
     */
    protected $posCardAcceptorCountry;

    /**
     * @var string|null
     */
    protected $posSequenceNumber;

    /**
     * @var string|null
     */
    protected $posGenerationNumber;

    /**
     * @var string|null
     */
    protected $posRawRequest;

    /**
     * @var string|null
     */
    protected $posRawResponse;

    /**
     * @var string|null
     */
    protected $posCodeCondition;

    /**
     * @var DateTime|null
     */
    protected $posLocalDateTime;

    /**
     * @var string|null
     */
    protected $errorCode;

    /**
     * @var string|null
     */
    protected $product;

    /**
     * @var string|null
     */
    protected $channel;

    /**
     * @var string|null
     */
    protected $refundableAmount;

    /**
     * @var bool|null
     */
    protected $voidable;

    /**
     * @var int|null
     */
    protected $linkedTransaction;

    /**
     * @var string|null
     */
    protected $longitude;

    /**
     * @var string|null
     */
    protected $latitude;

    /**
     * @var string|null
     */
    protected $timezoneName;

    protected ?int $nfcTime = 0;

    /**
     * @var array|null
     */
    protected ?array $metadata = null;

    /**
     * @var string|null
     */
    protected ?string $retrievalReferenceNumber = null;

    protected ?int $scaType = null;
	

    protected ?string $receivingInstitutionIdentificationCode = null;

    /**
     * @var string|null
     */
    protected $mcc;

    /**
     * @var DateTime|null
     */
    protected $executionDate;

    protected ?array $acquirerSpecificData = null;

    /**
     * @var string|null
     */
    protected $affiliate = null;

    /**
     * @var string|null
     */
    protected ?string $terminalToken = null;

    /** @var string|null */
    protected ?string $storeToken = null;

    /** @var string|null */
    protected ?string $instance = null;

    protected ?string $orderReference = null;

    protected ?array $appDetails = null;

    /** @var string|null */
    private $surchargeAmount = null;

    protected ?string $remoteServiceTransaction = null;

    public function __construct()
    {
        $this->createDate = new DateTime();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string|null
     */
    public function getTrnKey(): ?string
    {
        return $this->trnKey;
    }

    /**
     * @param string|null $trnKey
     */
    public function setTrnKey(?string $trnKey): void
    {
        $this->trnKey = $trnKey;
    }

    /**
     * @return string|null
     */
    public function getMerchantIdent(): ?string
    {
        return $this->merchantIdent;
    }

    /**
     * @param string|null $merchantIdent
     */
    public function setMerchantIdent(?string $merchantIdent): void
    {
        $this->merchantIdent = $merchantIdent;
    }

    /**
     * @return DateTime|null
     */
    public function getCreateDate(): ?DateTime
    {
        return $this->createDate;
    }

    /**
     * @param DateTime|null $createDate
     */
    public function setCreateDate(?DateTime $createDate): void
    {
        $this->createDate = $createDate;
    }

    /**
     * @return string|null
     */
    public function getUserToken(): ?string
    {
        return $this->userToken;
    }

    /**
     * @param string|null $userToken
     */
    public function setUserToken(?string $userToken): void
    {
        $this->userToken = $userToken;
    }

    /**
     * @return string|null
     */
    public function getAmount(): ?string
    {
        return $this->amount;
    }

    /**
     * @param string|null $amount
     */
    public function setAmount(?string $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param string|null $currency
     */
    public function setCurrency(?string $currency): void
    {
        $this->currency = $currency;
    }

    /**
     * @return string|null
     */
    public function getTipAmount(): ?string
    {
        return $this->tipAmount;
    }

    /**
     * @param string|null $tipAmount
     */
    public function setTipAmount(?string $tipAmount): void
    {
        $this->tipAmount = $tipAmount;
    }

    /**
     * @return int|null
     */
    public function getTransactionType(): ?int
    {
        return $this->transactionType;
    }

    /**
     * @param int|null $transactionType
     */
    public function setTransactionType(?int $transactionType): void
    {
        $this->transactionType = $transactionType;
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @param int|null $status
     */
    public function setStatus(?int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string|null
     */
    public function getTerminalId(): ?string
    {
        return $this->terminalId;
    }

    /**
     * @param string|null $terminalId
     */
    public function setTerminalId(?string $terminalId): void
    {
        $this->terminalId = $terminalId;
    }

    /**
     * @return string|null
     */
    public function getDeviceId(): ?string
    {
        return $this->deviceId;
    }

    /**
     * @param string|null $deviceId
     */
    public function setDeviceId(?string $deviceId): void
    {
        $this->deviceId = $deviceId;
    }

    /**
     * @return string|null
     */
    public function getCardToken(): ?string
    {
        return $this->cardToken;
    }

    /**
     * @param string|null $cardToken
     */
    public function setCardToken(?string $cardToken): void
    {
        $this->cardToken = $cardToken;
    }

    /**
     * @return string|null
     */
    public function getCardPanObfuscated(): ?string
    {
        return $this->cardPanObfuscated;
    }

    /**
     * @param string|null $cardPanObfuscated
     */
    public function setCardPanObfuscated(?string $cardPanObfuscated): void
    {
        $this->cardPanObfuscated = $cardPanObfuscated;
    }

    /**
     * @return string|null
     */
    public function getCardType(): ?string
    {
        return $this->cardType;
    }

    /**
     * @param string|null $cardType
     */
    public function setCardType(?string $cardType): void
    {
        $this->cardType = $cardType;
    }

    /**
     * @return string|null
     */
    public function getCctiId(): ?string
    {
        return $this->cctiId;
    }

    /**
     * @param string|null $cctiId
     */
    public function setCctiId(?string $cctiId): void
    {
        $this->cctiId = $cctiId;
    }

    /**
     * @return string|null
     */
    public function getPosSystemTraceAuditNumber(): ?string
    {
        return $this->posSystemTraceAuditNumber;
    }

    /**
     * @param string|null $posSystemTraceAuditNumber
     */
    public function setPosSystemTraceAuditNumber(?string $posSystemTraceAuditNumber): void
    {
        $this->posSystemTraceAuditNumber = $posSystemTraceAuditNumber;
    }

    /**
     * @return string|null
     */
    public function getPosAuthCode(): ?string
    {
        return $this->posAuthCode;
    }

    /**
     * @param string|null $posAuthCode
     */
    public function setPosAuthCode(?string $posAuthCode): void
    {
        $this->posAuthCode = $posAuthCode;
    }

    /**
     * @return string|null
     */
    public function getPosApplicationCryptogram(): ?string
    {
        return $this->posApplicationCryptogram;
    }

    /**
     * @param string|null $posApplicationCryptogram
     */
    public function setPosApplicationCryptogram(?string $posApplicationCryptogram): void
    {
        $this->posApplicationCryptogram = $posApplicationCryptogram;
    }

    /**
     * @return string|null
     */
    public function getPosApplicationId(): ?string
    {
        return $this->posApplicationId;
    }



    /**
     * @param string|null $posApplicationId
     */
    public function setPosApplicationId(?string $posApplicationId): void
    {
        $this->posApplicationId = $posApplicationId;
    }

    /**
     * @return string|null
     */
    public function getPosTransactionStamp(): ?string
    {
        return $this->posTransactionStamp;
    }

    /**
     * @param string|null $posTransactionStamp
     */
    public function setPosTransactionStamp(?string $posTransactionStamp): void
    {
        $this->posTransactionStamp = $posTransactionStamp;
    }

    /**
     * @return string|null
     */
    public function getPosAcquiringInstitutionCode(): ?string
    {
        return $this->posAcquiringInstitutionCode;
    }

    /**
     * @param string|null $posAcquiringInstitutionCode
     */
    public function setPosAcquiringInstitutionCode(?string $posAcquiringInstitutionCode): void
    {
        $this->posAcquiringInstitutionCode = $posAcquiringInstitutionCode;
    }

    /**
     * @return string|null
     */
    public function getPosCardAcceptorIdentCode(): ?string
    {
        return $this->posCardAcceptorIdentCode;
    }

    /**
     * @param string|null $posCardAcceptorIdentCode
     */
    public function setPosCardAcceptorIdentCode(?string $posCardAcceptorIdentCode): void
    {
        $this->posCardAcceptorIdentCode = $posCardAcceptorIdentCode;
    }

    /**
     * @return string|null
     */
    public function getPosCardAcceptorName(): ?string
    {
        return $this->posCardAcceptorName;
    }

    /**
     * @param string|null $posCardAcceptorName
     */
    public function setPosCardAcceptorName(?string $posCardAcceptorName): void
    {
        $this->posCardAcceptorName = $posCardAcceptorName;
    }

    /**
     * @return string|null
     */
    public function getPosCardAcceptorCity(): ?string
    {
        return $this->posCardAcceptorCity;
    }

    /**
     * @param string|null $posCardAcceptorCity
     */
    public function setPosCardAcceptorCity(?string $posCardAcceptorCity): void
    {
        $this->posCardAcceptorCity = $posCardAcceptorCity;
    }

    /**
     * @return string|null
     */
    public function getPosCardAcceptorCountry(): ?string
    {
        return $this->posCardAcceptorCountry;
    }

    /**
     * @param string|null $posCardAcceptorCountry
     */
    public function setPosCardAcceptorCountry(?string $posCardAcceptorCountry): void
    {
        $this->posCardAcceptorCountry = $posCardAcceptorCountry;
    }

    /**
     * @return string|null
     */
    public function getPosSequenceNumber(): ?string
    {
        return $this->posSequenceNumber;
    }

    /**
     * @param string|null $posSequenceNumber
     */
    public function setPosSequenceNumber(?string $posSequenceNumber): void
    {
        $this->posSequenceNumber = $posSequenceNumber;
    }

    /**
     * @return string|null
     */
    public function getPosGenerationNumber(): ?string
    {
        return $this->posGenerationNumber;
    }

    /**
     * @param string|null $posGenerationNumber
     */
    public function setPosGenerationNumber(?string $posGenerationNumber): void
    {
        $this->posGenerationNumber = $posGenerationNumber;
    }

    /**
     * @return string|null
     */
    public function getPosRawRequest(): ?string
    {
        return $this->posRawRequest;
    }

    /**
     * @param string|null $posRawRequest
     */
    public function setPosRawRequest(?string $posRawRequest): void
    {
        $this->posRawRequest = $posRawRequest;
    }

    /**
     * @return string|null
     */
    public function getPosRawResponse(): ?string
    {
        return $this->posRawResponse;
    }

    /**
     * @param string|null $posRawResponse
     */
    public function setPosRawResponse(?string $posRawResponse): void
    {
        $this->posRawResponse = $posRawResponse;
    }

    /**
     * @return string|null
     */
    public function getPosCodeCondition(): ?string
    {
        return $this->posCodeCondition;
    }

    /**
     * @param string|null $posCodeCondition
     */
    public function setPosCodeCondition(?string $posCodeCondition): void
    {
        $this->posCodeCondition = $posCodeCondition;
    }

    /**
     * @return DateTime|null
     */
    public function getPosLocalDateTime(): ?DateTime
    {
        return $this->posLocalDateTime;
    }

    /**
     * @param DateTime|null $posLocalDateTime
     */
    public function setPosLocalDateTime(?DateTime $posLocalDateTime): void
    {
        $this->posLocalDateTime = $posLocalDateTime;
    }

    /**
     * @return string|null
     */
    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    /**
     * @param string|null $errorCode
     */
    public function setErrorCode(?string $errorCode): void
    {
        $this->errorCode = $errorCode;
    }

    /**
     * @return string|null
     */
    public function getProduct(): ?string
    {
        return $this->product;
    }

    /**
     * @param string|null $product
     */
    public function setProduct(?string $product): void
    {
        $this->product = $product;
    }

    /**
     * @return string|null
     */
    public function getChannel(): ?string
    {
        return $this->channel;
    }

    /**
     * @param string|null $channel
     */
    public function setChannel(?string $channel): void
    {
        $this->channel = $channel;
    }

    /**
     * @return string|null
     */
    public function getRefundableAmount(): ?string
    {
        return $this->refundableAmount;
    }

    /**
     * @param float|null $refundableAmount
     */
    public function setRefundableAmount(?string $refundableAmount): void
    {
        $this->refundableAmount = $refundableAmount;
    }

    /**
     * @return bool|null
     */
    public function getVoidable(): ?bool
    {
        return $this->voidable;
    }

    /**
     * @param bool|null $voidable
     */
    public function setVoidable(?bool $voidable): void
    {
        $this->voidable = $voidable;
    }

    public function getLinkedTransaction(): ?int
    {
        return $this->linkedTransaction;
    }

    public function setLinkedTransaction(?int $linkedTransaction): void
    {
        $this->linkedTransaction = $linkedTransaction;
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

    /**
     * @return string|null
     */
    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    /**
     * @param string|null $longitude
     */
    public function setLongitude(?string $longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @return string|null
     */
    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    /**
     * @param string|null $latitude
     */
    public function setLatitude(?string $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getTimezoneName(): ?string
    {
        return $this->timezoneName;
    }

    public function setTimezoneName(?string $name): void
    {
        $this->timezoneName = $name;
    }

    public function getNfcTime(): ?int
    {
        return $this->nfcTime;
    }

    public function setNfcTime(?int $nfcTime): void
    {
        $this->nfcTime = $nfcTime;
    }

    public function getMetadata(): ?array
    {
        return $this->metadata;
    }

    public function setMetadata(?array $metadata): void
    {
        $this->metadata = $metadata;
    }

    public function getRetrievalReferenceNumber():  ?string
    {
        return $this->retrievalReferenceNumber;
    }

    public function setRetrievalReferenceNumber(?string $retrievalReferenceNumber): void
    {
        $this->retrievalReferenceNumber = $retrievalReferenceNumber;
    }

    public function getScaType(): ?int
    {
        return $this->scaType;
    }

    public function setScaType(?int $scaType): void
    {
        $this->scaType = $scaType;
    }

    /**
     * @return string|null
     */
    public function getMcc(): ?string
    {
        return $this->mcc;
    }

    /**
     * @param string|null $mcc
     */
    public function setMcc(?string $mcc): void
    {
        $this->mcc = $mcc;
    }

    /**
     * @return string|null
     */
    public function getReceivingInstitutionIdentificationCode(): ?string
    {
        return $this->receivingInstitutionIdentificationCode;
    }

    /**
     * @param string|null $receivingInstitutionIdentificationCode
     */
    public function setReceivingInstitutionIdentificationCode(?string $receivingInstitutionIdentificationCode): void
    {
        $this->receivingInstitutionIdentificationCode = $receivingInstitutionIdentificationCode;
    }

    /**
     * @return DateTime|null
     */
    public function getExecutionDate(): ?DateTime
    {
        return $this->executionDate;
    }

    /**
     * @param DateTime|null $executionDate
     */
    public function setExecutionDate(?DateTime $executionDate): void
    {
        $this->executionDate = $executionDate;
    }

    /**
     * @return array|null
     */
    public function getAcquirerSpecificData(): ?array
    {
        return $this->acquirerSpecificData;
    }

    /**
     * @param array|null $acquirerSpecificData
     */
    public function setAcquirerSpecificData(?array $acquirerSpecificData): void
    {
        $this->acquirerSpecificData = $acquirerSpecificData;
    }

    /**
     * @return string|null
     */
    public function getAffiliate(): ?string
    {
        return $this->affiliate;
    }

    /**
     * @param string|null $affiliate
     */
    public function setAffiliate(?string $affiliate): void
    {
        $this->affiliate = $affiliate;
    }

    /**
     * @return string|null
     */
    public function getTerminalToken(): ?string
    {
        return $this->terminalToken;
    }

    /**
     * @param string|null $terminalToken
     */
    public function setTerminalToken(?string $terminalToken): void
    {
        $this->terminalToken = $terminalToken;
    }

    /**
     * @return string|null
     */
    public function getStoreToken(): ?string
    {
        return $this->storeToken;
    }

    /**
     * @param string|null $storeToken
     */
    public function setStoreToken(?string $storeToken): void
    {
        $this->storeToken = $storeToken;
    }

    /**
     * @return string|null
     */
    public function getInstance(): ?string
    {
        return $this->instance;
    }

    /**
     * @param string|null $instance
     */
    public function setInstance(?string $instance): void
    {
        $this->instance = $instance;
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
     * @return array|null
     */
    public function getAppDetails(): ?array
    {
        return $this->appDetails;
    }

    /**
     * @param array|null $appDetails
     */
    public function setAppDetails(?array $appDetails): void
    {
        $this->appDetails = $appDetails;
    }

    /**
     * @return string|null
     */
    public function getSurchargeAmount(): ?string
    {
        return $this->surchargeAmount;
    }

    /**
     * @param string|null $surchargeAmount
     */
    public function setSurchargeAmount(?string $surchargeAmount): void
    {
        $this->surchargeAmount = $surchargeAmount;
    }

    /**
     * @return string|null
     */
    public function getRemoteServiceTransaction(): ?string
    {
        return $this->remoteServiceTransaction;
    }

    /**
     * @param string|null $remoteServiceTransaction
     */
    public function setRemoteServiceTransaction(?string $remoteServiceTransaction): void
    {
        $this->remoteServiceTransaction = $remoteServiceTransaction;
    }
}
