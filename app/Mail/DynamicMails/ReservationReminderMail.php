<?php

namespace App\Mail\DynamicMails;


use App\Documents\Invoices\OrderInvoiceDocument;
use App\Mail\Traits\TicketsListTrait;
use App\Models\Order;
use App\Models\User;

class ReservationReminderMail extends AbstractDynamicMail
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
        // @SiteUrl - site link
        // @OrderId - order number
        // @Currency - order currency
        // @Amount - order total
        // @TicketsList - list of order tickets
        // @ReserveExpirationDate - order reservation date

        // @WeWillCallYouMessage
        // @TicketOfficesList
        // @BankRequisites
        return [
            '@ClientName' => $this->_user->first_name,
            '@SiteUrl' => url('/'),
            '@OrderId' => $this->_order->id,
            '@Currency' => Order::CURRENCY,
            '@Amount' => $this->_order->total,
            '@TicketsList' => $this->_getTicketsListPlaceholder($this->_order),
            '@ReserveExpirationDate' => $this->_order->getReservationDate()->format('d.m.Y H:i'),
        ];
    }

    protected function _attachmentsList()
    {
        return [
            'OrderInvoice' => function($mail) {
                $invoice = new OrderInvoiceDocument($mail->getOrder());

                return $invoice->attachment('provisional');
            },
        ];
    }

    /**
     * @return array
     */
    public function getSubject()
    {
        return __('Reservation reminder');
    }

    /**
     * @return string
     */
    public function getTemplateTag()
    {
        return 'clearance_reserve_reminder';
    }

    public function getOrder()
    {
        return $this->_order;
    }
}