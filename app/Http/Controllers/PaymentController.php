<?php


namespace App\Http\Controllers;


use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController
{
    protected  $paymentService;

    function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function checkout(Request $request)
    {
        $redirect = $this->paymentService->process($request);

        return redirect($redirect);
    }

    public function confirm(Request $request)
    {
        $this->paymentService->confrim();
    }
}