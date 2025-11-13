<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BookLoan extends Model
{
    protected $fillable = [
        'user_id',
        'book_id',
        'library_id',
        'delivery_type',
        'delivery_address',
        'delivery_fee',
        'borrowed_at',
        'due_date',
        'returned_at',
        'return_method',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'borrowed_at' => 'datetime',
            'due_date' => 'date',
            'returned_at' => 'datetime',
            'delivery_fee' => 'decimal:2',
        ];
    }

    /**
     * Get the user that owns the loan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the book for the loan.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get the library for the loan.
     */
    public function library(): BelongsTo
    {
        return $this->belongsTo(Library::class);
    }

    /**
     * Get the penalties for the loan.
     */
    public function penalties(): HasMany
    {
        return $this->hasMany(Penalty::class, 'loan_id');
    }

    /**
     * Get the payments for the loan.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'loan_id');
    }

    /**
     * Get the delivery schedules for the loan.
     */
    public function deliverySchedules(): HasMany
    {
        return $this->hasMany(DeliverySchedule::class, 'loan_id');
    }

    /**
     * Check if the loan is overdue.
     */
    public function isOverdue(): bool
    {
        return $this->status === 'active' && 
               $this->due_date < now() && 
               $this->returned_at === null;
    }
}
