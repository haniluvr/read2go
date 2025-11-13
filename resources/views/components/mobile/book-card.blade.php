@props(['book'])

<a href="{{ route('books.show', $book) }}" class="card p-3 block">
    <div class="aspect-[3/4] mb-2 overflow-hidden rounded-lg bg-primary-100">
        @if($book->cover_url)
            <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
        @else
            <div class="w-full h-full flex items-center justify-center text-primary-400">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        @endif
    </div>
    <h3 class="font-semibold text-sm text-primary-900 line-clamp-2 mb-1">{{ $book->title }}</h3>
    <p class="text-xs text-primary-600 mb-2">{{ $book->author }}</p>
    <div class="flex items-center justify-between">
        <span class="text-xs px-2 py-1 rounded-full {{ $book->status === 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
            {{ ucfirst($book->status) }}
        </span>
        @if($book->library)
            <span class="text-xs text-primary-500">{{ $book->library->name }}</span>
        @endif
    </div>
</a>

