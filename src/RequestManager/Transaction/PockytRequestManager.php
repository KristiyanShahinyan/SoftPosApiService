<?php

namespace App\RequestManager\Transaction;

use App\RequestManager\AbstractRequestManager;

class PockytRequestManager extends AbstractRequestManager
{
    protected const SERVICE = 'POCKYT_SERVICE';

    public function createSale($transaction)
    {
        return $this->post('/transaction', $transaction);
    }

    public function createRefund($transaction)
    {
        return $this->post('/transaction/refund', $transaction);
    }

    public function createVoid($transaction)
    {
        return $this->post('/transaction/void', $transaction);
    }
}