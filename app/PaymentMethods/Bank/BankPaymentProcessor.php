<?php

namespace App\PaymentMethods\Bank;

use App\Mail\DynamicMails\PaymentMail;
use App\Mail\DynamicMails\ReservationMail;
use App\PaymentMethods\AbstractPaymentProcessor;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BankPaymentProcessor extends AbstractPaymentProcessor
{
    public function process(Order $order)
    {
        $user = $order->user;

        $this->_ticketService->reserveByOrder($order);
        $this->_mailService->send(new ReservationMail($user, $order));

        return redirect()->route('payment.success', compact('order'));
    }

    public function confirm(Order $order, Request $request)
    {
        $user = $order->user;

        $order->update([
            'status' => Order::STATUS_CONFIRMED,
            'paid_at' => Carbon::now(),
        ]);
        $this->_ticketService->sold($order);

        $this->_mailService->send(new PaymentMail($user, $order));
    }

    public function cancel(Order $order, Request $request)
    {
        $order->update(['status' => Order::STATUS_CANCELED]);
        $this->_ticketService->freeByOrder($order);
    }
}
