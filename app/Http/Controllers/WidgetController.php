<?php

namespace App\Http\Controllers;

use App\Services\TicketService;
use Illuminate\Http\Request;

class WidgetController extends Controller
{
    protected $ticketService;
    
    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    public function index($id, $mode = 'view')
    {
        return view('widget.template', compact('id', 'mode'));
    }
}
