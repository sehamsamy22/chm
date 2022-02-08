<?php
namespace App\Http\Controllers\Customers;
use App\Http\Controllers\Admins\AdminController;
use App\Http\Controllers\UploadsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('test', [HomeController::class, 'test']);
Route::get('opinions', [HomeController::class, 'opinions']);
Route::get('bannerMiddle', [HomeController::class, 'bannerMiddle']);
Route::get('exchange_rate', [ExchangeRateController::class, 'ExchangeRate']);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('/user-forget-password', [ForgotPasswordController::class, 'postEmail']);
Route::post('/user-reset-password', [ForgotPasswordController::class, 'updatePassword']);
Route::post('/verify_code', [AuthController::class, 'verify']);
Route::post('/resend_verify_code', [AuthController::class, 'resendVerificationCode']);

Route::group(['middleware' => ['auth:api']], function () {
    Route::get('profile', [ProfileController::class, 'profile']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('update-profile', [ProfileController::class,'update']);
    Route::post('update_password', [ProfileController::class,'updatePassword']);
    Route::get('notifications', [NotificationController::class, 'notifications']);
    Route::resource('occasions', OccasionController::class);

});

Route::post('/upload', [UploadsController::class, 'upload'])->name('upload');
Route::get('index', [HomeController::class, 'index']);
Route::get('more_ordered_products', [HomeController::class, 'moreOrderedProduct']);
//Facebook Login
Route::get('login/facebook', [SocialController::class, 'facebookRedirect']);
//Google Login
Route::get('login/google', [SocialController::class, 'redirectToGoogle']);

Route::get('login/social', [SocialController::class, 'loginWithFacebook']);
Route::get('login/callBackGoogle', [SocialController::class, 'loginWithGoogle']);
