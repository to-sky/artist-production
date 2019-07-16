<?php

namespace App\Modules\Admin\Controllers;

use App\Models\Ticket;
use App\Services\TicketService;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;

class TicketController extends AdminController
{
    /**
     * Print ticket on standard A4 printer
     *
     * @param Ticket $ticket
     * @return mixed
     */
    public function print(Ticket $ticket)
    {
        $filename = "ETicket_$ticket->id";
        $pdf = PDF::loadView('Admin::pdf.ticket', compact('ticket'));

        return $pdf->stream($filename.'.pdf', 'UTF-8');
    }

    /**
     * Print ticket on Zebra printer
     *
     * @param Ticket $ticket
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function zebraPrint(Ticket $ticket)
    {
        return view('Admin::pdf.zebra_ticket', compact('ticket'));
    }
}
