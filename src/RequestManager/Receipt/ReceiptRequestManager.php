<?php

namespace App\RequestManager\Receipt;

use App\Dto\Request\ReceiptServiceReceiptGenerateDto;
use App\RequestManager\AbstractRequestManager;
use GuzzleHttp\Exception\GuzzleException;
use Phos\Exception\ApiException;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ReceiptRequestManager extends AbstractRequestManager
{
    protected const SERVICE = 'RECEIPT_SERVICE';

    /**
     * @param ReceiptServiceReceiptGenerateDto $dto
     *
     * @return bool|RedirectResponse|array
     *
     * @throws GuzzleException
     * @throws ApiException
     */
    public function generate(ReceiptServiceReceiptGenerateDto $dto)
    {
        return $this->post('/receipt/generate', $dto);
    }
}
