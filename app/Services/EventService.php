<?php
/**
 * Created by PhpStorm.
 * User: server
 * Date: 9/9/19
 * Time: 7:50 PM
 */

namespace App\Services;


use App\Handlers\Kartina\KartinaEventHandler;
use App\Handlers\Native\NativeEventHandler;
use App\Models\Event;

class EventService
{
    public function schema(Event $event)
    {
        $handler = $this->getHandler($event);

        return $handler->schema($event);
    }

    public function delta(Event $event, $ts)
    {
        $handler = $this->getHandler($event);

        return $handler->delta($event, $ts);
    }

    protected function getHandler(Event $event)
    {
        if ($event->kartina_id) {
            return new KartinaEventHandler();
        } else {
            return new NativeEventHandler();
        }
    }
}