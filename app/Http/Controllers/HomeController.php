<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookLoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Display the home page
     */
    public function index()
    {
        // Get featured books (latest available books)
        $featuredBooks = Book::where('status', 'available')
            ->with('library')
            ->latest()
            ->take(10)
            ->get();

        // Get active loans for authenticated users
        $activeLoans = null;
        if (Auth::check()) {
            $activeLoans = BookLoan::where('user_id', Auth::id())
                ->where('status', 'active')
                ->with(['book', 'library'])
                ->orderBy('due_date', 'asc')
                ->take(3)
                ->get();
        }

        return view('home', compact('featuredBooks', 'activeLoans'));
    }
}

