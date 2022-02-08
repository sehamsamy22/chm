<?php

namespace App\Modules\Order\Http\Controllers\Web;

use Illuminate\Support\Facades\Route;
use Modules\Invoice\Http\Controllers\Web\InvoiceController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "api" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['auth:api']], function () {
    Route::resource('orders', OrderController::class)->parameter('', 'order_id');
    Route::post('check_coupon_order', [OrderController::class, 'checkCoupon']);
    Route::get('get_delivery_fees/{id}', [OrderController::class, 'getDeliveryFees']);
    Route::get('orders_filter', [OrderController::class, 'ordersFilter']);
    Route::get('get_pickup_times/{day}', [OrderController::class, 'getPickupTimes']);

});
Route::get('moyasar_callback', [OrderController::class, 'moyasar_callback']);
Route::any('payments/status/{payment_method?}', [OrderController::class, 'callback']);
Route::any('orders/pay_call_back/{payment_method}', [OrderController::class, 'callback']);
Route::any('handle-payment/declined', [OrderController::class, 'telrDeclinedCallback']);
Route::any('handle-payment/success', [OrderController::class, 'telrSuccessCallback']);

