<?php

namespace App\Http\Controllers;


use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Ticket;
use Illuminate\Http\Request;

class CartController
{
    public function add(Ticket $ticket)
    {
        Cart::add($ticket);
    }

    public function update($id, Request $request)
    {

        Cart::update($id, $request->all());
    }

    public function remove($id)
    {
        Cart::remove($id);

        return back();
    }

    public function destroy()
    {
        Cart::destroy();
    }
}