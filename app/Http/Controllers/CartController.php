<?php

namespace App\Http\Controllers;


use App\Models\Place;
use App\Services\TicketService;
use App\Models\Ticket;

class CartController
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function removeById($id)
    {
        $this->ticketService->freeById($id);

        return back();
    }

    public function destroy()
    {
        $this->ticketService->emptyCart();

        return back();
    }
}