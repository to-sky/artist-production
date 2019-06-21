<?php

namespace App\PaymentMethods\Bank;

use App\PaymentMethods\AbstractPaymentProcessor;
use App\Models\Order;
use Illuminate\Http\Request;

class BankPaymentProcessor extends AbstractPaymentProcessor
{
    public function process(Order $order)
    {
        // todo: finish order process
        dd($order);
    }

    public function confirm(Order $order, Request $request)
    {

    }

}
