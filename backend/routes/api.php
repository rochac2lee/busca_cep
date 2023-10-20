<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::get('/addresses/search', 'AddressesController@search');
    Route::get('/addresses/{cep}', 'AddressesController@search_cep');
    Route::resource("addresses", "AddressesController");
});