<?php

namespace App\Modules\Admin\Controllers;

use App\Models\Ticket;
use App\Services\TicketService;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;

class TicketController extends AdminController
{
    public function print(Ticket $ticket)
    {
        $filename = "ETicket_$ticket->id";
        $pdf = PDF::loadView('Admin::pdf.ticket', compact('ticket'));

        return $pdf->stream($filename.'.pdf', 'UTF-8');
    }

    public function zebraPrint()
    {
        $ticket = Ticket::all()->first();

        return view('Admin::pdf.zebra_ticket', compact('ticket'));
    }

    public function dissociateUser(TicketService $ticketService, Request $request)
    {
        $ticketService->freeAnywhere($request->ticket_id);

        return response()->json(null, 200);
    }
}
