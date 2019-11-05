<?php

namespace App\Modules\Admin\Controllers;

use App\Documents\Invoices\OrderInvoiceDocument;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;

class InvoiceController extends AdminController
{
    /**
     * Download invoice pdf
     *
     * @param Order $order
     * @param string $tag
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(Order $order, $tag)
    {
        $invoice = new OrderInvoiceDocument($order);

        return $invoice->download($tag);
    }

    public function print(Order $order, $tag)
    {
        $invoice = new OrderInvoiceDocument($order);

        return $invoice->print($tag);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Invoice::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        $pdf = PDF::loadView('Admin::pdf.invoice', compact('invoice'));

        return $pdf->download('invoice.pdf');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->destroy();
    }
}
