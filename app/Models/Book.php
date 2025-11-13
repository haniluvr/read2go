<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'library_id',
        'isbn',
        'title',
        'author',
        'description',
        'cover_url',
        'status',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    /**
     * Get the library that owns the book.
     */
    public function library(): BelongsTo
    {
        return $this->belongsTo(Library::class);
    }

    /**
     * Get the book loans for the book.
     */
    public function bookLoans(): HasMany
    {
        return $this->hasMany(BookLoan::class);
    }
}
