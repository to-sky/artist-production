<?php

namespace App\Handlers\Abstracts;

use App\Models\Event;

interface EventHandlerInterface
{
    /**
     * @param Event $event
     * @return array
     */
    public function schema(Event $event);

    /**
     * @param Event $event
     * @param int $ts - timestamp
     * @return array
     */
    public function delta(Event $event, $ts);

    public function statistic(Event $event);
}