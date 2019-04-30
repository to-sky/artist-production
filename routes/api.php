<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([
    'module'=>'Api',
    'namespace' => 'Api\Controllers'
], function() {
    Route::get('events/{event}', 'EventController@show');
    Route::get('events/{event}/setup',  'EventController@hallPrices');

    Route::post('tickets', 'EventController@updateTicket');
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
