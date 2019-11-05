<?php

namespace App\Services;


use App\Documents\Invoices\OrderInvoiceDocument;
use App\Models\BillingAddress;
use App\Models\Invoice;
use App\Models\Order;

class InvoiceService
{
    /**
     * Get invoice title
     *
     * @param int $status
     * @return string
     */
    protected function getInvoiceTitle(int $status)
    {
        switch ($status) {
            case Order::STATUS_CONFIRMED:
                return 'Оплата заказа';
            break;
            case Order::STATUS_PENDING:
            case Order::STATUS_RESERVE:
                return 'Резервирование заказа';
            break;
            case Order::STATUS_REALIZATION:
                return 'Отправка заказа в реализацию';
            break;
        }
    }

    /**
     * Get invoice tag
     *
     * @param int $status
     * @return string
     */
    protected function getInvoiceTag(int $status)
    {
        return $status === Order::STATUS_CONFIRMED
                ? 'final'
                : 'provisional';
    }

    /**
     * Store billing address and append to order
     *
     * @param Order $order
     * @return BillingAddress|null
     */
    public function appendBillingAddressToOrder(Order $order)
    {
        if (is_null($order->user)) {
            return null;
        }

        $userAddress = $order->user->addresses()->active()->first();

        if (is_null($userAddress)) return null;

        $data = $userAddress->only([
            'apartment',
            'city',
            'first_name',
            'last_name',
            'house',
            'post_code',
            'street',
        ]);

        $data += [
            'country' => $userAddress->country->name,
            'order_id' => $order->id,
        ];

        return BillingAddress::create($data);
    }

    /**
     * Store invoice
     *
     * @param Order $order
     * @param string $tag
     * @param array $fill
     * @return mixed
     */
    public function store(Order $order, $tag = '', $fill = [])
    {
        if (! $order->billingAddress()->exists()) {
            $this->appendBillingAddressToOrder($order);
        }

        $data = [
            'title' => $this->getInvoiceTitle($order->status),
            'order_id' => $order->id,
        ];

        $invoiceDocument = new OrderInvoiceDocument($order);

        if (empty($tag)) {
            $tag = $this->getInvoiceTag($order->status);
        }

        $file = $invoiceDocument->makeFile($tag);

        $data['file_id'] = $file->id;

        if ($fill) {
            $data = array_replace($data, $fill);
        }

        return Invoice::create($data);
    }
}
