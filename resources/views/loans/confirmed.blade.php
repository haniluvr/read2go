@extends('layouts.mobile')

@section('content')
<div class="px-4 py-6">
    <!-- Success Icon -->
    <div class="flex flex-col items-center justify-center py-8">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mb-4">
            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-primary-900 mb-2">Order Confirmed!</h1>
        <p class="text-primary-600 text-center">Your book request has been confirmed</p>
    </div>

    <!-- Book Info -->
    <div class="card p-4 mb-6">
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
                <h2 class="font-bold text-primary-900 mb-1">{{ $loan->book->title }}</h2>
                <p class="text-sm text-primary-600">{{ $loan->book->author }}</p>
            </div>
        </div>
    </div>

    <!-- Delivery Information -->
    <div class="card p-4 mb-6">
        <h3 class="font-bold text-primary-900 mb-4">Delivery Information</h3>
        
        <div class="space-y-3">
            @if($loan->delivery_type === 'home')
                <div>
                    <p class="text-sm text-primary-600 mb-1">Delivery Method</p>
                    <p class="font-semibold text-primary-900">Home Delivery</p>
                </div>
                <div>
                    <p class="text-sm text-primary-600 mb-1">Delivery Address</p>
                    <p class="text-primary-900">{{ $loan->delivery_address }}</p>
                    <p class="text-sm text-primary-600">{{ $loan->user->barangay ?? '' }}</p>
                </div>
                <div class="pt-3 border-t border-primary-200">
                    <p class="text-sm text-primary-600 mb-1">Estimated Delivery Time</p>
                    <p class="font-bold text-lg text-primary-600">
                        {{ $estimatedDelivery->format('g:i A') }} 
                        <span class="text-sm font-normal text-primary-500">
                            ({{ $estimatedDelivery->diffForHumans() }})
                        </span>
                    </p>
                    <p class="text-xs text-primary-500 mt-1">
                        Your book will arrive within 1-3 hours
                    </p>
                </div>
            @else
                <div>
                    <p class="text-sm text-primary-600 mb-1">Pickup Method</p>
                    <p class="font-semibold text-primary-900">Library Pickup</p>
                </div>
                <div>
                    <p class="text-sm text-primary-600 mb-1">Pickup Location</p>
                    <p class="text-primary-900">{{ $loan->library->name }}</p>
                    <p class="text-sm text-primary-600">{{ $loan->library->address }}</p>
                </div>
                <div class="pt-3 border-t border-primary-200">
                    <p class="text-sm text-primary-600 mb-1">Ready for Pickup</p>
                    <p class="font-bold text-lg text-primary-600">
                        {{ $estimatedDelivery->format('g:i A') }} 
                        <span class="text-sm font-normal text-primary-500">
                            ({{ $estimatedDelivery->diffForHumans() }})
                        </span>
                    </p>
                    <p class="text-xs text-primary-500 mt-1">
                        Your book will be ready within 1-3 hours
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="space-y-3">
        <a href="{{ route('loans.show', $loan) }}" class="btn btn-primary w-full">
            View Loan Details
        </a>
        <a href="{{ route('books.index') }}" class="block text-center text-primary-600 text-sm">
            Browse More Books
        </a>
    </div>
</div>
@endsection

