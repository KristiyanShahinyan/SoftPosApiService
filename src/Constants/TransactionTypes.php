<?php

namespace App\Constants;

abstract class TransactionTypes
{
    /**
     * Wallet  on POS terminal
     */
    public const PAYMENT = 5;

    /**
     * Authorisation on POS terminal
     */
    public const AUTH = 12;

    /**
     * Refund for a specific amount (full or partial)
     */
    public const REFUND = 16;

    /**
     * Reversal of a purchase or a refund
     */
    public const VOID = 17;

    /**
     * Purchase using Nupay
     */
    public const NUAPAY_SALE = 18;

    /**
     * Refund for Nupay
     */
    public const NUAPAY_REFUND = 19;

    /**
     * Purchase using Pockyt
     */
    public const POCKYT_SALE = 20;

    /**
     * Refund for Pockyt
     */
    public const POCKYT_REFUND = 21;

    /**
     * Void for Pockyt
     */
    public const POCKYT_VOID = 22;


    public static function getOperationType(int $type): string
    {
        switch ($type) {
            case TransactionTypes::AUTH:
            case TransactionTypes::PAYMENT:
                return 'sale';
            case TransactionTypes::REFUND:
                return 'refund';
            case TransactionTypes::VOID:
                return 'void';
            case TransactionTypes::NUAPAY_SALE:
                return 'nuapay_sale';
            case TransactionTypes::NUAPAY_REFUND:
                return 'nuapay_refund';
            case TransactionTypes::POCKYT_SALE:
                return 'pockyt_sale';
            case TransactionTypes::POCKYT_REFUND:
                return 'pockyt_refund';
            case TransactionTypes::POCKYT_VOID:
                return 'pockyt_void';
            default:
                return '';
        }
    }

    public static function getTrnType(string $operation): int
    {
        switch ($operation) {
            case 'sale':
                return TransactionTypes::AUTH;
            case 'refund':
                return TransactionTypes::REFUND;
            case 'void':
                return TransactionTypes::VOID;
            case 'nuapay_sale':
                return TransactionTypes::NUAPAY_SALE;
            case 'nuapay_refund':
                return TransactionTypes::NUAPAY_REFUND;
            case 'pockyt_sale':
                return TransactionTypes::POCKYT_SALE;
            case 'pockyt_refund':
                return TransactionTypes::POCKYT_REFUND;
            case 'pockyt_void':
                return TransactionTypes::POCKYT_VOID;
            default:
                return 0;
        }
    }
}
