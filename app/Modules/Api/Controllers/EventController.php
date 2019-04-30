<?php

namespace App\Modules\Api\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;

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
            'id', 'hall_id', 'text', 'template', 'x', 'y', 'width', 'height', 'path', 'rotate'
        ])->toArray();

        $prices = $event->prices()->get(['id', 'price', 'color'])->toArray();

        $tickets = $event->tickets;

        return response()->json(compact('places', 'prices', 'tickets'));
    }

    public function updateTicket(Request $request)
    {
        $data = $request->all([
            'event_id',
            'place_id',
            'price_id',
            'amount_printed',
        ]);

        $ticket = Ticket
            ::withTrashed()
            ->whereEventId($data['event_id'])
            ->wherePlaceId($data['place_id'])
            ->firstOrCreate($data)
        ;

        dd($ticket);
    }
}
