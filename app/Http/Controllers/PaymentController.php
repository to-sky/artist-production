<?php


namespace App\Http\Controllers;


use App\Models\Address;
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

    /**
     * Order confirmation page
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function checkout(Request $request)
    {
        $shippings = Shipping::all();
        $paymentMethods = PaymentMethod::where('active', PaymentMethod::ACTIVE)->get();

        $countries = Country::pluck('name', 'id')->toArray();
        $addresses = Address::whereUserId(Auth::id())->get();

        return view('payment.checkout', compact(
            'shippings',
            'paymentMethods',
            'countries',
            'addresses'
        ));
    }

    /**
     * Process order confirmation page
     *
     * @param ProcessCheckoutRequest $request
     * @param UserService $userService
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function processCheckout(ProcessCheckoutRequest $request, UserService $userService)
    {
        if (!Auth::check()) {
            $user = $userService->createFromCheckoutRequest($request);

            $address = $userService->addUserAddress(
                $userService->extractAddressFromPaymentRequest($request),
                $user
            );
        } else {
            $user = Auth::user();
            $address = Address::find($request->get('address_id'));
        }

        $additionalAddress = null;
        if ($request->get('other_address_check')) {
            $additionalAddress = $userService->addUserAddress(
                $userService->extractAdditionalAddressFromPaymentRequest($request),
                $user
            );
        }

        $response = $this->paymentService->checkout(
            $request,
            $user,
            $address,
            $additionalAddress
        );

        return $response;
    }

    /**
     * Confirmation of payment
     *
     * @param Order $order
     * @param Request $request
     * @return mixed
     */
    public function confirm(Order $order, Request $request)
    {
        $response = $this->paymentService->confirm($order, $request);

        return $response;
    }

    /**
     * Success page for payment
     *
     * @param Order $order
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function success(Order $order, Request $request)
    {
        return view('payment.success', compact('order'));
    }

    /**
     * Cancel page for payment
     *
     * @param Order $order
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function cancel(Order $order, Request $request)
    {
        $this->paymentService->cancel($order, $request);

        return view('payment.cancel', compact('order'));
    }

    /**
     * Payment error page
     *
     * @param Order $order
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function error(Order $order, Request $request)
    {
        return view('payment.error', compact('order'));
    }

    /**
     * Partial for shipping options radio list
     *
     * @param ShippingService $shippingService
     * @param Country $country
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function shippingOptions(ShippingService $shippingService, Country $country)
    {
        $options = $shippingService->getShippingOptionsForCountry($country);

        return view('payment.partials.shipping_options', compact('options'));
    }
}