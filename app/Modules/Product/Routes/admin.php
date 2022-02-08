<?php

namespace App\Modules\Product\Http\Controllers\Admin;

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
    Route::resource("products", ProductController::class);
    Route::resource("promotions", PromotionController::class);
    Route::resource("lists", ListController::class);
    Route::resource("colors", ProductColorController::class);
    Route::resource("brands", BrandController::class);
    Route::get('/get_product_comment/{id}', [ProductController::class, 'getComments']);
    Route::get('/get_product_rate/{id}', [ProductController::class, 'getRates']);
    Route::get('/get_product_wish/{id}', [ProductController::class, 'store_wish']);
    Route::get('products/excel/export', [ProductController::class, 'exportExcel']);
    Route::post('products/excel/import', [ProductController::class, 'importExcel']);
    Route::get('subscriptions', [ProductController::class, 'subscriptions']);
    Route::get('services', [ProductController::class, 'services']);
    Route::get('additions', [ProductController::class, 'additions']);

});
