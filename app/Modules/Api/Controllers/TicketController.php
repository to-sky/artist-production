<?php

namespace App\Modules\Api\Controllers;

use App\Models\Place;
use App\Services\TicketService;
use Illuminate\Http\Request;
use Keygen\Keygen;

class TicketController extends ApiController
{
    protected $ticketService;

    public function __construct(TicketService $ticketService)
    {
        $this->ticketService = $ticketService;
    }

    /**
     * Updates ticket - price binding for place tickets
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Exception
     */
    public function updateTicket(Request $request)
    {
        $tickets = $this->ticketService->manage($request);

        return response()->json(compact('tickets'));
    }

    /**
     * Reserve tickets by ticket id
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reserve(Request $request)
    {
        $tickets = $this->ticketService->reserve(
            $this->ticketService->getReserveDataFromRequest($request)
        );

        return response()->json(compact('tickets'));
    }

    /**
     * Free tickets reserved for place
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function free(Request $request)
    {
        $tickets = $this->ticketService->free(
            $this->ticketService->getFreeDataFromRequest($request)
        );

        return response()->json(compact('tickets'));
    }

    /**
     * Free tickets by ticket id
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function freeById($id)
    {
        $tickets = $this->ticketService->freeById($id);

        return response()->json(compact('tickets'));
    }
}
