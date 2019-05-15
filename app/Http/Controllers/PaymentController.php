<?php


namespace App\Http\Controllers;


use App\Models\Country;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Shipping;
use App\Models\ShippingZone;
use App\Models\Ticket;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use App\Http\Requests\ProcessCheckoutRequest;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Redirect;

class PaymentController
{
    protected  $paymentService;

    function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function checkout(Request $request)
    {
        $shippings = Shipping::all();
        $paymentMethods = PaymentMethod::where('active', PaymentMethod::ACTIVE)->get();

        $shippingZone = ShippingZone::first();

        $countries = Country::pluck('name', 'id')->toArray();

        return view('payment.checkout', compact('shippings', 'paymentMethods', 'shippingZone', 'countries'));
    }

    public function processCheckout(ProcessCheckoutRequest $request)
    {
        return $this->paymentService->checkout($request);
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

    public function error(Order $order, Request $request)
    {
        return view('payment.error', compact('order'));
    }
}