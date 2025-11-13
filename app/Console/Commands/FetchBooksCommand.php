<?php

namespace App\Console\Commands;

use App\Models\Library;
use App\Services\OpenLibraryService;
use App\Services\GoogleBooksService;
use Illuminate\Console\Command;

class FetchBooksCommand extends Command
{
    protected $signature = 'books:fetch {query=popular} {--limit=20}';
    protected $description = 'Fetch books from library APIs';

    public function handle(OpenLibraryService $openLibrary, GoogleBooksService $googleBooks)
    {
        $query = $this->argument('query');
        $limit = $this->option('limit');

        $this->info("Fetching books for query: {$query}");

        // Fetch from Open Library
        $this->info('Fetching from Open Library...');
        try {
            $openLibraryResults = $openLibrary->searchBooks($query, $limit);
            $this->info("Found " . count($openLibraryResults) . " books from API");
            $synced = 0;
            foreach ($openLibraryResults as $bookData) {
                if (empty($bookData['isbn'])) {
                    $this->warn("Skipping book without ISBN: " . ($bookData['title'] ?? 'Unknown'));
                    continue;
                }
                $book = $openLibrary->syncBookToDatabase($bookData);
                if ($book) {
                    $synced++;
                } else {
                    $this->warn("Failed to sync: " . ($bookData['title'] ?? 'Unknown'));
                }
            }
            $this->info("✓ Open Library: {$synced} books synced to database");
        } catch (\Exception $e) {
            $this->error('✗ Open Library error: ' . $e->getMessage());
        }

        // Fetch from Google Books
        $this->info('Fetching from Google Books...');
        try {
            $googleBooksResults = $googleBooks->searchBooks($query, $limit);
            $this->info("Found " . count($googleBooksResults) . " books from API");
            $synced = 0;
            foreach ($googleBooksResults as $bookData) {
                if (empty($bookData['isbn'])) {
                    $this->warn("Skipping book without ISBN: " . ($bookData['title'] ?? 'Unknown'));
                    continue;
                }
                $book = $googleBooks->syncBookToDatabase($bookData);
                if ($book) {
                    $synced++;
                } else {
                    $this->warn("Failed to sync: " . ($bookData['title'] ?? 'Unknown'));
                }
            }
            $this->info("✓ Google Books: {$synced} books synced to database");
        } catch (\Exception $e) {
            $this->error('✗ Google Books error: ' . $e->getMessage());
        }

        $this->info('Done! Books are now available in the database.');
    }
}

