<?php

namespace App\Modules\Category\Http\Controllers\Admin;

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
    Route::resource('/categories', CategoryController::class);
    Route::resource('/options', OptionController::class);
    Route::resource('/category_options', CategoryOptionController::class)->except('show');
    Route::get('/category_options/{id}', [CategoryOptionController::class,'categoryOptions']);
    Route::get('/category_additions/{id}', [CategoryController::class,'categoryAdditions']);
}
);
