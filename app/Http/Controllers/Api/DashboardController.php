<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Models\CardReview;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Get dashboard summary
     *
     * Returns summary statistics for the authenticated user's decks and cards.
     *
     * @group Dashboard
     *
     * @authenticated
     *
     * @response 200 {
     *   "deck_count": 3,
     *   "card_count": 42,
     *   "due_cards": 0,
     *   "study_streak": 0,
     *   "latest_deck": {
     *     "id": 1,
     *     "title": "Math",
     *     "description": "Practice formulas",
     *     "progress": 0
     *   }
     * }
     */
    public function index(Request $request)
    {
        $user = $request->user();

        $deckIds = $user->decks()->pluck('id');

        $deckCount = $deckIds->count();

        $cardCount = Card::whereIn('deck_id', $deckIds)->count();
        $dueCards = Card::whereIn('deck_id', $deckIds)
            ->where(function ($query) {
                $query->whereNull('due_at')
                    ->orWhere('due_at', '<=', now());
            })
            ->count();

        $latestDeck = $user->decks()->latest()->first();
        $latestDeckProgress = $latestDeck
            ? $this->deckProgress($latestDeck->id)
            : 0;

        return response()->json([
            'deck_count' => $deckCount,
            'card_count' => $cardCount,
            'due_cards' => $dueCards,
            'study_streak' => $this->studyStreak($user->id),
            'latest_deck' => $latestDeck ? [
                'id' => $latestDeck->id,
                'title' => $latestDeck->title,
                'description' => $latestDeck->description,
                'progress' => $latestDeckProgress,
            ] : null,
        ]);
    }

    private function deckProgress(int $deckId): int
    {
        $totalCards = Card::where('deck_id', $deckId)->count();

        if ($totalCards === 0) {
            return 0;
        }

        $reviewedCards = Card::where('deck_id', $deckId)
            ->where('review_count', '>', 0)
            ->count();

        return (int) round(($reviewedCards / $totalCards) * 100);
    }

    private function studyStreak(int $userId): int
    {
        $streak = 0;
        $date = now()->startOfDay();

        while ($streak < 365) {
            $hasReview = CardReview::where('user_id', $userId)
                ->whereDate('created_at', $date)
                ->exists();

            if (! $hasReview) {
                break;
            }

            $streak++;
            $date->subDay();
        }

        return $streak;
    }
}
