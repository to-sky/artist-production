<?php

namespace App\Handlers\Kartina;


use App\Handlers\Abstracts\TicketHandlerInterface;
use App\Libs\Kartina\Purchase;
use App\Models\Event;
use App\Models\Place;
use App\Models\Ticket;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class KartinaTicketHandler implements TicketHandlerInterface
{
    protected $api;

    public function __construct()
    {
        $this->api = new Purchase();
    }

    public function reserve($data)
    {
        $event = Event::find($data['event_id']);
        $place = Place::find($data['place_id']);

        $r = $this->api->addToOrder($event->kartina_id, $place->kartina_id, $data['count']);

        if ($r['CommandStatus'] != '0') throw new UnprocessableEntityHttpException($r['errormessage']);

        $kartinaOrder = $this->api->getOrder($r['orderId']);
        $orderTickets = collect($kartinaOrder['items']);
        $items = $orderTickets->slice($orderTickets->count() - $data['count'], $data['count']);

        $tickets = Ticket
            ::whereStatus(Ticket::AVAILABLE)
            ->whereEventId($data['event_id'])
            ->wherePlaceId($data['place_id'])
            ->limit($data['count'])
            ->get()
        ;

        foreach ($items as $i => $kartinaTicket) {
            $ticket = isset($tickets[$i]) ? $tickets[$i] : $this->makeTicket($place, $data);

            $ticket->price = $kartinaTicket['price'];
            $ticket->kartina_id = $kartinaTicket['id'];
            $ticket->status = Ticket::RESERVED;
            $ticket->save();

            $tickets[$i] = $ticket;
        }

        return $tickets;
    }

    protected function makeTicket(Place $place, $data)
    {
        $ticket = new Ticket([
            'place_id' => $place->id,
            'price_id' => $place->price_id,
            'event_id' => $data['event_id'],
        ]);

        return $ticket;
    }

    public function free(Ticket $ticket)
    {
        $this->api->removeFromOrder($ticket->kartina_id);

        $ticket->user()->dissociate();
        $ticket->status = Ticket::AVAILABLE;
        $ticket->reserved_to = null;
        $ticket->kartina_id = null;
        $ticket->save();
    }
}