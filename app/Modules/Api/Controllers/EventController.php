<?php

namespace App\Modules\Api\Controllers;

use App\Http\Requests\EventTicketsDeltaRequest;
use App\Models\Event;
use App\Services\TicketService;

class EventController extends ApiController
{
    /**
     * Get event data
     *
     * @param TicketService $ticketService
     * @param Event $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(TicketService $ticketService, Event $event)
    {
        $event->loadMissing('eventImage');

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

        $selectedTickets = $ticketService->getCartTickets();

        return response()->json(compact(
            'event',
            'hall',
            'labels',
            'zones',
            'places',
            'prices',
            'priceGroups',
            'selectedTickets'
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

    /**
     * Get tickets updates from `last_update` to current time
     *
     * @param TicketService $ticketService
     * @param EventTicketsDeltaRequest $request
     * @param Event $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function delta(TicketService $ticketService, EventTicketsDeltaRequest $request, Event $event)
    {
        $end = time();
        $start = $request->get('last_update') ?: $end;

        $tickets = $ticketService->getStatusDelta($event, $start, $end);

        return response()->json([
            'tickets' => $tickets,
            'timestamp' => $end,
        ]);
    }
}
