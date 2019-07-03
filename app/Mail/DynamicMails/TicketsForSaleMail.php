<?php

namespace App\Mail\DynamicMails;


use App\Models\Order;
use App\Models\User;

class TicketsForSaleMail extends AbstractDynamicMail
{
    protected $_order;

    public function __construct(User $user, Order $order)
    {
        parent::__construct($user);

        $this->_order = $order;
    }

    /**
     * Prepare placeholders data
     *
     * @return array
     */
    protected function _prepareData()
    {
        // @ClientName - client name
        // @SiteUrl - link to the website
        // @OrderId - order number
        // @Currency - order currency
        // @Amount - payment amount

        // @TicketsList - list of ordered tickets
        // @TicketOfficesList - cash list
        // @WeWillCallYouMessage - message 'we will call you'
        // @BankRequisites - bank details
        return [
            '@ClientName' => $this->_user->first_name,
            '@SiteUrl' => url('/'),
            '@OrderId' => $this->_order->id,
            '@Currency' => Order::CURRENCY,
            '@Amount' => $this->_order->total,
        ];
    }

    /**
     * @return array
     */
    public function getSubject()
    {
        return __('Tickets for sale');
    }

    /**
     * @return string
     */
    public function getTemplateTag()
    {
        return 'tickets_for_sale';
    }
}