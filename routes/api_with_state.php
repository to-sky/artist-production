<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here are api routes which require session to work
|
*/

Route::post('tickets', 'TicketController@updateTicket');
Route::post('tickets/reserve', 'TicketController@reserve');
Route::post('tickets/free', 'TicketController@free');
Route::post('tickets/free/{id}', 'TicketController@freeById');
Route::get('tickets/cart', 'TicketController@cart');

Route::get('events/{event}/delta', 'EventController@delta');
Route::get('events/{event}', 'EventController@show');
Route::get('events/{event}/setup',  'EventController@hallPrices');

Route::prefix('orders')->group(function () {
    Route::get('events/event_id', 'OrderController@getSelectedTickets')->name('orders.getSelectedTickets');
    Route::patch('tickets/free', 'OrderController@freeTicket')->name('tickets.freeTicket');
});
