<?php
namespace App\Modules\Coupon\Http\Controllers\Admin;

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
     Route::resource("coupons", CouponController::class)->parameter('', 'coupon_id');
    Route::get('coupons/excel/export', [CouponController::class, 'exportExcel']);

});
