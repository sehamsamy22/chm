<?php

namespace App\Modules\Product\Http\Controllers\Web;

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
Route::get('lists_occasions', [ListController::class, 'occasions']);
Route::get('lists_designers', [ListController::class, 'designers']);
Route::get('lists/{id}', [ListController::class, 'show']);
Route::get('brands', [BrandController::class, 'brands']);
Route::get('brands/{id}', [BrandController::class, 'brandProducts']);

Route::get('subscriptions', [ProductController::class, 'subscriptions']);
Route::get('services', [ProductController::class, 'services']);

Route::post('products_filtration', [ProductController::class, 'filtration']);
Route::resource('products', ProductController::class);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/add_comment', [ProductController::class, 'store_comment']);
    Route::post('/removeFromCompareList', [ProductController::class, 'removeFromCompareList']);
    Route::post('/add_rate', [ProductController::class, 'storeRate']);
    Route::post('/add_wish', [ProductController::class, 'storeWish']);
    Route::post('/add_compare', [ProductController::class, 'storeCompare']);
    Route::get('wish_lists/{type?}', [ProductController::class, 'getWishList']);
    Route::get('products_promotions', [ProductController::class, 'productsHasPromotions']);
});
