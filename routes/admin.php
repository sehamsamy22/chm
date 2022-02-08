<?php

namespace App\Http\Controllers\Admins;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('/forget-password', [ForgotPasswordController::class, 'postEmail']);
    Route::post('/reset-password', [ForgotPasswordController::class, 'updatePassword']);

    Route::group(['middleware' => ['auth:admin-api']], function () {
        Route::resource('admins', AdminController::class);
        Route::resource('opinions', CustomerOpinionController::class);
        Route::resource('occasions', OccasionController::class);
        Route::get('profile', [AuthController::class, 'profile']);
        Route::post('update-profile', [AuthController::class, 'updateProfile']);
        Route::post('change_password', [AuthController::class, 'change_password']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('setLocale', [AdminController::class,'setLocale']);

        // users
        Route::get('users', [UserController::class, 'index']);
        Route::get('users/{id}', [UserController::class, 'show']);
        Route::get('users/excel/export', [UserController::class, 'export']);
        Route::get('index', [HomeController::class, 'index']);
    });
