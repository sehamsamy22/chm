<?php

namespace App\Modules\Category\Http\Controllers\Web;

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
Route::resource('categories', CategoryController::class);
Route::get('/category_additions/{id}', [CategoryController::class,'categoryAdditions']);

//Route::group(['middleware' => ['auth:api']], function () {
//    // Route::resource('categories', [CategoryController::class])->parameter('', 'category_id');
//});
