<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('payment/checkout', 'PaymentController@checkout')->name('payment.checkout');
Route::post('payment/processCheckout', 'PaymentController@processCheckout')->name('payment.processCheckout');
Route::get('payment/confirm/{order}', 'PaymentController@confirm')->name('payment.confirm');
Route::get('payment/success/{order}', 'PaymentController@success')->name('payment.success');
Route::get('payment/cancel/{order}', 'PaymentController@cancel')->name('payment.cancel');
Route::get('payment/error/{order}', 'PaymentController@error')->name('payment.error');
Route::get('cart/add/{ticket}', 'CartController@add')->name('cart.add');
Route::get('cart/remove/{id}', 'CartController@remove')->name('cart.remove');

Route::group([
    'namespace' => '\App\Http\Controllers',
], function() {
    Route::get('hall/{id}/{mode?}', 'WidgetController@index')->name('hallWidget');
});