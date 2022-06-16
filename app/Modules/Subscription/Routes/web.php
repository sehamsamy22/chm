<?php
namespace App\Modules\Subscription\Http\Controllers\Web;

use App\Modules\Blog\Http\Controllers\Web\BlogController;
use App\Modules\Subscription\Http\Controllers\Admin\CareInstructionController;
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
Route::get('wrappingTypes', [SubscriptionController::class, 'wrappingTypes']);
Route::get('sizes', [SubscriptionController::class, 'sizes']);
Route::get('items', [SubscriptionController::class, 'items']);

Route::get('types', [SubscriptionController::class, 'types']);
Route::get('days', [SubscriptionController::class, 'days']);
Route::get('deliveries', [SubscriptionController::class, 'deliveries']);
Route::post('subscriptions', [SubscriptionController::class, 'subscriptions']);
Route::get('careInstructions', [SubscriptionController::class, 'careInstructions']);

