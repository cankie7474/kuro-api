<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Card extends Model
{
    protected $fillable = [
        'deck_id',
        'front',
        'back',
    ];

    public function deck(): BelongsTo{
        return $this->belongsTo(Deck::class);
    }
}
