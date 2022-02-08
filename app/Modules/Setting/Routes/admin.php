<?php
namespace App\Modules\Setting\Http\Controllers\Admin;

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

Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
    Route::group( ['middleware' => ['auth:admin-api'] ],function(){
        Route::resource('/', SettingController::class)->parameter('', 'setting')->except(['create', 'edit', 'update', 'destroy']);
    });
});
