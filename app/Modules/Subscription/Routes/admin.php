<?php
namespace App\Modules\Subscription\Http\Controllers\Admin;

use App\Modules\Subscription\Http\Controllers\Admin\WrappingTypeController;
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
    Route::resource('wrappingTypes', WrappingTypeController::class);
    Route::resource('subscriptionSizes', SubscriptionSizeController::class);
    Route::resource('subscriptionItems', SubscriptionItemController::class);
    Route::resource('subscriptionTypes', SubscriptionTypeController::class);
    Route::resource('subscriptionDeliveryCounts', SubscriptionDeliveryCountController::class);
    Route::resource('subscriptionDayCounts', SubscriptionDayCountController::class);
    Route::resource('subscriptions', SubscriptionController::class);
    Route::resource('careInstructions', CareInstructionController::class);

});
