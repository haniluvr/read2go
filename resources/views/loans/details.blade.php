@extends('layouts.mobile')

@section('content')
<div class="px-4 py-6">
    <!-- Book Info -->
    <div class="card p-5 mb-5">
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
                <h2 class="font-bold text-primary-900 mb-2">{{ $loan->book->title }}</h2>
                <p class="text-sm text-primary-600 mb-1">{{ $loan->book->author }}</p>
                <p class="text-xs text-primary-500">{{ $loan->library->name }}</p>
            </div>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="card p-5 mb-6">
        <h3 class="font-bold text-primary-900 mb-5 text-lg">Order Summary</h3>
        
        <div class="space-y-4">
            <!-- Delivery Method -->
            <div class="flex justify-between items-center pb-3 border-b border-primary-200">
                <span class="text-primary-700">Delivery Method</span>
                <span class="font-semibold text-primary-900">
                    {{ $loan->delivery_type === 'home' ? 'Home Delivery' : 'Pickup' }}
                </span>
            </div>

            @if($loan->delivery_type === 'home')
                <!-- Delivery Address -->
                <div class="pb-3 border-b border-primary-200">
                    <p class="text-sm text-primary-600 mb-2">Delivery Address</p>
                    <p class="text-primary-900 font-medium mb-1">{{ $loan->delivery_address }}</p>
                    <p class="text-sm text-primary-600">{{ $loan->user->barangay ?? '' }}</p>
                </div>
            @else
                <!-- Pickup Location -->
                <div class="pb-3 border-b border-primary-200">
                    <p class="text-sm text-primary-600 mb-2">Pickup Location</p>
                    <p class="text-primary-900 font-medium mb-1">{{ $loan->library->name }}</p>
                    <p class="text-sm text-primary-600">{{ $loan->library->address }}</p>
                </div>
            @endif

            <!-- Delivery Fee -->
            <div class="pb-3 border-b border-primary-200">
                <div class="flex justify-between items-center mb-1">
                    <span class="text-primary-700">Delivery Fee</span>
                    <span class="font-semibold text-primary-900">
                        @if($loan->delivery_fee > 0)
                            ₱{{ number_format($loan->delivery_fee, 2) }}
                        @else
                            Free
                        @endif
                    </span>
                </div>
                @if($loan->delivery_type === 'home' && $loan->delivery_fee > 0)
                    <p class="text-xs text-primary-500 mt-1">Distance-based fee calculated</p>
                @endif
            </div>

            <!-- Total -->
            <div class="pt-2">
                <div class="flex justify-between items-center">
                    <span class="font-bold text-lg text-primary-900">Total</span>
                    <span class="font-bold text-lg text-primary-600">
                        ₱{{ number_format($loan->delivery_fee, 2) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Button -->
    @if($loan->delivery_fee > 0)
        <form action="{{ route('payments.createForLoan', $loan->id) }}" method="POST" class="mb-4">
            @csrf
            <button type="submit" class="btn btn-primary w-full">
                Proceed to Payment
            </button>
        </form>
    @else
        <form action="{{ route('payments.confirmFreeLoan', $loan->id) }}" method="POST" class="mb-4">
            @csrf
            <button type="submit" class="btn btn-primary w-full">
                Confirm Order
            </button>
        </form>
    @endif

    <!-- Back Button -->
    <a href="{{ route('books.show', $loan->book) }}" class="block text-center text-primary-600 text-sm py-2">
        ← Back to Book Details
    </a>
</div>
@endsection

