<?php
namespace App\Modules\Ad\Http\Controllers\Admin;

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
    Route::resource("ads", AdController::class)->parameter('', 'ad_id');
    Route::get('ad_locations', [AdController::class,'adLocations']);
    Route::get('filter/{type}', [AdController::class,'filtration']);

});
