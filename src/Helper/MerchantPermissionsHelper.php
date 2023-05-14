<?php

namespace App\Helper;

use App\Security\Merchants;

class MerchantPermissionsHelper
{
    /**
     * @param array $merchants
     * @param string $merchantToken
     * @return array
     */
    public static function getPermissions(array $merchants, string $merchantToken): array
    {
        foreach ($merchants as $merchant) {
            /** @var Merchants $merchant */
            if ($merchant->getMerchant()->getToken() === $merchantToken) {
                return is_array($merchant->getRoles()) ? $merchant->getRoles() : [];
            }
        }

        return [];
    }
}
