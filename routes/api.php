<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\DeckController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});


Route::middleware('auth:sanctum')->group(function () {
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

});
