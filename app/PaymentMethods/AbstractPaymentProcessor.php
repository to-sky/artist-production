<?php

namespace App\PaymentMethods;

use App\Models\Order;
use App\Services\MailService;
use App\Services\TicketService;
use Illuminate\Http\Request;

abstract class AbstractPaymentProcessor
{
    protected $_mailService;
    protected $_ticketService;

    public function __construct(MailService $mailService, TicketService $ticketService)
    {
        $this->_mailService = $mailService;
        $this->_ticketService = $ticketService;
    }

    abstract public function process(Order $order);

    abstract public function confirm(Order $order, Request $request);

    abstract public function cancel(Order $order, Request $request);
}
