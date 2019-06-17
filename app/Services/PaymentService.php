<?php


namespace App\Services;


use App\Models\Address;
use App\Models\Country;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Shipping;
use App\Models\ShippingAddress;
use App\Models\BillingAddress;
use App\Models\ShippingZone;
use App\Models\User;
use App\PaymentMethods\AbstractPaymentProcessor;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentService
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    /**
     * Compile order
     *
     * @param Request $request
     * @param User $user
     * @param Address $address
     * @param Address|null $additionalAddress
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function checkout(Request $request, User $user, Address $address, Address $additionalAddress = null)
    {
        $shippingZone = ShippingZone::find($request->get('shipping_zone_id'));
        $paymentMethod = PaymentMethod::find($request->get('payment_method_id'));

        $data = [
            'status' => Order::STATUS_PENDING,
            'tax' => Cart::tax(),
            'subtotal' => Cart::subtotal(),
            'user_id' => $user->id,
            'shipping_price' => $shippingZone->price ?? 0,
            'shipping_status' => Shipping::STATUS_IN_PROCESSING,
            'shipping_zone_id' => $shippingZone->id ?? null,
            'payment_method_id' => $paymentMethod->id ?? null,
        ];

        $order = Order::create(array_merge($data, $request->all()));

        $this->castAddress($order, $additionalAddress ?: $address, ShippingAddress::class);
        $this->castAddress($order, $address, BillingAddress::class);

        $this->ticketService->attachCartToOrder($order);

        $paymentMethod = $this->getPaymentMethod($paymentMethod->id ?? 0);
        if ($paymentMethod) {
            return $paymentMethod->process($order);
        }

        return redirect('payment.error');
    }

    /**
     * Confirm payment
     *
     * @param Order $order
     * @param Request $request
     * @return mixed
     */
    public function confirm(Order $order, Request $request)
    {
        $processor = $this->getPaymentMethod($order->payment_method_id);

        return $processor->confirm($order, $request);
    }

    /**
     * Get payment method processor object
     *
     * @param $id
     * @return bool|AbstractPaymentProcessor
     */
    public function getPaymentMethod($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);


        @$paymentMethodClass = '\\App\\PaymentMethods\\' . $paymentMethod->name . '\\' . $paymentMethod->name . 'PaymentProcessor';
        if (class_exists($paymentMethodClass)) {
            return new $paymentMethodClass;
        }

        return false;
    }

    /**
     * Cast $address model data into $class and attach to $order
     *
     * @param Order $order
     * @param Address $address
     * @param string $class
     * @return Address|null
     */
    protected function castAddress(Order $order, Address $address, $class)
    {
        $data = $address->only([
            'first_name',
            'last_name',
            'street',
            'house',
            'apartment',
            'post_code',
            'city',
        ]);

        $data['country'] = $address->country->name;
        $data['order_id'] = $order->id;

        try {
            $address = $class::create($data);
        } catch (\Exception $e) {
            return null;
        }

        return $address;
    }
}