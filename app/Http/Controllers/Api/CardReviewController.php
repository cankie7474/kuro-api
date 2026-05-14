<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\CardReview;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CardReviewController extends Controller
{
    /**
     * Review a card
     *
     * Stores a review result for a card and schedules the next due date.
     *
     * Ratings map to intervals as follows: again = 10 minutes, hard = 1 day,
     * good = 3 days, easy = 7 days.
     *
     * @group Study
     * @authenticated
     *
     * @urlParam card integer required The card ID. Example: 5
     * @bodyParam rating string required Review rating. Must be one of again, hard, good, easy. Example: good
     *
     * @response 200 {
     *   "card": {
     *     "id": 5,
     *     "front": "What is a controller?",
     *     "back": "A class for handling requests",
     *     "due_at": "2026-05-17T10:00:00.000000Z",
     *     "last_reviewed_at": "2026-05-14T10:00:00.000000Z",
     *     "review_count": 1,
     *     "interval_days": 3,
     *     "deck_id": 1,
     *     "created_at": "2026-05-14T09:00:00.000000Z",
     *     "updated_at": "2026-05-14T10:00:00.000000Z"
     *   },
     *   "rating": "good"
     * }
     */
    public function store(Request $request, Card $card)
    {
        $user = $request->user();
        $card->load('deck');

        abort_if($card->deck->user_id !== $user->id, 403);

        $validated = $request->validate([
            'rating' => ['required', Rule::in(['again', 'hard', 'good', 'easy'])],
        ]);

        $rating = $validated['rating'];
        $intervalDays = $this->intervalDaysForRating($rating);

        CardReview::create([
            'user_id' => $user->id,
            'card_id' => $card->id,
            'rating' => $rating,
        ]);

        $card->update([
            'due_at' => $rating === 'again'
                ? now()->addMinutes(10)
                : now()->addDays($intervalDays),
            'last_reviewed_at' => now(),
            'review_count' => $card->review_count + 1,
            'interval_days' => $intervalDays,
        ]);

        return response()->json([
            'card' => $card->fresh(),
            'rating' => $rating,
        ]);
    }

    private function intervalDaysForRating(string $rating): int
    {
        return match ($rating) {
            'again' => 0,
            'hard' => 1,
            'good' => 3,
            'easy' => 7,
        };
    }
}
