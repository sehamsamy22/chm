<?php

namespace App\Modules\Payment\Http\Controllers\Web;

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
Route::get('payments', [PaymentController::class, 'index']);
Route::group(['middleware' => ['auth:api']], function () {
    Route::get('my_wallet', [PaymentController::class, 'myWallet']);
    Route::post('/paypal', [PaypalPaymentController::class, 'payWithpaypal'])->name('paypal');
    Route::post('/paypal', [PaypalPaymentController::class, 'payWithpaypal'])->name('paypal');
    Route::get('/handle-payment/declined', [PaymentController::class, 'declined']);
    Route::get('/handle-payment/cancel', [PaymentController::class, 'cancel']);
    Route::get('/handle-payment/success', [PaymentController::class, 'success']);

// route for check status of the payment
    Route::get('/status', [PaypalPaymentController::class, 'getPaymentStatus'])->name('status');
});
