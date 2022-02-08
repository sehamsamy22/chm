<?php

namespace App\Modules\Cart\Http\Controllers\Web;

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
Route::post('cookies_cart', [CartController::class, 'getCookiesItems']);
Route::group(['middleware' => ['auth:api']], function () {
    Route::post('carts', [CartController::class, 'store']);
    Route::get('cart', [CartController::class, 'items']);
    Route::Delete('items/{id}', [CartController::class, 'deleteItem']);
    Route::Delete('cart', [CartController::class, 'deleteCart']);
});
