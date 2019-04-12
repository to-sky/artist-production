<?php

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use App\Models\Order;
use Illuminate\Http\Request;

class BankPaymentProcessor extends AbstractPaymentProcessor
{
    public function process(Order $order)
    {

    }

    public function confirm(Order $order, Request $request)
    {

    }

}