<?php

namespace App\Modules\Blog\Http\Controllers\Web;

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
Route::get('features', [FeatureController::class, 'index']);
Route::resource('blogs', BlogController::class)->parameter('', 'blog_id');
Route::get('blog_similar/{id}', [BlogController::class, 'blogSimilar']);
Route::get('blog_categories', [BlogController::class, 'blogCategories']);
Route::get('category_blogs/{id}', [BlogController::class, 'categoryBlogs']);

