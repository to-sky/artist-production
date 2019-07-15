<?php

namespace App\Documents\Invoices;


use App\Documents\Defaults\AbstractDocument;

abstract class AbstractInvoiceDocument extends AbstractDocument
{
    /** @var \Barryvdh\DomPDF\Facade */
    protected $_pdfMaker;

    public function __construct()
    {
        $this->_pdfMaker = app()->make('dompdf.wrapper');
    }
}