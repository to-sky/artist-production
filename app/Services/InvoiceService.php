<?php

namespace App\Services;


use App\Documents\Invoices\OrderInvoiceDocument;
use App\Models\Invoice;
use App\Models\Order;

class InvoiceService
{

    protected function getStatusTitle(int $status)
    {
        switch ($status) {
            case Order::STATUS_CONFIRMED: return 'Оплата заказа';
            break;
            case Order::STATUS_RESERVE: return 'Резервирование заказа';
            break;
            case Order::STATUS_REALIZATION: return 'Отправка заказа в реализацию';
            break;
        }
    }

    protected function getInvoiceTag(int $status)
    {
        return $status === Order::STATUS_CONFIRMED
                ? 'final'
                : 'provisional';
    }

    // store invoice($order)
    public function store(Order $order, $fill = [], $tag = '')
    {
        $data = [
            'title' => $this->getStatusTitle($order->status),
            'order_id' => $order->id,
        ];

        $invoiceDocument = new OrderInvoiceDocument($order);
        if (empty($tag)) {
            $tag = $this->getInvoiceTag($order->status);
        }

        $file = $invoiceDocument->getFile($tag);

        $data['file_id'] = $file->id;

        if ($fill) $data += $fill;

        $invoice = Invoice::make($data);
        $invoice->save();

        return $invoice;
    }


    // regenerate invoice ($order)
    public function regenerate(Order $order)
    {

    }
}
