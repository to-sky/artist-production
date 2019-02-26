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
    $api = new \App\Libs\Kartina\Api();

    $result = $api->storeHallSchema(581834836);
//    $result = $api->getAuth();
//    $result = $api->getBuildingsFromCache();

    dd($result);

    return view('welcome');
});
