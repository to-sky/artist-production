<?php

namespace App\Listeners;

use App\Events\ParseEventSaved;
use App\Libs\Kartina\Api;
use Illuminate\Contracts\Queue\ShouldQueue;

class StoreHallSchema implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ParseEventSaved $event
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(ParseEventSaved $event)
    {
        if (! $event->parseEvent->is_parsed) {
            (new Api())->parse($event->parseEvent);
        }
    }
}
