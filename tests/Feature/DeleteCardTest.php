<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

it('deletes a card from the given deck', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $deck = $user->decks()->create([
        'title' => 'Test Deck',
    ]);

    $card = $deck->cards()->create([
        'front' => 'Front',
        'back' => 'Back',
    ]);

    $this->deleteJson("/api/decks/{$deck->id}/cards/{$card->id}")
        ->assertOk()
        ->assertJson([
            'success' => true,
            'message' => 'Card deleted',
        ]);

    $this->assertDatabaseMissing('cards', [
        'id' => $card->id,
    ]);
});

it('returns 404 when the card does not belong to the given deck', function () {
    $user = User::factory()->create();
    Sanctum::actingAs($user);

    $deck = $user->decks()->create([
        'title' => 'Deck A',
    ]);

    $otherDeck = $user->decks()->create([
        'title' => 'Deck B',
    ]);

    $card = $otherDeck->cards()->create([
        'front' => 'Front',
        'back' => 'Back',
    ]);

    $this->deleteJson("/api/decks/{$deck->id}/cards/{$card->id}")
        ->assertNotFound();

    $this->assertDatabaseHas('cards', [
        'id' => $card->id,
    ]);
});
