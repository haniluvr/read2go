@extends('layouts.mobile')

@section('content')
<div class="px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-primary-900 mb-2">My Shelf</h1>
        <p class="text-sm text-primary-600">Manage your borrowed books</p>
    </div>

    @if($loans && $loans->count() > 0)
        <!-- Active Loans -->
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-primary-900 mb-4">Active Loans</h2>
            <div class="space-y-4">
                @foreach($loans->where('status', 'active') as $loan)
                    <x-mobile.loan-card :loan="$loan" />
                @endforeach
            </div>
        </div>

        <!-- Loan History -->
        @if($loans->where('status', '!=', 'active')->count() > 0)
            <div>
                <h2 class="text-lg font-semibold text-primary-900 mb-4">Loan History</h2>
                <div class="space-y-4">
                    @foreach($loans->where('status', '!=', 'active') as $loan)
                        <div class="card p-4">
                            <div class="flex gap-4">
                                <div class="w-16 h-24 flex-shrink-0 rounded-lg overflow-hidden bg-primary-100">
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
                                <div class="flex-1">
                                    <h3 class="font-semibold text-primary-900 mb-1">{{ $loan->book->title }}</h3>
                                    <p class="text-sm text-primary-600 mb-2">{{ $loan->book->author }}</p>
                                    <div class="flex items-center gap-2 text-xs text-primary-500">
                                        <span>Returned: {{ $loan->returned_at ? $loan->returned_at->format('M d, Y') : 'N/A' }}</span>
                                        <span class="inline-block px-2 py-0.5 rounded-full {{ $loan->status === 'returned' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                            {{ ucfirst($loan->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 mx-auto mb-4 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <h3 class="text-lg font-bold text-primary-900 mb-2">No loans yet</h3>
            <p class="text-sm text-primary-600 mb-4">Start borrowing books to see them here</p>
            <a href="{{ route('books.index') }}" class="btn btn-primary inline-block">
                Browse Books
            </a>
        </div>
    @endif
</div>
@endsection
