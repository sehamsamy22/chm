<?php

namespace App\Modules\Shipping\Http\Controllers\Admin;

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
    Route::resource('shipping_methods', ShippingMethodController::class);
    Route::post('add_shipment/{id}', [ShippingController::class, 'AddShipment']);
    Route::post('get_code_cities', [ShippingController::class, 'GetCODCitiesList']);
    Route::post('get_pick_up_cities', [ShippingController::class, 'GetPickupCitiesList']);
    Route::post('can_be_canceled', [ShippingController::class, 'CanBeCanceled']);
    Route::post('cancel_shipment', [ShippingController::class, 'CancelShipment']);
    Route::post('get_shipment_label', [ShippingController::class, 'GetShipmentLabel']);
    Route::post('get_shipment_prices', [ShippingController::class, 'GetShipmentPrices']);
    Route::post('get_shipments_status', [ShippingController::class, 'GetShipmentsStatus']);
    Route::post('read_shipment', [ShippingController::class, 'ReadShipment']);

});
