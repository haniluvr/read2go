<?php

namespace Database\Seeders;

use App\Models\Library;
use Illuminate\Database\Seeder;

class LibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Library::create([
            'name' => 'Open Library',
            'api_type' => 'open_library',
            'api_endpoint' => 'https://openlibrary.org',
            'address' => 'Quezon City, Philippines',
            'latitude' => 14.6760,
            'longitude' => 121.0437,
            'contact_info' => 'Open Library API - Free Public Domain Books',
        ]);

        Library::create([
            'name' => 'Google Books',
            'api_type' => 'google_books',
            'api_endpoint' => 'https://www.googleapis.com/books/v1',
            'address' => 'Quezon City, Philippines',
            'latitude' => 14.6760,
            'longitude' => 121.0437,
            'contact_info' => 'Google Books API - Comprehensive Book Database',
        ]);
    }
}
