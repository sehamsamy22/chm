<?php

namespace App\Modules\Address\Http\Controllers\Web;

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
Route::get('currencies', [AddressController::class, 'currencies']);
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::resource('addresses', AddressController::class);
    Route::post('addresses/{address_id}/default_address', [AddressController::class, 'setDefaultAddress']);
    Route::get('cities/{id}', [AddressController::class, 'cities']);
    Route::get('areas/{id}', [AddressController::class, 'areas']);

});
