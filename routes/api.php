<?php

use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(AuthMiddleware::class)->group(function () {
  Route::prefix('books')->group(function () {
    Route::get('/', [BookController::class, 'index']);
    Route::get('/{id}', [BookController::class, 'show']);
    Route::post('/create', [BookController::class, 'store']);
    Route::put('/update/{id}', [BookController::class, 'update']);
    Route::delete('/delete/{id}', [BookController::class, 'destroy']);
  });

  Route::prefix('favorites')->group(function () {
    Route::get('/', [FavoriteController::class, 'index']);
    Route::post('/add/{id}', [FavoriteController::class, 'store']);
    Route::delete('/remove/{id}', [FavoriteController::class, 'destroy']);
  });
  Route::post('/logout', [AuthController::class, 'logout']);
});

