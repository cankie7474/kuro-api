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
     * @group Decks
     * @authenticated
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
    public function index(Request $request)
    {
        return response()->json(
            $request->user()->decks()->latest()->get()
        );
    }

    /**
     * Create a new deck
     *
     * Erstellt ein neues Deck.
     *
     * @group Decks
     * @authenticated
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
        $validated = $request->validate([
            'title' => 'required|string|max:120',
            'description' => 'nullable|string|max:1000',
            'color' => 'nullable|string|max:30',
        ]);

        $deck = $request->user()->decks()->create($validated);

        return response()->json($deck, 201);
    }

    /**
     * Get a single deck
     *
     * Gibt ein einzelnes Deck zurück.
     *
     * @group Decks
     * @authenticated
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
    public function show(Request $request, Deck $deck)
    {
        abort_if($deck->user_id !== $request->user()->id, 403);
        return response()->json($deck);
    }

    /**
     * Update a deck
     *
     * Aktualisiert ein bestehendes Deck.
     *
     * @group Decks
     * @authenticated
     *
     * @urlParam deck integer required Die ID des Decks. Example: 1
     *
     * @bodyParam title string required Titel des Decks. Example: Biologie
     * @bodyParam description string Beschreibung des Decks. Example: Prüfungsvorbereitung Kapitel 3
     * @bodyParam color string Farbe des Decks. Example: #22c55e
     *
     * @response 200 {
     *   "id": 1,
     *   "title": "Biologie",
     *   "description": "Prüfungsvorbereitung Kapitel 3",
     *   "color": "#22c55e",
     *   "created_at": "2026-04-14T10:00:00.000000Z",
     *   "updated_at": "2026-04-18T10:00:00.000000Z"
     * }
     */
    public function update(Request $request, Deck $deck)
    {
        abort_if($deck->user_id !== $request->user()->id, 403);

        $validated = $request->validate([
            'title' => 'required|string|max:120',
            'description' => 'sometimes|nullable|string|max:1000',
            'color' => 'sometimes|nullable|string|max:30',
        ]);

        $deck->update($validated);

        return response()->json($deck);
    }

    /**
     * Delete a deck
     *
     * Löscht ein Deck und alle dazugehörigen Karten.
     *
     * @group Decks
     * @authenticated
     *
     * @urlParam deck integer required Die ID des Decks. Example: 1
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Deck deleted"
     * }
     */
    public function destroy(Request $request, Deck $deck)
    {
        abort_if($deck->user_id !== $request->user()->id, 403);

        $deck->delete();

        return response()->json([
            'success' => true,
            'message' => 'Deck deleted',
        ]);
    }
}
