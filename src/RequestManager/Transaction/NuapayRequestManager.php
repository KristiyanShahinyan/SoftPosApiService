<?php

namespace App\RequestManager\Transaction;

use App\RequestManager\AbstractRequestManager;

class NuapayRequestManager extends AbstractRequestManager
{
    protected const SERVICE = 'NUAPAY_SERVICE';

    public function createSale($transaction)
    {
        return $this->post('/transaction', $transaction);
    }

    public function createRefund($transaction)
    {
        return $this->post('/transaction/refund', $transaction);
    }
}