<?php

use App\Http\Controllers\Api\CardController;
use App\Http\Controllers\Api\DeckController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Deck Controller
Route::get('/decks', [DeckController::class, 'index']);
Route::get('/decks/{deck}', [DeckController::class, 'show']);
Route::post('/decks', [DeckController::class, 'store']);
Route::delete('/decks/{deck}', [DeckController::class, 'destroy']);
Route::patch('/decks/{deck}', [DeckController::class, 'update']);

// Card Controller
Route::get('/decks/{deck}/cards', [CardController::class, 'index']);
Route::post('/decks/{deck}/cards', [CardController::class, 'store']);
Route::delete('/decks/{deck}/cards/{card}', [CardController::class, 'destroy']);
Route::patch('/decks/{deck}/cards/{card}', [CardController::class, 'update']);
