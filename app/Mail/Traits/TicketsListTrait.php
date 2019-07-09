<?php

namespace App\Mail\Traits;


use App\Models\Order;
use App\Models\Ticket;

/**
 * Converts order tickets to list string
 *
 * Trait TicketsListTrait
 * @package App\Mail\Traits
 */
trait TicketsListTrait
{
    /**
     * Convert order tickets collection into string representation
     *
     * @param Order $order
     * @return string
     */
    protected function _getTicketsListPlaceholder(Order $order)
    {
        $tickets = $order->tickets->loadMissing('');

        $list = [];
        foreach ($tickets as $ticket) {
            $list[] = $this->_ticketToString($ticket);
        }

        return join('; ', $list);
    }

    /**
     * Converts ticket to string
     *
     * @param Ticket $ticket
     * @return string
     */
    private function _ticketToString(Ticket $ticket)
    {
        $eventName = $ticket->event->name;
        $eventDate = $ticket->event->date->format('d.m.Y H:i');
        $cityName = $ticket->event->hall->building->city->name;
        $zoneName = $ticket->place->zone->name ?? '-';
        $row = $ticket->place->row ?? '-';
        $num = $ticket->place->num ?? '-';

        return "{$eventName} ({$eventDate}), {$cityName}, {$zoneName}, r. {$row}, p. {$num}";
    }
}