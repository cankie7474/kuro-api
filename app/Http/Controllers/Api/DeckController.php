<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deck;
use Illuminate\Http\Request;

class DeckController extends Controller
{
    /**
     * Get all decks
     *
     * Gibt alle Decks zurück.
     *
     * @response 200 [
     *   {
     *     "id": 1,
     *     "title": "Mathe",
     *     "description": "Formeln lernen",
     *     "color": "#4ACDB5",
     *     "created_at": "2026-04-14T10:00:00.000000Z",
     *     "updated_at": "2026-04-14T10:00:00.000000Z"
     *   }
     * ]
     */
    public function index()
    {
        return response()->json(Deck::all());
    }

    /**
     * Create a new deck
     *
     * Erstellt ein neues Deck.
     *
     * @bodyParam title string required Titel des Decks. Example: Englisch Vokabeln
     * @bodyParam description string Beschreibung des Decks. Example: Lernen für Test
     * @bodyParam color string Farbe des Decks. Example: #098588
     *
     * @response 201 {
     *   "id": 1,
     *   "title": "Englisch Vokabeln",
     *   "description": "Lernen für Test",
     *   "color": "#098588",
     *   "created_at": "2026-04-14T10:00:00.000000Z",
     *   "updated_at": "2026-04-14T10:00:00.000000Z"
     * }
     */
    public function store(Request $request)
    {
        // connected to fillable
        $validated = $request->validate([
            'title' => 'required|string|max:120',
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|max:30',
            ]);

        $deck = Deck::create($validated);

        return response()->json($deck, 201);

    }

    /**
     * Get a single deck
     *
     * Gibt ein einzelnes Deck zurück.
     *
     * @urlParam deck integer required Die ID des Decks. Example: 1
     *
     * @response 200 {
     *   "id": 1,
     *   "title": "Mathe",
     *   "description": "Formeln lernen",
     *   "color": "#4ACDB5",
     *   "created_at": "2026-04-14T10:00:00.000000Z",
     *   "updated_at": "2026-04-14T10:00:00.000000Z"
     * }
     */
    public function show(Deck $deck)
    {
        return response()->json($deck);
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
