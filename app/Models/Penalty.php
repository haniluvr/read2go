<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Penalty extends Model
{
    protected $fillable = [
        'loan_id',
        'user_id',
        'type',
        'amount',
        'reason',
        'is_paid',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'is_paid' => 'boolean',
        ];
    }

    /**
     * Get the loan that owns the penalty.
     */
    public function loan(): BelongsTo
    {
        return $this->belongsTo(BookLoan::class, 'loan_id');
    }

    /**
     * Get the user that owns the penalty.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the payment for the penalty.
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
