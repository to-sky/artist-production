<?php

namespace App\Services;


use App\Documents\Invoices\OrderInvoiceDocument;
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
    public function store(Order $order, $fill = [])
    {
        $data = [
            'title' => $this->getStatusTitle($order->status)
        ];

        if ($fill) $data += $fill;

        $invoice = Invoice::create($data);
        $invoice->order()->associate($order);

        $invoiceDocument = new OrderInvoiceDocument($order);
        $tag = $this->getInvoiceTag($order->status);

        dd($invoiceDocument->getFile($tag));

//        $invoice->file()->associate($file);

        return $invoice;
    }


    // regenerate invoice ($order)
    public function regenerate(Order $order)
    {

    }
}
