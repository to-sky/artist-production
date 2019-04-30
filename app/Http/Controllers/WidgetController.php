<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WidgetController extends Controller
{
    public function index($id, $mode = 'view')
    {
        return view('widget.template', compact('id', 'mode'));
    }
}
