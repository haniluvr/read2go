@extends('layouts.mobile')

@section('content')
<div class="px-4 py-6">
    <!-- Search Bar -->
    <div class="mb-6">
        <form action="{{ route('books.index') }}" method="GET" class="space-y-3">
            <div class="flex gap-2">
                <input 
                    type="text" 
                    name="q" 
                    value="{{ request('q') }}" 
                    placeholder="Search books, authors, ISBN..." 
                    class="flex-1 px-4 py-3 rounded-lg border border-primary-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                >
                <button type="submit" class="btn btn-primary px-6">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Library Filter -->
            <select name="library_id" class="w-full px-4 py-3 rounded-lg border border-primary-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                <option value="">All Libraries</option>
                @foreach($libraries ?? [] as $library)
                    <option value="{{ $library->id }}" {{ request('library_id') == $library->id ? 'selected' : '' }}>
                        {{ $library->name }}
                    </option>
                @endforeach
            </select>
            
            @if(request('q') || request('library_id'))
                <a href="{{ route('books.index') }}" class="text-sm text-primary-600 hover:text-primary-700">
                    Clear filters
                </a>
            @endif
        </form>
    </div>

    <!-- Results Count -->
    @if(isset($books) && $books->total() > 0)
        <p class="text-sm text-primary-600 mb-4">
            Found {{ $books->total() }} {{ Str::plural('book', $books->total()) }}
        </p>
    @endif

    <!-- Books Grid -->
    @if(isset($books) && $books->count() > 0)
        <div class="grid grid-cols-2 gap-4 mb-6">
            @foreach($books as $book)
                <x-mobile.book-card :book="$book" />
            @endforeach
        </div>

        <!-- Pagination -->
        @if($books->hasPages())
            <div class="mt-6">
                {{ $books->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 mx-auto mb-4 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <h3 class="text-lg font-bold text-primary-900 mb-2">No books found</h3>
            <p class="text-sm text-primary-600">Try adjusting your search or filters</p>
        </div>
    @endif
</div>
@endsection
