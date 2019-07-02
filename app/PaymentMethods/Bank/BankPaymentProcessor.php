<?php

namespace App\PaymentMethods\Bank;

use App\Mail\DynamicMails\ReservationMail;
use App\Models\Ticket;
use App\PaymentMethods\AbstractPaymentProcessor;
use App\Models\Order;
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
        $order->update(['status' => Order::STATUS_CONFIRMED]);
        $this->_ticketService->sold($order);
    }

    public function cancel(Order $order, Request $request)
    {
        $order->update(['status' => Order::STATUS_CANCELED]);
        $this->_ticketService->freeByOrder($order);
    }
}
