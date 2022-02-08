<?php
namespace App\Modules\Setting\Http\Controllers\Web;

use App\Modules\Setting\Entities\Setting;
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

Route::get('settings/{name?}', [SettingController::class,'setting']);
Route::group(['middleware' => ['auth:sanctum']], function () {
});
