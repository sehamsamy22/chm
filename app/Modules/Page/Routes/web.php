<?php
namespace App\Modules\Page\Http\Controllers\Web;

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
 Route::get('pages/{id}', [PageController::class,'show']);
Route::get('pages', [PageController::class,'index']);
//Route::group(['middleware' => ['auth:api']], function () {
//    // Route::resource('pages', [PageController::class])->parameter('', 'page_id');
//});
