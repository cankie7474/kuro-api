<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deck;
use Illuminate\Http\Request;

class CardController extends Controller
{
    /**
     * Get all cards of a deck
     *
     * Gibt alle Karten eines bestimmten Decks zurück.
     *
     * @urlParam deck integer required Die ID des Decks. Example: 1
     *
     * @response 200 [
     *   {
     *     "id": 1,
     *     "front": "Was ist Laravel?",
     *     "back": "Ein PHP Framework",
     *     "deck_id": 1,
     *     "created_at": "2026-04-14T10:00:00.000000Z",
     *     "updated_at": "2026-04-14T10:00:00.000000Z"
     *   }
     * ]
     */
    public function index(Deck $deck)
    {
        return response()->json($deck->cards);
    }

    /**
     * Create a new card
     *
     * Erstellt eine neue Karte in einem Deck.
     *
     * @urlParam deck integer required Die ID des Decks. Example: 1
     *
     * @bodyParam front string required Vorderseite der Karte. Example: Was ist PHP?
     * @bodyParam back string required Rückseite der Karte. Example: Eine Programmiersprache
     *
     * @response 201 {
     *   "id": 1,
     *   "front": "Was ist PHP?",
     *   "back": "Eine Programmiersprache",
     *   "deck_id": 1,
     *   "created_at": "2026-04-14T10:00:00.000000Z",
     *   "updated_at": "2026-04-14T10:00:00.000000Z"
     * }
     */
    public function store(Request $request, Deck $deck)
    {
        $validated = $request->validate([
            'front' => 'required|string',
            'back' => 'required|string',
        ]);

        $card = $deck->cards()->create($validated);

        return response()->json($card, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
