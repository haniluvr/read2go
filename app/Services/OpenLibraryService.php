<?php

namespace App\Services;

use App\Models\Book;
use App\Models\Library;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenLibraryService
{
    protected $baseUrl = 'https://openlibrary.org';
    protected $library;

    public function __construct()
    {
        $this->library = Library::where('api_type', 'open_library')->first();
    }

    /**
     * Search for books in Open Library
     */
    public function searchBooks(string $query, int $limit = 20): array
    {
        try {
            $response = Http::timeout(30) // Increased timeout for Open Library
                ->withoutVerifying() // Disable SSL verification for development (XAMPP)
                ->withHeaders([
                    'User-Agent' => 'Read2Go/1.0 (contact: read2go@example.com)'
                ])
                ->get("{$this->baseUrl}/search.json", [
                    'q' => $query,
                    'limit' => $limit,
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $docs = $data['docs'] ?? [];
                Log::info('Open Library API: Found ' . count($docs) . ' documents');
                
                if (count($docs) > 0) {
                    $formatted = $this->formatBooks($docs);
                    Log::info('Open Library API: Formatted ' . count($formatted) . ' books (after ISBN filtering)');
                    return $formatted;
                }
                
                Log::warning('Open Library API: No documents in response');
                return [];
            }

            Log::warning('Open Library API: Request failed with status ' . $response->status());
            Log::warning('Open Library API: Response body: ' . substr($response->body(), 0, 200));
            return [];
        } catch (\Exception $e) {
            Log::error('Open Library API Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get book details by ISBN
     */
    public function getBookByIsbn(string $isbn): ?array
    {
        try {
            $response = Http::timeout(10)
                ->withoutVerifying() // Disable SSL verification for development (XAMPP)
                ->get("{$this->baseUrl}/api/books", [
                    'bibkeys' => "ISBN:{$isbn}",
                    'format' => 'json',
                    'jscmd' => 'data',
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $key = "ISBN:{$isbn}";
                if (isset($data[$key])) {
                    return $this->formatSingleBook($data[$key], $isbn);
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Open Library API Error: ' . $e->getMessage());
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
    protected function formatBooks(array $docs): array
    {
        $formatted = [];
        foreach ($docs as $doc) {
            // Try multiple ways to get ISBN
            $isbn = null;
            
            // Check if isbn is an array
            if (isset($doc['isbn']) && is_array($doc['isbn']) && !empty($doc['isbn'])) {
                $isbn = $doc['isbn'][0];
            } elseif (isset($doc['isbn_13']) && is_array($doc['isbn_13']) && !empty($doc['isbn_13'])) {
                $isbn = $doc['isbn_13'][0];
            } elseif (isset($doc['isbn_10']) && is_array($doc['isbn_10']) && !empty($doc['isbn_10'])) {
                $isbn = $doc['isbn_10'][0];
            } elseif (isset($doc['isbn']) && is_string($doc['isbn'])) {
                $isbn = $doc['isbn'];
            }
            
            // Skip books without ISBN as they're required for syncing
            if (!$isbn) {
                continue;
            }

            $formatted[] = [
                'isbn' => $isbn,
                'title' => $doc['title'] ?? 'Unknown Title',
                'author' => isset($doc['author_name']) && is_array($doc['author_name']) 
                    ? ($doc['author_name'][0] ?? 'Unknown Author')
                    : ($doc['author_name'] ?? 'Unknown Author'),
                'description' => is_array($doc['first_sentence'] ?? null) 
                    ? ($doc['first_sentence'][0] ?? null)
                    : ($doc['first_sentence'] ?? null),
                'cover_url' => $this->getCoverUrl($doc['cover_i'] ?? null, $isbn),
                'metadata' => $doc,
            ];
        }
        return $formatted;
    }

    /**
     * Format single book from API response
     */
    protected function formatSingleBook(array $data, string $isbn): array
    {
        return [
            'isbn' => $isbn,
            'title' => $data['title'] ?? 'Unknown Title',
            'author' => $data['authors'][0]['name'] ?? 'Unknown Author',
            'description' => null,
            'cover_url' => $this->getCoverUrl(null, $isbn),
            'metadata' => $data,
        ];
    }

    /**
     * Get cover image URL
     */
    protected function getCoverUrl(?int $coverId, ?string $isbn): ?string
    {
        if ($coverId) {
            return "https://covers.openlibrary.org/b/id/{$coverId}-L.jpg";
        }
        if ($isbn) {
            return "https://covers.openlibrary.org/b/isbn/{$isbn}-L.jpg";
        }
        return null;
    }
}

