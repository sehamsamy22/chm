<?php
namespace App\Modules\Ad\Http\Controllers\Web;

use App\Modules\Ad\Http\Controllers\Web\AdController;
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
Route::get('ads/{location}', [AdController::class,'getAds']);
Route::get('ads', [AdController::class,'ads']);
Route::group(['middleware' => ['auth:api']], function () {
});
