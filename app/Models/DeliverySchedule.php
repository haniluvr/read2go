<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliverySchedule extends Model
{
    protected $fillable = [
        'loan_id',
        'pickup_type',
        'scheduled_at',
        'rider_id',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
        ];
    }

    /**
     * Get the loan that owns the delivery schedule.
     */
    public function loan(): BelongsTo
    {
        return $this->belongsTo(BookLoan::class, 'loan_id');
    }

    /**
     * Get the rider for the delivery schedule.
     */
    public function rider(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rider_id');
    }
}
