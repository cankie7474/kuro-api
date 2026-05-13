<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Card;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $deckIds = $user->decks()->pluck('id');

        $deckCount = $deckIds->count();

        $cardCount = Card::whereIn('deck_id', $deckIds)->count();

        $latestDeck = $user->decks()->latest()->first();

        return response()->json([
            'deck_count' => $deckCount,
            'card_count' => $cardCount,
            'due_cards' => 0,
            'study_streak' => 0,
            'latest_deck' => $latestDeck ? [
                'id' => $latestDeck->id,
                'title' => $latestDeck->title,
                'description' => $latestDeck->description,
                'progress' => 0,
            ] : null,
        ]);
    }
}
