<?php

namespace App\Handlers\Native;


use App\Handlers\Abstracts\TicketHandlerInterface;
use App\Models\Event;
use App\Models\Ticket;

class NativeTicketHandler implements TicketHandlerInterface
{
    public function reserve($data)
    {
        $tickets = Ticket
            ::whereStatus(Ticket::AVAILABLE)
            ->whereEventId($data['event_id'])
            ->wherePlaceId($data['place_id'])
            ->limit($data['count'])
            ->get()
        ;

        return $tickets;
    }

    public function free(Ticket $ticket)
    {
        $ticket->user()->dissociate();
        $ticket->status = Ticket::AVAILABLE;
        $ticket->reserved_to = null;
        $ticket->kartina_id = null;
        $ticket->save();
    }
}