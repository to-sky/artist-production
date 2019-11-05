<?php

namespace App\Modules\Admin\Controllers;

use App\Models\Ticket;
use App\Services\TicketService;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function returnForm()
    {
        return view('Admin::ticket.return');
    }

    public function getByBarcode(TicketService $ticketService, Request $request)
    {
        $code = $request->get('code');
        $ticket = $ticketService->getByBarcode($code);

        if (empty($ticket)) throw new NotFoundHttpException(__('Ticket not found'));

        $ticket->loadMissing([
            'place' => [
                'zone',
                'hall',
            ],
            'order.manager',
            'event',
        ]);

        $ticket_pos_parts = [];
        if ($ticket->place->row && $ticket->place->num) {
            $ticket_pos_parts[] = __('Row') . ": {$ticket->place->row}, " . __('Place') . ": {$ticket->place->num}";
        }
        if ($ticket->place->zone) {
            $ticket_pos_parts[] = "({$ticket->place->zone->name})";
        }

        return response()->json([
            'ticket_pos' => join(' ', $ticket_pos_parts),
            'event_name' => $ticket->event->name ?? null,
            'hall_name' => $ticket->place->hall->name ?? null,
            'bookkeeper' => $ticket->order->manager->fullname ?? null,
            'sale_date' => $ticket->order->paid_at->format('Y-m-d H:i:s') ?? null,
            'ticket_price' => $ticket->getBuyablePrice() ?? 0,
            'ticket_discount' => (float)$ticket->discount ?? 0,
            'commission' => (float)$ticket->realizator_commission ?? 0,
            'return_amount' => $ticket->getBuyablePrice() - $ticket->discount ?? 0,
            'ticket_id' => $ticket->id,
            'barcode' => $ticket->barcode,
        ]);
    }

    public function makeReturn(TicketService $ticketService, Request $request)
    {
        $ticketIds = $request->get('ticket_id', []);

        $ticketService->refundById($ticketIds);
    }
}
