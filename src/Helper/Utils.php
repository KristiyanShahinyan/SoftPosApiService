<?php

namespace App\Helper;

use App\Dto\CurrencyDto;
use App\Exception\ExceptionCodes;
use Exception;
use Phos\Exception\ApiException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Intl\Currencies;

/**
 * Class Utils
 * @package App\Helper
 */
abstract class Utils
{
    public const HEADER_DEVICE_ID   = 'x-device-id';
    public const HEADER_APP_TYPE    = 'x-app-type';
    public const HEADER_APP_VERSION = 'x-app-version';
    public const HEADER_SDK_VERSION = 'x-sdk-version';
    public const HEADER_PACKAGE_NAME = 'x-package-name';

    /**
     * @param $string
     * @param bool $url
     * @return string
     */
    public static function base64Encode($string, bool $url = false): string
    {
        $result = base64_encode($string);

        if ($url) {
            $result = rtrim(strtr($result, '+/', '-_'), '=');
        }

        return $result;
    }

    /**
     * @param $obj
     * @return string
     * @throws Exception
     */
    public static function jsonEncode($obj): string
    {
        return json_encode($obj, JSON_THROW_ON_ERROR);
    }

    /**
     * @param $string
     * @return array
     * @throws Exception
     */
    public static function jsonDecode($string): array
    {
        return json_decode($string, true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @param Request $request
     * @return string|null
     * @throws ApiException
     */
    public static function parseDeviceId(Request $request): ?string
    {
        $deviceId = $request->headers->get(static::HEADER_DEVICE_ID);

        if ($deviceId === null) {
            throw new ApiException(ExceptionCodes::INVALID_DEVICE_ID);
        }

        if (!preg_match('/^[a-f0-9]{16}$/', $deviceId)) {
            throw new ApiException(ExceptionCodes::INVALID_DEVICE_ID);
        }

        return $deviceId;
    }

    /**
     * @param Request $request
     * @return string|null
     * @throws ApiException
     */
    public static function parseAppType(Request $request): ?string
    {
        $appType = $request->headers->get(static::HEADER_APP_TYPE);

        if ($appType === null) {
            $appType = 'phos'; // backwards compatibility
        }

        if (!in_array($appType, ['phos', 'pinpad'], true)) {
            throw new ApiException(ExceptionCodes::INVALID_APP_TYPE);
        }

        return $appType;
    }

    /**
     * @param Request $request
     * @return int|null
     * @throws ApiException
     */
    public static function parseAppVersion(Request $request): ?int
    {
        $appVersion = $request->headers->get(static::HEADER_APP_VERSION);

        if ($appVersion === null) {
            throw new ApiException(ExceptionCodes::INVALID_PROTOCOL_ID);
        }

        return (int)$appVersion;
    }

    public static function parseSdkVersion(Request $request): ?int
    {
        return (int)$request->headers->get(static::HEADER_SDK_VERSION);
    }

    public static function parsePackageName(Request $request): ?string
    {
        return $request->headers->get(static::HEADER_PACKAGE_NAME);
    }

    /**
     * @throws ApiException
     */
    public static function parseAppDetails(Request $request): array
    {
        return [
            'appVersion' => static::parseAppVersion($request),
            'sdkVersion' => static::parseSdkVersion($request),
            'packageName' => static::parsePackageName($request),
        ];
    }

    /**
     * @param string $currencyCode
     * @return CurrencyDto
     */
    public static function getCurrencyInfo(string $currencyCode): CurrencyDto
    {
        $currencyDto = new CurrencyDto();
        $currencyDto->setName(Currencies::getName($currencyCode));
        $currencyDto->setCode($currencyCode);
        $currencyDto->setSymbol(Currencies::getSymbol($currencyCode));
        $currencyDto->setFractionDigits(Currencies::getFractionDigits($currencyCode));
        $currencyDto->setRoundingIncrement(Currencies::getRoundingIncrement($currencyCode));

        return $currencyDto;
    }
}
