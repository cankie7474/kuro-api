<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Card extends Model
{
    protected $fillable = [
        'front',
        'back',
        'due_at',
        'last_reviewed_at',
        'review_count',
        'interval_days',
    ];

    protected $casts = [
        'due_at' => 'datetime',
        'last_reviewed_at' => 'datetime',
    ];

    public function deck(): BelongsTo
    {
        return $this->belongsTo(Deck::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(CardReview::class);
    }
}
