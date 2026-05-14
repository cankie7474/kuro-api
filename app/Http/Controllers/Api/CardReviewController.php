<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\CardReview;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CardReviewController extends Controller
{
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
