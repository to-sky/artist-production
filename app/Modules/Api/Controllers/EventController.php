<?php

namespace App\Modules\Api\Controllers;

use App\Http\Requests\EventTicketsDeltaRequest;
use App\Models\Event;
use App\Models\Place;
use App\Models\Price;
use App\Models\PriceGroup;
use App\Services\EventService;
use App\Services\TicketService;

class EventController extends ApiController
{
    /**
     * Get event data
     *
     * @param EventService $eventService
     * @param Event $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(EventService $eventService, Event $event)
    {
        return response()->json(
            $eventService->schema($event)
        );
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
     * @param EventService $eventService
     * @param EventTicketsDeltaRequest $request
     * @param Event $event
     * @return \Illuminate\Http\JsonResponse
     */
    public function delta(EventService $eventService, EventTicketsDeltaRequest $request, Event $event)
    {
        $start = $request->get('last_update') ?: 0;

        return response()->json(
            $eventService->delta($event, $start)
        );
    }
}
