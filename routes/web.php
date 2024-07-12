<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;
use Illuminate\Support\Facades\Route;

Route::get('register', function () {
    return view('auth.register');
})->name('register');

Route::get('login', function () {
    return view('auth.login');
})->name('login');

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::post('posts', [PostController::class, 'store'])->name('posts.store');
    Route::delete('posts/{id}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('comments', [CommentController::class, 'store'])->name('comments.store');
});
