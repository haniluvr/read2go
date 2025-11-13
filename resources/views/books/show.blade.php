@extends('layouts.mobile')

@section('content')
<div class="px-4 py-6">
    <!-- Back Button -->
    <a href="{{ route('books.index') }}" class="inline-flex items-center text-primary-600 mb-4">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back
    </a>

    <!-- Book Cover -->
    <div class="mb-6">
        <div class="aspect-[3/4] max-w-xs mx-auto rounded-lg overflow-hidden bg-primary-100">
            @if($book->cover_url)
                <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center text-primary-400">
                    <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            @endif
        </div>
    </div>

    <!-- Book Info -->
    <div class="space-y-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-primary-900 mb-2">{{ $book->title }}</h1>
            @if($book->author)
                <p class="text-lg text-primary-600">by {{ $book->author }}</p>
            @endif
        </div>

        <div class="flex items-center gap-3">
            <span class="inline-block text-sm px-3 py-1 rounded-full {{ $book->status === 'available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ ucfirst($book->status) }}
            </span>
            @if($book->isbn)
                <span class="text-sm text-primary-500">ISBN: {{ $book->isbn }}</span>
            @endif
        </div>
    </div>

    <!-- Borrow Button -->
    @if($book->status === 'available')
        @auth
            <a href="{{ route('loans.create', $book) }}" class="btn btn-primary w-full mb-6 text-center block">
                Borrow This Book
            </a>
        @else
            <a href="{{ route('login') }}" class="btn btn-primary w-full mb-6 text-center block">
                Login to Borrow
            </a>
        @endauth
    @else
        <button disabled class="btn w-full bg-gray-300 text-gray-600 cursor-not-allowed mb-6">
            Currently Unavailable
        </button>
    @endif

    <!-- Description -->
    @if($book->description)
        <div class="card p-4 mb-4">
            <h2 class="text-lg font-bold text-primary-900 mb-3">Description</h2>
            <p class="text-sm text-primary-700 leading-relaxed">{{ $book->description }}</p>
        </div>
    @endif

    <!-- Library Information -->
    @if($book->library)
        <div class="card p-4 mb-4">
            <h2 class="text-lg font-bold text-primary-900 mb-3">Library Information</h2>
            <div class="space-y-2 text-sm">
                <p class="text-primary-600">
                    <span class="font-medium">Name:</span> {{ $book->library->name }}
                </p>
                @if($book->library->address)
                    <p class="text-primary-600">
                        <span class="font-medium">Address:</span> {{ $book->library->address }}
                    </p>
                @endif
                @if($book->library->contact_info)
                    <p class="text-primary-600">
                        <span class="font-medium">Contact:</span> {{ $book->library->contact_info }}
                    </p>
                @endif
            </div>
        </div>
    @endif

    <!-- Additional Information -->
    @if($book->metadata && count($book->metadata) > 0)
        @php
            // Extract readable information from metadata
            $readableInfo = [];
            
            // Handle Google Books format
            if (isset($book->metadata['volumeInfo'])) {
                $volumeInfo = $book->metadata['volumeInfo'];
                
                if (isset($volumeInfo['publishedDate'])) {
                    $readableInfo['Published Date'] = $volumeInfo['publishedDate'];
                }
                if (isset($volumeInfo['publisher'])) {
                    $readableInfo['Publisher'] = $volumeInfo['publisher'];
                }
                if (isset($volumeInfo['pageCount'])) {
                    $readableInfo['Pages'] = $volumeInfo['pageCount'];
                }
                if (isset($volumeInfo['language'])) {
                    $readableInfo['Language'] = strtoupper($volumeInfo['language']);
                }
                if (isset($volumeInfo['categories']) && is_array($volumeInfo['categories'])) {
                    $readableInfo['Categories'] = implode(', ', array_slice($volumeInfo['categories'], 0, 3));
                }
                if (isset($volumeInfo['averageRating'])) {
                    $readableInfo['Rating'] = $volumeInfo['averageRating'] . '/5.0';
                }
            }
            
            // Handle Open Library format
            if (isset($book->metadata['publish_date'])) {
                $readableInfo['Published Date'] = is_array($book->metadata['publish_date']) 
                    ? $book->metadata['publish_date'][0] 
                    : $book->metadata['publish_date'];
            }
            if (isset($book->metadata['publisher'])) {
                $readableInfo['Publisher'] = is_array($book->metadata['publisher']) 
                    ? $book->metadata['publisher'][0] 
                    : $book->metadata['publisher'];
            }
            if (isset($book->metadata['number_of_pages'])) {
                $readableInfo['Pages'] = $book->metadata['number_of_pages'];
            }
            if (isset($book->metadata['language'])) {
                $readableInfo['Language'] = is_array($book->metadata['language']) 
                    ? strtoupper($book->metadata['language'][0]) 
                    : strtoupper($book->metadata['language']);
            }
            if (isset($book->metadata['subject']) && is_array($book->metadata['subject'])) {
                $readableInfo['Subjects'] = implode(', ', array_slice($book->metadata['subject'], 0, 3));
            }
        @endphp
        
        @if(count($readableInfo) > 0)
            <div class="card p-4">
                <h2 class="text-lg font-bold text-primary-900 mb-3">Additional Information</h2>
                <div class="space-y-2 text-sm">
                    @foreach($readableInfo as $label => $value)
                        <div>
                            <span class="font-medium text-primary-700">{{ $label }}:</span>
                            <span class="text-primary-600">{{ $value }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endif
</div>
@endsection
