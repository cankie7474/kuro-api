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
Route::post('/decks', [DeckController::class, 'store']);
Route::get('/decks/{deck}', [DeckController::class, 'show']); // get single deck

// Card Controller
Route::get('/decks/{deck}/cards', [CardController::class, 'index']);
Route::post('/decks/{deck}/cards', [CardController::class, 'store']);
