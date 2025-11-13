<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Library;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleBooksService
{
    protected $baseUrl = 'https://www.googleapis.com/books/v1';
    protected $apiKey;
    protected $library;

    public function __construct()
    {
        $this->apiKey = config('services.google_books.api_key', env('GOOGLE_BOOKS_API_KEY'));
        $this->library = Library::where('api_type', 'google_books')->first();
    }

    /**
     * Search for books in Google Books
     */
    public function searchBooks(string $query, int $maxResults = 20): array
    {
        try {
            $params = [
                'q' => $query,
                'maxResults' => $maxResults,
            ];

            if ($this->apiKey) {
                $params['key'] = $this->apiKey;
            }

            $response = Http::timeout(10)
                ->withoutVerifying() // Disable SSL verification for development (XAMPP)
                ->get("{$this->baseUrl}/volumes", $params);

            if ($response->successful()) {
                $data = $response->json();
                return $this->formatBooks($data['items'] ?? []);
            }

            return [];
        } catch (\Exception $e) {
            Log::error('Google Books API Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get book details by ISBN
     */
    public function getBookByIsbn(string $isbn): ?array
    {
        try {
            $params = [
                'q' => "isbn:{$isbn}",
            ];

            if ($this->apiKey) {
                $params['key'] = $this->apiKey;
            }

            $response = Http::timeout(10)
                ->withoutVerifying() // Disable SSL verification for development (XAMPP)
                ->get("{$this->baseUrl}/volumes", $params);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['items'][0])) {
                    return $this->formatSingleBook($data['items'][0]);
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Google Books API Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Sync book to database
     */
    public function syncBookToDatabase(array $bookData): ?Book
    {
        if (!$this->library) {
            return null;
        }

        $isbn = $bookData['isbn'] ?? null;
        if (!$isbn) {
            return null;
        }

        // Check if book already exists
        $book = Book::where('library_id', $this->library->id)
            ->where('isbn', $isbn)
            ->first();

        if ($book) {
            // Update existing book
            $book->update([
                'title' => $bookData['title'] ?? 'Unknown Title',
                'author' => $bookData['author'] ?? null,
                'description' => $bookData['description'] ?? null,
                'cover_url' => $bookData['cover_url'] ?? null,
                'metadata' => $bookData['metadata'] ?? null,
            ]);
            return $book;
        }

        // Create new book
        return Book::create([
            'library_id' => $this->library->id,
            'isbn' => $isbn,
            'title' => $bookData['title'] ?? 'Unknown Title',
            'author' => $bookData['author'] ?? null,
            'description' => $bookData['description'] ?? null,
            'cover_url' => $bookData['cover_url'] ?? null,
            'status' => 'available',
            'metadata' => $bookData['metadata'] ?? null,
        ]);
    }

    /**
     * Format books from API response
     */
    protected function formatBooks(array $items): array
    {
        $formatted = [];
        foreach ($items as $item) {
            $volumeInfo = $item['volumeInfo'] ?? [];
            $isbn = $this->extractIsbn($volumeInfo);
            
            if (!$isbn) {
                continue;
            }

            $formatted[] = [
                'isbn' => $isbn,
                'title' => $volumeInfo['title'] ?? 'Unknown Title',
                'author' => $volumeInfo['authors'][0] ?? 'Unknown Author',
                'description' => $volumeInfo['description'] ?? null,
                'cover_url' => $volumeInfo['imageLinks']['thumbnail'] ?? 
                              $volumeInfo['imageLinks']['smallThumbnail'] ?? null,
                'metadata' => $item,
            ];
        }
        return $formatted;
    }

    /**
     * Format single book from API response
     */
    protected function formatSingleBook(array $item): array
    {
        $volumeInfo = $item['volumeInfo'] ?? [];
        $isbn = $this->extractIsbn($volumeInfo);

        return [
            'isbn' => $isbn,
            'title' => $volumeInfo['title'] ?? 'Unknown Title',
            'author' => $volumeInfo['authors'][0] ?? 'Unknown Author',
            'description' => $volumeInfo['description'] ?? null,
            'cover_url' => $volumeInfo['imageLinks']['thumbnail'] ?? 
                          $volumeInfo['imageLinks']['smallThumbnail'] ?? null,
            'metadata' => $item,
        ];
    }

    /**
     * Extract ISBN from volume info
     */
    protected function extractIsbn(array $volumeInfo): ?string
    {
        $identifiers = $volumeInfo['industryIdentifiers'] ?? [];
        foreach ($identifiers as $identifier) {
            if ($identifier['type'] === 'ISBN_13') {
                return $identifier['identifier'];
            }
        }
        foreach ($identifiers as $identifier) {
            if ($identifier['type'] === 'ISBN_10') {
                return $identifier['identifier'];
            }
        }
        return null;
    }
}

