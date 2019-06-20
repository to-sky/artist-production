<?php

namespace App\Mail\DynamicMails;


use App\Models\Order;
use App\Models\User;

class ReservationMail extends AbstractDynamicMail
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
        // @SiteUrl - site link
        // @OrderId - order number
        // @Currency - order currency
        // @Amount - order total

        // @WeWillCallYouMessage
        // @ReserveExpirationDate - order reservation date
        // @TicketsList - list of order tickets
        // @TicketOfficesList
        // @BankRequisites
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
        return __('Reservation information');
    }

    /**
     * @return string
     */
    public function getTemplateTag()
    {
        return 'clearance_reserve';
    }
}