<?php

namespace App\RequestManager\Notification;

use App\Dto\Request\NotificationServiceReceiptSendDto;
use App\RequestManager\AbstractRequestManager;
use GuzzleHttp\Exception\GuzzleException;
use Phos\Exception\ApiException;
use Symfony\Component\HttpFoundation\RedirectResponse;

class NotificationRequestManager extends AbstractRequestManager
{
    protected const SERVICE = 'NOTIFICATION_SERVICE';

    /**
     * @param NotificationServiceReceiptSendDto $dto
     *
     * @return bool|RedirectResponse|array
     *
     * @throws GuzzleException
     * @throws ApiException
     */
    public function sendReceipt(NotificationServiceReceiptSendDto $dto)
    {
        return $this->post('/phos/receipt/send', $dto);
    }

    /**
     * @param mixed $data
     * @return array|mixed
     *
     *
     * @throws ApiException
     * @throws GuzzleException
     */
    public function sendPasswordResetEmail($data)
    {
        return $this->post('/forgotten-password', $data);
    }
}
