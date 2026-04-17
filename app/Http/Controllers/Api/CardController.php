<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
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
     * Update a card
     *
     * Aktualisiert eine bestehende Karte in einem Deck.
     *
     * @urlParam deck integer required Die ID des Decks. Example: 1
     * @urlParam card integer required Die ID der Karte. Example: 5
     *
     * @bodyParam front string required Vorderseite der Karte. Example: Was ist ein Controller?
     * @bodyParam back string required Rückseite der Karte. Example: Eine Klasse für Request-Handling
     *
     * @response 200 {
     *   "id": 5,
     *   "front": "Was ist ein Controller?",
     *   "back": "Eine Klasse für Request-Handling",
     *   "deck_id": 1,
     *   "created_at": "2026-04-14T10:00:00.000000Z",
     *   "updated_at": "2026-04-18T10:00:00.000000Z"
     * }
     */
    public function update(Request $request, Deck $deck, Card $card)
    {
        $validated = $request->validate([
            'front' => 'required|string',
            'back' => 'required|string',
        ]);

        $card->update($validated);
        return response()->json($card);
    }

    /**
     * Delete a card
     *
     * Löscht eine Karte innerhalb eines Decks.
     *
     * @urlParam deck integer required Die ID des Decks. Example: 1
     * @urlParam card integer required Die ID der Karte. Example: 5
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Card deleted"
     * }
     */
    public function destroy(Deck $deck, Card $card)
    {
        $deck->cards()->whereKey($card->id)->firstOrFail()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Card deleted'
        ]);
    }
}
