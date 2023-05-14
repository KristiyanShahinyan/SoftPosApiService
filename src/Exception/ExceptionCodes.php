<?php

namespace App\Exception;

abstract class ExceptionCodes
{

    public const INVALID_DEVICE_ID        = 50001;
    public const INVALID_APP_TYPE         = 50002;
    public const INVALID_PROTOCOL_ID      = 50003;
    public const ENCRYPTION_KEY_NOT_FOUND = 50004;
    public const UNABLE_TO_ENCRYPT_DATA   = 50005;
    public const UNABLE_TO_DECRYPT_DATA   = 50006;
    public const UNSUPPORTED_APP_VERSION  = 50007;
    public const MISSING_REQUIRED_HEADER  = 50008;
    public const UNRECOGNIZED_APP_VERSION = 50009;
    public const DEVICE_NOT_FOUND = 50010;

    public const INSUFFICIENT_RIGHTS = 50011;
    public const TERMINAL_NOT_ACTIVE = 50012;
    public const STORE_NOT_ACTIVE = 50013;
    public const TRANSACTION_ALREADY_REFUNDED = 50014;
    public const REPLY_REQUEST = 50015;
    public const UNVOIDABLE_TRANSACTION = 50016;
    public const MONITORING_RULES_VIOLATION = 50017;
    public const SDK_INVALID_ISSUER = 50018;

    public const INSTANCE_NOT_FOUND = 12004;

    public const CODES = [
        self::INVALID_DEVICE_ID => 'services.api.invalid_or_missing_device_id',
        self::INVALID_APP_TYPE => 'services.api.invalid_or_missing_app_type',
        self::INVALID_PROTOCOL_ID => 'services.api.invalid_or_missing_protocol_id',
        self::ENCRYPTION_KEY_NOT_FOUND => 'services.api.encryption_key_not_found',
        self::UNABLE_TO_ENCRYPT_DATA => 'services.api.unable_to_encrypt_data',
        self::UNABLE_TO_DECRYPT_DATA => 'services.api.unable_to_decrypt_data',
        self::UNSUPPORTED_APP_VERSION => 'services.api.unsupported_app_version',
        self::MISSING_REQUIRED_HEADER => 'services.api.missing_required_header',
        self::UNRECOGNIZED_APP_VERSION => 'services.api.unrecognized_app_version',
        self::DEVICE_NOT_FOUND => 'services.api.device_not_found',
        self::INSUFFICIENT_RIGHTS => 'services.api.insufficient_rights',
        self::TERMINAL_NOT_ACTIVE => 'services.api.terminal_not_active',
        self::STORE_NOT_ACTIVE => 'services.api.store_not_active',
        self::TRANSACTION_ALREADY_REFUNDED => 'services.api.transaction_already_refunded',
        self::REPLY_REQUEST => 'services.api.reply_request',
        self::UNVOIDABLE_TRANSACTION => 'services.api.unvoidable_transaction',
        self::MONITORING_RULES_VIOLATION => 'services.api.monitoring_rules_violation',
        self::INSTANCE_NOT_FOUND => 'services.accounts.instance.not_found'
    ];
}
