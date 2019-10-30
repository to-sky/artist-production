<?php

namespace App\PaymentMethods\Bank;

use App\Mail\DynamicMails\CourierDeliveryMail;
use App\Mail\DynamicMails\ETicketsListMail;
use App\Mail\DynamicMails\PaymentMail;
use App\Mail\DynamicMails\ReservationMail;
use App\Models\Shipping;
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
        switch ($order->shipping_type) {
            case Shipping::TYPE_EMAIL:
                $this->_mailService->send(new ReservationMail($user, $order));
                break;
            case Shipping::TYPE_POST:
                $this->_mailService->send(new CourierDeliveryMail($user, $order));
                break;
        }

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

        switch ($order->shipping_type) {
            case Shipping::TYPE_POST:
                $this->_mailService->send(new PaymentMail($user, $order));
                break;
            case Shipping::TYPE_EMAIL:
                $this->_mailService->send(new ETicketsListMail($user, $order));
                break;
        }
    }

    public function cancel(Order $order, Request $request)
    {
        $order->update(['status' => Order::STATUS_CANCELED]);
        $this->_ticketService->freeByOrder($order);
    }
}
