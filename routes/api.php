<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\CardReviewController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DeckController;
use App\Http\Controllers\Api\StudyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('users', [UserController::class, 'index']);
});

Route::middleware('auth:sanctum')->scopeBindings()->group(function () {
    // Decks
    Route::get('/decks', [DeckController::class, 'index']);
    Route::get('/decks/{deck}', [DeckController::class, 'show']);
    Route::post('/decks', [DeckController::class, 'store']);
    Route::delete('/decks/{deck}', [DeckController::class, 'destroy']);
    Route::patch('/decks/{deck}', [DeckController::class, 'update']);

    // Card
    Route::get('/decks/{deck}/cards', [CardController::class, 'index']);
    Route::post('/decks/{deck}/cards', [CardController::class, 'store']);
    Route::delete('/decks/{deck}/cards/{card}', [CardController::class, 'destroy']);
    Route::patch('/decks/{deck}/cards/{card}', [CardController::class, 'update']);
    Route::get('/decks/{deck}/study', [StudyController::class, 'index']);
    Route::post('/cards/{card}/review', [CardReviewController::class, 'store']);

});
