<?php
namespace App\Modules\Permission\Http\Controllers\Admin;

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
    // authenticated staff routes here
    Route::get('permissions', [RoleController::class, 'allPermissions']);
    //Get Admins By Role
    Route::get('admins_role/{roleName}', [RoleController::class, 'AdminsHasRole']);
    // Roles And Permissions
    Route::resource('roles',RoleController::class);
});
