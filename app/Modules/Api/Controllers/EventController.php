<?php

namespace App\Modules\Api\Controllers;

use App\Models\Event;
use App\Models\Ticket;

class EventController extends ApiController
{
    /**
     * Get event data
     *
     * @param Event $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Event $event)
    {
        $hall = $event->hall()->with('building.city')->first();

        $labels = $event->hall->labels()->get([
            'id', 'hall_id', 'x', 'y', 'text', 'is_bold', 'is_italic', 'layer', 'rotation'
        ]);

        $zones = $event->hall->zones()->get(['id', 'hall_id', 'name', 'color']);

        $places = $event
            ->hall
            ->places()
            ->with([
                'tickets' => function ($q) use ($event) {
                    return $q->whereEventId($event->id);
                }
            ])
            ->get([
                'id',
                'hall_id',
                'text',
                'template',
                'x',
                'y',
                'width',
                'height',
                'path',
                'rotate',
                'row',
                'num',
            ])
        ;

        $prices = $event->prices()->get(['id', 'price', 'color']);

        $priceGroups = $event->priceGroups()->get(['id', 'name','discount']);

        return response()->json(compact(
            'event',
            'hall',
            'labels',
            'zones',
            'places',
            'prices',
            'priceGroups'
        ), 200);
    }

    /**
     * Get event data for widget in place binding mode
     *
     * @param Event $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function hallPrices(Event $event)
    {
        $places = $event->hall->places()->get([
            'id',
            'hall_id',
            'text',
            'template',
            'x',
            'y',
            'width',
            'height',
            'path',
            'rotate',
            'zone_id',
            'row'
        ])->toArray();

        $prices = $event->prices()->get(['id', 'price', 'color'])->toArray();

        $tickets = $event->tickets;

        return response()->json(compact('places', 'prices', 'tickets'));
    }

    protected function updatePlace($data)
    {
        $ticket = Ticket::updateOrCreate(
            [
                'event_id' => $data['event_id'],
                'place_id' => $data['place_id'],
            ],
            $data
        );

        return $ticket;
    }

    protected function updateFanZone($data, $count)
    {
        /** @var Ticket[] $tickets */
        $tickets = Ticket
            ::withTrashed()
            ->whereEventId($data['event_id'])
            ->wherePlaceId($data['place_id'])
            ->get()
        ;

        $ceil = max($tickets->count(), $count);

        $result = [];
        for ($i = 0; $i < $ceil; $i++) {
            if (isset($tickets[$i])) {
                $ticket = $tickets[$i];
            } else {
                $ticket = new Ticket();
            }

            if ($i > $count - 1) {
                $ticket->delete();
            } else {
                if ($ticket->trashed()) {
                    $ticket->restore();
                }

                $ticket->fill($data);
                $ticket->save();

                $result[] = $ticket;
            }
        }

        return $result;
    }
}
