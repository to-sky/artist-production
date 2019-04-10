<?php

namespace App\Http\Controllers;

use App\Models\Hall;

class HallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function getHalls()
    {
        $halls = Hall::all();
    }
}
