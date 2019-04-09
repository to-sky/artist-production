<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WidgetController extends Controller
{
    public function index($id)
    {
        return view('widget.template', compact('id'));
    }

    public function getData($id)
    {

    }
}
