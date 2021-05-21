<?php

namespace App\Handlers\Abstracts;


use App\Models\Ticket;

interface TicketHandlerInterface
{
    public function reserve($data);

    public function free(Ticket $ticket);
}