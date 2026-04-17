<?php

use App\Models\Card;
use App\Models\Deck;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('deletes a card from the given deck', function () {
    $deck = Deck::create([
        'title' => 'Test Deck',
    ]);

    $card = Card::create([
        'deck_id' => $deck->id,
        'front' => 'Front',
        'back' => 'Back',
    ]);

    $this->deleteJson("/api/decks/{$deck->id}/cards/{$card->id}")
        ->assertNoContent();

    $this->assertDatabaseMissing('cards', [
        'id' => $card->id,
    ]);
});

it('returns 404 when the card does not belong to the given deck', function () {
    $deck = Deck::create([
        'title' => 'Deck A',
    ]);

    $otherDeck = Deck::create([
        'title' => 'Deck B',
    ]);

    $card = Card::create([
        'deck_id' => $otherDeck->id,
        'front' => 'Front',
        'back' => 'Back',
    ]);

    $this->deleteJson("/api/decks/{$deck->id}/cards/{$card->id}")
        ->assertNotFound();

    $this->assertDatabaseHas('cards', [
        'id' => $card->id,
    ]);
});
