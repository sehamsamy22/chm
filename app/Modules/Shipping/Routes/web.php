<?php
namespace App\Modules\Shipping\Http\Controllers\Admin;

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
    // Route::resource('shippings', [ShippingController::class])->parameter('', 'shipping_id');
    Route::post('create_shipments/{cart}',[ShippingController::class,'shipToAramex']);

});
