<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\Auth\AuthController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\API\UserController;


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

    Route::get('users', [UserController::class, 'index'])->name('users');
    Route::get('users/{user}', [UserController::class, 'show'])->name('user');
    Route::get('user', [UserController::class, 'show_current_user'])->name('current_user');
    Route::delete('users/{user}', [UserController::class, 'destroy']);

    Route::get('users/{user}/posts', [PostController::class, 'index_users'])->name('user_posts');

    Route::get('posts/{post}', [PostController::class, 'show'])->name('post');
    Route::delete('posts/{post}', [PostController::class, 'destroy']);
    Route::post('posts/create', [PostController::class, 'create']);
    Route::post('posts/{post}', [PostController::class, 'update']);
});
