@extends('layouts.mobile')

@section('content')
    <!-- Hero Section -->
    <div class="bg-primary-600 text-white px-4 py-12">
        <div class="max-w-md mx-auto text-center">
            <h1 class="text-3xl font-bold mb-4 text-white">Your Library, Delivered</h1>
            <p class="text-white mb-6 opacity-95">Discover and borrow books from Quezon City libraries, delivered right to your doorstep.</p>
            
            <!-- Search Bar -->
            <form action="{{ route('books.index') }}" method="GET" class="mb-4">
                <div class="flex gap-2">
                    <input 
                        type="text" 
                        name="q" 
                        placeholder="Search books, authors, ISBN..." 
                        class="flex-1 px-4 py-3 rounded-lg text-primary-900 placeholder-primary-500 bg-white focus:outline-none focus:ring-2 focus:ring-white/50"
                        value="{{ request('q') }}"
                    >
                    <button type="submit" class="bg-white text-primary-600 px-6 py-3 rounded-lg font-semibold hover:bg-primary-50 transition">
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Active Loans Summary (if authenticated) -->
    @auth
        @if($activeLoans && $activeLoans->count() > 0)
            <div class="px-4 py-6 bg-white border-b border-primary-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-primary-900">My Active Loans</h2>
                    <a href="{{ route('loans.index') }}" class="text-sm text-primary-600 font-medium">View All</a>
                </div>
                <div class="space-y-3">
                    @foreach($activeLoans as $loan)
                        <div class="flex items-center gap-3 p-3 bg-primary-50 rounded-lg">
                            <div class="w-12 h-16 flex-shrink-0 rounded overflow-hidden bg-primary-100">
                                @if($loan->book->cover_url)
                                    <img src="{{ $loan->book->cover_url }}" alt="{{ $loan->book->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-primary-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-sm text-primary-900 truncate">{{ $loan->book->title }}</h3>
                                <p class="text-xs text-primary-600 mb-1">Due: {{ $loan->due_date->format('M d, Y') }}</p>
                                @if($loan->isOverdue())
                                    <span class="inline-block text-xs px-2 py-0.5 rounded-full bg-red-100 text-red-700">Overdue</span>
                                @else
                                    <span class="inline-block text-xs px-2 py-0.5 rounded-full bg-green-100 text-green-700">
                                        {{ $loan->due_date->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endauth

    <!-- Featured Books -->
    <div class="px-4 py-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold text-primary-900">Featured Books</h2>
            <a href="{{ route('books.index') }}" class="text-sm text-primary-600 font-medium">See All</a>
        </div>
        
        @if($featuredBooks->count() > 0)
            <div class="grid grid-cols-2 gap-4">
                @foreach($featuredBooks->take(6) as $book)
                    <x-mobile.book-card :book="$book" />
                @endforeach
            </div>
        @else
            <div class="text-center py-12 text-primary-500">
                <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <p>No books available at the moment.</p>
            </div>
        @endif
    </div>

    <!-- Recommendations Section -->
    <div class="px-4 py-6 bg-white">
        <h2 class="text-lg font-bold text-primary-900 mb-4">Recommendations</h2>
        @if($featuredBooks->count() > 6)
            <div class="grid grid-cols-2 gap-4">
                @foreach($featuredBooks->skip(6)->take(4) as $book)
                    <x-mobile.book-card :book="$book" />
                @endforeach
            </div>
        @else
            <p class="text-sm text-primary-500 text-center py-4">More recommendations coming soon!</p>
        @endif
    </div>
@endsection

