<?php

namespace App\Mail\DynamicMails;


use App\Mail\Traits\TicketsListTrait;
use App\Models\Order;
use App\Models\User;

class ETicketsListMail extends AbstractDynamicMail
{
    use TicketsListTrait;

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
        return [
            '@ClientName' => $this->_user->first_name,
            '@SiteUrl' => url('/'),
            '@OrderId' => $this->_order->id,
            '@Currency' => Order::CURRENCY,
            '@Amount' => $this->_order->total,
            '@TicketsList' => $this->_getTicketsListPlaceholder($this->_order),
        ];
    }

    /**
     * @return array
     */
    public function getSubject()
    {
        return __('E-Ticket list');
    }

    /**
     * @return string
     */
    public function getTemplateTag()
    {
        return 'e-ticket_list';
    }

    /**
     * Get mail attachments list
     *
     * @return array
     */
    protected function _attachmentsList()
    {
        return [];
    }
}