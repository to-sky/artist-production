<?php


namespace App\Services;


use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentService
{
    public function checkout(Request $request)
    {
        $order = Order::create(
            $request->all()
        );

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