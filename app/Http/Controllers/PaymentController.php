<?php


namespace App\Http\Controllers;


use App\Models\Country;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Shipping;
use App\Models\ShippingZone;
use App\Models\Ticket;
use App\Services\PaymentService;
use App\Services\ShippingService;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Http\Requests\ProcessCheckoutRequest;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

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

        $countries = Country::pluck('name', 'id')->toArray();

        return view('payment.checkout', compact(
            'shippings',
            'paymentMethods',
            'countries'
        ));
    }

    public function processCheckout(ProcessCheckoutRequest $request, UserService $userService)
    {
        if (!Auth::check()) {
            $user = $userService->createFromCheckoutRequest($request);
        }

        dd($request->all());

        $this->paymentService->checkout($request);

        return redirect()->route('payment.succes');
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

    public function shippingOptions(ShippingService $shippingService, Country $country)
    {
        $options = $shippingService->getShippingOptionsForCountry($country);

        return view('payment.partials.shipping_options', compact('options'));
    }
}