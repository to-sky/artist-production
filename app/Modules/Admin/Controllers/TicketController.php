<?php

namespace App\Modules\Admin\Controllers;

use App\Models\Ticket;
use Barryvdh\DomPDF\Facade as PDF;

class TicketController extends AdminController
{
    public function print(Ticket $ticket)
    {
        $filename = "ETicket_$ticket->id";
        $pdf = PDF::loadView('Admin::pdf.ticket', compact('ticket'));

        return $pdf->stream($filename.'.pdf', 'UTF-8');
    }
}
