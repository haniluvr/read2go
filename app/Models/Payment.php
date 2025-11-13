<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'loan_id',
        'penalty_id',
        'amount',
        'currency',
        'xendit_payment_id',
        'status',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    /**
     * Get the user that owns the payment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the loan for the payment.
     */
    public function loan(): BelongsTo
    {
        return $this->belongsTo(BookLoan::class, 'loan_id');
    }

    /**
     * Get the penalty for the payment.
     */
    public function penalty(): BelongsTo
    {
        return $this->belongsTo(Penalty::class);
    }
}
