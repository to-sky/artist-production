<?php

namespace App\Modules\Api\Controllers;

use App\Models\Ticket;
use App\Services\TicketService;
use Illuminate\Http\Request;

class OrderController extends ApiController
{
    public function getSelectedTickets(TicketService $ticketService, Request $request)
    {
        $ticket = $ticketService->getCartTickets($request->event_id)->where('status', Ticket::RESERVED);

        return $ticket->map(function ($ticket) {
            return [
                'eventId' => $ticket->event->id,
                'eventName' => $ticket->event->name,
                'eventDate' => $ticket->event->date->format('d.m.Y, H:i'),
                'id' => $ticket->id,
                'row' => $ticket->place->row,
                'place' => $ticket->place->num,
                'price' => $ticket->getBuyablePrice()
            ];
        });
    }

    public function freeTicket(TicketService $ticketService, Request $request)
    {
        $ticketService->freeById($request->ticket_id);

        return response()->json(null, 200);
    }
}
