<?php

namespace App\PaymentMethods;

use App\Models\Order;
use Illuminate\Http\Request;

abstract class AbstractPaymentProcessor
{
    abstract public function process(Order $order);

    abstract public function confirm(Order $order, Request $request);

    abstract public function cancel(Order $order, Request $request);
}