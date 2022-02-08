<?php
namespace App\Modules\Subscribe\Http\Controllers\Admin;

use App\Modules\Contact\Http\Controllers\Admin\ContactController;
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
Route::group( ['middleware' => ['auth:admin-api'] ],function(){
    Route::resource('/subscribes', SubscribeController::class)->parameter('', 'subscribe');
    Route::post('send_emails', [SubscribeController::class,'sendEmails'])->name('sendEmails');
});
