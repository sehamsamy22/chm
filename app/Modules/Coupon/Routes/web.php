<?php
namespace App\Modules\Coupon\Http\Controllers\Web;

use Illuminate\Support\Facades\Route;

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
    Route::post('check_coupon',[CouponController::class,'checkCoupon']);
    // Route::resource('coupons', [CouponController::class])->parameter('', 'coupon_id');
});
