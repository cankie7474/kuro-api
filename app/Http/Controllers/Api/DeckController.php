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
     * Returns all decks owned by the authenticated user.
     *
     * @group Decks
     * @authenticated
     *
     * @response 200 [
     *   {
     *     "id": 1,
     *     "title": "Math",
     *     "description": "Practice formulas",
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
     * Creates a new deck for the authenticated user.
     *
     * @group Decks
     * @authenticated
     *
     * @bodyParam title string required The deck title. Example: English vocabulary
     * @bodyParam description string The deck description. Example: Practice for the exam
     * @bodyParam color string The deck color. Example: #098588
     *
     * @response 201 {
     *   "id": 1,
     *   "title": "English vocabulary",
     *   "description": "Practice for the exam",
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
     * Returns a single deck owned by the authenticated user.
     *
     * @group Decks
     * @authenticated
     *
     * @urlParam deck integer required The deck ID. Example: 1
     *
     * @response 200 {
     *   "id": 1,
     *   "title": "Math",
     *   "description": "Practice formulas",
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
     * Updates a deck owned by the authenticated user.
     *
     * @group Decks
     * @authenticated
     *
     * @urlParam deck integer required The deck ID. Example: 1
     *
     * @bodyParam title string required The deck title. Example: Biology
     * @bodyParam description string The deck description. Example: Chapter 3 exam prep
     * @bodyParam color string The deck color. Example: #22c55e
     *
     * @response 200 {
     *   "id": 1,
     *   "title": "Biology",
     *   "description": "Chapter 3 exam prep",
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
     * Deletes a deck owned by the authenticated user.
     *
     * @group Decks
     * @authenticated
     *
     * @urlParam deck integer required The deck ID. Example: 1
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
