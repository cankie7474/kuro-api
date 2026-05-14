<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\Deck;
use Illuminate\Http\Request;

class CardController extends Controller
{
    protected function ensureOwnedDeck(Request $request, Deck $deck):void {
        abort_if($deck->user_id !== $request->user()->id, 403);
    }

    protected function ensureCardBelongsToDeck(Deck $deck, Card $card):Card {
        return $deck->cards()->whereKey($card->id)->firstOrFail();
    }

    /**
     * Get all cards of a deck
     *
     * Returns all cards in a deck owned by the authenticated user.
     *
     * @group Cards
     * @authenticated
     *
     * @urlParam deck integer required The deck ID. Example: 1
     *
     * @response 200 [
     *   {
     *     "id": 1,
     *     "front": "What is Laravel?",
     *     "back": "A PHP framework",
     *     "due_at": null,
     *     "last_reviewed_at": null,
     *     "review_count": 0,
     *     "interval_days": 0,
     *     "deck_id": 1,
     *     "created_at": "2026-04-14T10:00:00.000000Z",
     *     "updated_at": "2026-04-14T10:00:00.000000Z"
     *   }
     * ]
     */
    public function index(Request $request, Deck $deck)
    {
        $this->ensureOwnedDeck($request, $deck);

        return response()->json($deck->cards()->latest()->get());
    }

    /**
     * Create a new card
     *
     * Creates a new card in a deck owned by the authenticated user.
     *
     * @group Cards
     * @authenticated
     *
     * @urlParam deck integer required The deck ID. Example: 1
     *
     * @bodyParam front string required The front side of the card. Example: What is PHP?
     * @bodyParam back string required The back side of the card. Example: A programming language
     *
     * @response 201 {
     *   "id": 1,
     *   "front": "What is PHP?",
     *   "back": "A programming language",
     *   "due_at": null,
     *   "last_reviewed_at": null,
     *   "review_count": 0,
     *   "interval_days": 0,
     *   "deck_id": 1,
     *   "created_at": "2026-04-14T10:00:00.000000Z",
     *   "updated_at": "2026-04-14T10:00:00.000000Z"
     * }
     */
    public function store(Request $request, Deck $deck)
    {
        $this->ensureOwnedDeck($request, $deck);

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
     * Updates a card in a deck owned by the authenticated user.
     *
     * @group Cards
     * @authenticated
     *
     * @urlParam deck integer required The deck ID. Example: 1
     * @urlParam card integer required The card ID. Example: 5
     *
     * @bodyParam front string required The front side of the card. Example: What is a controller?
     * @bodyParam back string required The back side of the card. Example: A class for handling requests
     *
     * @response 200 {
     *   "id": 5,
     *   "front": "What is a controller?",
     *   "back": "A class for handling requests",
     *   "due_at": null,
     *   "last_reviewed_at": null,
     *   "review_count": 0,
     *   "interval_days": 0,
     *   "deck_id": 1,
     *   "created_at": "2026-04-14T10:00:00.000000Z",
     *   "updated_at": "2026-04-18T10:00:00.000000Z"
     * }
     */
    public function update(Request $request, Deck $deck, Card $card)
    {
        $this->ensureOwnedDeck($request, $deck);
        $card = $this->ensureCardBelongsToDeck($deck, $card);

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
     * Deletes a card from a deck owned by the authenticated user.
     *
     * @group Cards
     * @authenticated
     *
     * @urlParam deck integer required The deck ID. Example: 1
     * @urlParam card integer required The card ID. Example: 5
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Card deleted"
     * }
     */
    public function destroy(Request $request, Deck $deck, Card $card)
    {
        $this->ensureOwnedDeck($request, $deck);
        $card = $this->ensureCardBelongsToDeck($deck, $card);

        $card->delete();

        return response()->json([
            'success' => true,
            'message' => 'Card deleted',
        ]);
    }
}
