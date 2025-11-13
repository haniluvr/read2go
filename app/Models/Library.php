<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Library extends Model
{
    protected $fillable = [
        'name',
        'api_type',
        'api_endpoint',
        'address',
        'latitude',
        'longitude',
        'contact_info',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    /**
     * Get the books for the library.
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    /**
     * Get the book loans for the library.
     */
    public function bookLoans(): HasMany
    {
        return $this->hasMany(BookLoan::class);
    }
}
