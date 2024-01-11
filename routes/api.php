<?php

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\KeywordController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\SearchController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['namespace' => 'API', 'middleware' => ['guest']], function () {
    Route::post("login", [AuthController::class, "login"]);
    Route::post("register", [AuthController::class, "register"]);
});

Route::group(['namespace' => 'API', 'middleware' => ['auth:sanctum']], function () {
    Route::post("logout", [AuthController::class, "logout"]);

    Route::get('feed', [PostController::class, 'feed'])->name('feed');
    Route::get('keyword/{keyword}', [KeywordController::class, 'show'])->name('keyword');
    Route::get('search', [SearchController::class, 'search'])->name('search');
    Route::post('like/{post}', [PostController::class, 'like'])->name('like');
});
