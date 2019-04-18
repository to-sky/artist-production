<?php

namespace App\Modules\Api\Controllers;

use App\Models\Hall;

class HallController extends ApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $halls = Hall::all();

        return response()->json($halls, 200);
    }
}
