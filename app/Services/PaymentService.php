<?php


namespace App\Services;


use App\Models\Country;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Shipping;
use App\Models\ShippingAddress;
use App\Models\BillingAddress;
use App\Models\ShippingZone;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentService
{
    public function checkout(Request $request)
    {
        $shippingZone = ShippingZone::find($request->get('shipping_zone_id'));

        $data = [
            'status' => Order::STATUS_PENDING,
            'tax' => Cart::tax(),
            'subtotal' => Cart::subtotal(),
            'user_id' => Auth::id(),
            'shipping_price' => $shippingZone->price,
            'courier_price' => $request->get('courier', null) ? setting('checkout_courier_price') : 0,
            'shipping_status' => Shipping::STATUS_IN_PROCESSING,
//            'shipping_zone_id' => 1,
//            'payment_method' => 1
        ];

        $order = Order::create(array_merge($data, $request->all()));
//        $order = Order::where('id', 3)->first();

        $address = $request->get('other_address', null) ? $request->get('shipping_address') : $request->get('billing_address');

        $address['country'] = Country::find($address['country_id'])->name;
        $address['order_id'] = $order->id;

        $shippingAddress = ShippingAddress::create($address);

        $address = $request->get('billing_address');
        $address['country'] = Country::find($address['country_id'])->name;
        $address['order_id'] = $order->id;

        $billingAddress = BillingAddress::create($address);

        var_dump(array_merge($data, $request->all()), $shippingAddress, $billingAddress);
        die;

        foreach (Cart::content() as $ticket) {
            $ticket->model->update(['order_id' => $order->id]);
        }

        Cart::destroy();

        $paymentMethod = $this->getPaymentMethod($request->get('payment_method'));
        

        if ($paymentMethod) {
            return $paymentMethod->process($order);
        }
    }

    public function confirm($order, $request)
    {
        $paymentMethod = $this->getPaymentMethod($order->payment_method_id);

        if ($paymentMethod) {
            $paymentMethod->confirm($order, $request);
        }
    }

    public function getPaymentMethod($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        @$paymentMethodClass = '\\App\\PaymentMethods\\' . $paymentMethod->name . '\\' . $paymentMethod->name . 'PaymentProcessor';
        if (class_exists($paymentMethodClass)) {
            return new $paymentMethodClass;
        }

        return false;
    }
}