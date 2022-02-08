<?php

namespace App\Modules\Order\Http\Controllers\Admin;

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
    Route::resource('orders', OrderController::class)->parameter('', 'order_id');
    Route::resource('times', PickupTimeController::class);
    Route::post('orders_filtration', [OrderController::class, 'filtration']);
    Route::get('orders/excel/export', [OrderController::class, 'exportExcel']);
    Route::post('createShipment', [OrderController::class, 'createShipment']);
    Route::post('change-status/{id}', [OrderController::class, 'changeStatus']);
    Route::get('toggleTime/{id}', [PickupTimeController::class, 'toggleTime']);

});
