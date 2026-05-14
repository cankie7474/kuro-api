<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deck;
use Illuminate\Http\Request;

class StudyController extends Controller
{
    /**
     * Get due study cards
     *
     * Returns all cards in a deck that are due for study. Cards are due when
     * they have no due date yet or their due date is in the past.
     *
     * @group Study
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
     *     "created_at": "2026-05-14T10:00:00.000000Z",
     *     "updated_at": "2026-05-14T10:00:00.000000Z"
     *   }
     * ]
     */
    public function index(Request $request, Deck $deck)
    {
        abort_if($deck->user_id !== $request->user()->id, 403);

        $cards = $deck->cards()
            ->where(function ($query) {
                $query->whereNull('due_at')
                    ->orWhere('due_at', '<=', now());
            })
            ->latest()
            ->get();

        return response()->json($cards);
    }
}
