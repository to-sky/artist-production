<?php

namespace App\Mail\DynamicMails;


use App\Documents\Invoices\OrderInvoiceDocument;
use App\Mail\Traits\TicketsListTrait;
use App\Models\Order;
use App\Models\User;

class PaymentMail extends AbstractDynamicMail
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
        return [
            '@ClientName' => $this->_user->first_name,
            '@SiteUrl' => url('/'),
            '@OrderId' => $this->_order->id,
            '@Currency' => Order::CURRENCY,
            '@Amount' => $this->_order->total,
            '@TicketsList' => $this->_getTicketsListPlaceholder($this->_order),
        ];
    }

    protected function _attachmentsList()
    {
        return [
            'OrderInvoice' => function($mail) {
                $invoice = new OrderInvoiceDocument($mail->getOrder());

                return $invoice->attachment('final');
            },
        ];
    }

    /**
     * @return array
     */
    public function getSubject()
    {
        return __('Payment success');
    }

    public function getTo($isCopy)
    {
        if ($isCopy) {
            return [
                setting('mail_order_payment_copy_email'),
            ];
        }

        return parent::getTo($isCopy);
    }

    /**
     * @return string
     */
    public function getTemplateTag()
    {
        return 'order_payment';
    }

    /**
     * @return bool
     */
    public function shouldSendCopy()
    {
        return !!setting('mail_order_payment_copy_email');
    }

    public function getOrder()
    {
        return $this->_order;
    }
}