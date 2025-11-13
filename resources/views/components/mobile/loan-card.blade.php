@props(['loan'])

<div class="card p-4 mb-4">
    <div class="flex gap-4">
        <div class="w-20 h-28 flex-shrink-0 rounded-lg overflow-hidden bg-primary-100">
            @if($loan->book->cover_url)
                <img src="{{ $loan->book->cover_url }}" alt="{{ $loan->book->title }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center text-primary-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            @endif
        </div>
        <div class="flex-1">
            <h3 class="font-semibold text-primary-900 mb-1">{{ $loan->book->title }}</h3>
            <p class="text-sm text-primary-600 mb-2">{{ $loan->book->author }}</p>
            <div class="space-y-1 mb-3">
                <div class="flex items-center text-xs text-primary-500">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Due: {{ $loan->due_date->format('M d, Y') }}
                </div>
                @if($loan->isOverdue())
                    <span class="inline-block text-xs px-2 py-1 rounded-full bg-red-100 text-red-700">
                        Overdue
                    </span>
                @else
                    <span class="inline-block text-xs px-2 py-1 rounded-full bg-green-100 text-green-700">
                        Active
                    </span>
                @endif
            </div>
            <a href="{{ route('returns.create', $loan) }}" class="btn btn-primary text-sm py-2 px-4 inline-block">
                Return Book
            </a>
        </div>
    </div>
</div>

