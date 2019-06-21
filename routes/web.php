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
Route::get('payment/shippingOptions/{country}', 'PaymentController@shippingOptions')->name('payment.shippingOptions');
Route::get('payment/confirm/{order}', 'PaymentController@confirm')->name('payment.confirm');
Route::get('payment/success/{order}', 'PaymentController@success')->name('payment.success');
Route::get('payment/cancel/{order}', 'PaymentController@cancel')->name('payment.cancel');
Route::get('payment/error/{order}', 'PaymentController@error')->name('payment.error');
Route::post('cart/remove/{id}', 'CartController@removeById')->name('cart.remove');
Route::post('cart/destroy', 'CartController@destroy')->name('cart.destroy');

Route::get('hall/{id}/{mode?}', 'WidgetController@index')->name('hallWidget');

Route::group(['middleware' => 'auth'], function () {
    Route::get('profile', 'ProfileController@show')->name('profile.show');
    Route::post('profile', 'ProfileController@update')->name('profile.update');

    Route::get('orders', 'OrderController@index')->name('order.index');
    Route::get('orders/{order}', 'OrderController@show')->name('order.show');

    Route::get('addresses', 'AddressController@index')->name('address.index');
    Route::get('addresses/create', 'AddressController@create')->name('address.create');
    Route::post('addresses', 'AddressController@save')->name('address.save');
    Route::get('addresses/{address}', 'AddressController@show')->name('address.show');
    Route::post('addresses/{address}', 'AddressController@update')->name('address.update');
    Route::delete('address/{address}', 'AddressController@remove')->name('address.delete');
});
