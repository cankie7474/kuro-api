<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deck;
use Illuminate\Http\Request;

class DeckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Deck::all());
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
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
