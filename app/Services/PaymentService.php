<?php


namespace App\Services;


use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Shipping;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentService
{
    public function checkout(Request $request)
    {
        $data = [
            'status' => Order::STATUS_PENDING,
            'tax' => 0,
            'final_price' => Cart::total(),
            'payment_type' => 'paypal',
            'user_id' => Auth::id(),
            'shipping_id' => 1,
            'shipping_status' => Shipping::STATUS_IN_PROCESSING,
            'payment_method' => 1
        ];
        $request->merge($data);

        $data = $request->all();

        $order = Order::create($data);

        foreach (Cart::content() as $ticket) {
            $ticket->model->order_id = $order->id;
            $ticket->model->update();
        }

        $paymentMethod = $this->getPaymentMethod($request->get('payment_method'));
        

        if ($paymentMethod) {
            $paymentMethod->process($order);
        }
    }

    public function getPaymentMethod($id)
    {
        $paymentMethod = PaymentMethod::findOrFail($id);

        @$paymentMethodClass = '\\App\\PaymentMethods\\' . $paymentMethod->name . '\\Payment';
        if (class_exists($paymentMethodClass)) {
            return new $paymentMethodClass;
        }

        return false;
    }
}