<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deck;
use Illuminate\Http\Request;

class StudyController extends Controller
{
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
