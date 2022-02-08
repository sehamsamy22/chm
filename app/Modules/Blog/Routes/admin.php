<?php

namespace App\Modules\Blog\Http\Controllers\Admin;

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
    Route::resource('blogs', BlogController::class);
    Route::resource('features', FeatureController::class);
    Route::resource('blog_categories', CategoryController::class);

});
