<?php

namespace App\Modules\Address\Http\Controllers\Admin;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Admin routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your Admin API!
|
*/

Route::group(['middleware' => ['auth:admin-api']], function () {
    Route::resource('countries', CountryController::class);
    Route::resource('cities', CityController::class);
    Route::resource('areas', AreaController::class);
    Route::resource('addresses', AddressController::class);
    Route::resource('currencies', CurrencyController::class);
    Route::get('user-addresses/{id}', [AddressController::class, 'userAddresses']);
    Route::post('quick_edit', [AddressController::class, 'quickEditShippingPrice']);

});
