<?php

namespace App\Service;

use App\Exception\ExceptionCodes;
use App\Helper\Utils;
use App\RequestManager\Security\SecurityRequestManager;
use Phos\Exception\ApiException;
use Symfony\Component\HttpFoundation\Request;

class DeviceService
{
    private SecurityRequestManager $securityRequestManager;

    public function __construct(SecurityRequestManager $securityRequestManager)
    {
        $this->securityRequestManager = $securityRequestManager;
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws ApiException
     */
    public function getDevice(Request $request)
    {
        $response = $this->securityRequestManager->findDeviceById(Utils::parseDeviceId($request));
        $device = $response['items'][0] ?? null;
        if ($device === null) {
            throw new ApiException(ExceptionCodes::DEVICE_NOT_FOUND, ['Device not found!']);
        }

        return $device;
    }
}