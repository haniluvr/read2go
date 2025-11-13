<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Library;
use App\Services\GoogleBooksService;
use App\Services\OpenLibraryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookController extends Controller
{
    protected $openLibraryService;
    protected $googleBooksService;

    public function __construct(OpenLibraryService $openLibraryService, GoogleBooksService $googleBooksService)
    {
        $this->openLibraryService = $openLibraryService;
        $this->googleBooksService = $googleBooksService;
    }

    /**
     * Display a listing of books
     */
    public function index(Request $request)
    {
        $query = $request->get('q');
        $libraryId = $request->get('library_id');

        $books = Book::with('library')
            ->where('status', 'available')
            ->when($query, function ($q) use ($query) {
                $q->where(function ($query) use ($q) {
                    $query->where('title', 'like', "%{$q}%")
                          ->orWhere('author', 'like', "%{$q}%")
                          ->orWhere('isbn', 'like', "%{$q}%");
                });
            })
            ->when($libraryId, function ($q) use ($libraryId) {
                $q->where('library_id', $libraryId);
            })
            ->paginate(20);

        $libraries = Library::all();

        return view('books.index', compact('books', 'libraries', 'query', 'libraryId'));
    }

    /**
     * Search books from APIs and sync to database
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2',
        ]);

        $query = $request->get('q');
        $results = [];

        // Search Open Library
        $openLibraryResults = $this->openLibraryService->searchBooks($query, 10);
        foreach ($openLibraryResults as $bookData) {
            $book = $this->openLibraryService->syncBookToDatabase($bookData);
            if ($book) {
                $results[] = $book->load('library');
            }
        }

        // Search Google Books
        $googleBooksResults = $this->googleBooksService->searchBooks($query, 10);
        foreach ($googleBooksResults as $bookData) {
            $book = $this->googleBooksService->syncBookToDatabase($bookData);
            if ($book) {
                $results[] = $book->load('library');
            }
        }

        return response()->json([
            'success' => true,
            'books' => $results,
        ]);
    }

    /**
     * Display the specified book
     */
    public function show(Book $book)
    {
        $book->load('library');
        return view('books.show', compact('book'));
    }
}
