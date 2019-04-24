<?php


namespace App\Http\Controllers;


use App\Models\Order;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;

class PaymentController
{
    protected  $paymentService;

    function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function checkout(Request $request)
    {
        return view('payment.checkout');
    }

    public function processCheckout(Request $request)
    {
        $this->paymentService->process($request);
    }

    public function confirm(Order $order, Request $request)
    {
        $this->paymentService->confrim($order, $request);
    }

    public function success(Order $order, Request $request)
    {
        return view('payment.success', compact('order'));
    }

    public function cancel(Order $order, Request $request)
    {
        return view('payment.cancel', compact('order'));
    }
}