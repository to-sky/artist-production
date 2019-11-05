<?php

namespace App\Events;

use App\Models\ParseEvent;
use Illuminate\Queue\SerializesModels;

class ParseEventSaved
{
    use SerializesModels;

    public $parseEvent;

    /**
     * Create a new event instance.
     *
     * @param ParseEvent $parseEvent
     */
    public function __construct(ParseEvent $parseEvent)
    {
        $this->parseEvent = $parseEvent;
    }
}
