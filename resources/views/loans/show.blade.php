@extends('layouts.mobile')

@section('content')
<div class="px-4 py-6">
    <!-- Back Button -->
    <a href="{{ route('loans.index') }}" class="inline-flex items-center text-primary-600 mb-4">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to Shelf
    </a>

    <!-- Loan Details -->
    <div class="card p-4 mb-4">
        <div class="flex gap-4 mb-4">
            <div class="w-24 h-32 flex-shrink-0 rounded-lg overflow-hidden bg-primary-100">
                @if($loan->book->cover_url)
                    <img src="{{ $loan->book->cover_url }}" alt="{{ $loan->book->title }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-primary-400">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                @endif
            </div>
            <div class="flex-1">
                <h1 class="text-xl font-bold text-primary-900 mb-1">{{ $loan->book->title }}</h1>
                <p class="text-primary-600 mb-3">{{ $loan->book->author }}</p>
                <span class="inline-block text-sm px-3 py-1 rounded-full {{ $loan->status === 'active' ? 'bg-green-100 text-green-700' : ($loan->status === 'overdue' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700') }}">
                    {{ ucfirst($loan->status) }}
                </span>
            </div>
        </div>

        <div class="space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-primary-600">Borrowed Date:</span>
                <span class="font-medium text-primary-900">{{ $loan->borrowed_at->format('M d, Y') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-primary-600">Due Date:</span>
                <span class="font-medium text-primary-900 {{ $loan->isOverdue() ? 'text-red-600' : '' }}">
                    {{ $loan->due_date->format('M d, Y') }}
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-primary-600">Delivery Type:</span>
                <span class="font-medium text-primary-900">{{ ucfirst($loan->delivery_type) }}</span>
            </div>
            @if($loan->delivery_type === 'home' && $loan->delivery_address)
                <div>
                    <span class="text-primary-600">Delivery Address:</span>
                    <p class="font-medium text-primary-900 mt-1">{{ $loan->delivery_address }}, {{ $loan->user->barangay }}</p>
                </div>
            @endif
            @if($loan->delivery_fee > 0)
                <div class="flex justify-between">
                    <span class="text-primary-600">Delivery Fee:</span>
                    <span class="font-medium text-primary-900">₱{{ number_format($loan->delivery_fee, 2) }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Actions -->
    @if($loan->status === 'active')
        <a href="{{ route('returns.create', $loan) }}" class="btn btn-primary w-full mb-4 text-center block">
            Return Book
        </a>
    @endif

    <!-- Penalties -->
    @if($loan->penalties && $loan->penalties->count() > 0)
        <div class="card p-4 mb-4">
            <h2 class="text-lg font-bold text-primary-900 mb-3">Penalties</h2>
            <div class="space-y-2">
                @foreach($loan->penalties as $penalty)
                    <div class="flex justify-between items-center p-2 bg-red-50 rounded">
                        <div>
                            <p class="text-sm font-medium text-red-900">{{ ucfirst($penalty->type) }}</p>
                            <p class="text-xs text-red-600">{{ $penalty->reason }}</p>
                        </div>
                        <span class="text-sm font-bold text-red-700">₱{{ number_format($penalty->amount, 2) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Library Info -->
    @if($loan->library)
        <div class="card p-4">
            <h2 class="text-lg font-bold text-primary-900 mb-3">Library Information</h2>
            <div class="space-y-2 text-sm">
                <p class="text-primary-600">
                    <span class="font-medium">Name:</span> {{ $loan->library->name }}
                </p>
                @if($loan->library->address)
                    <p class="text-primary-600">
                        <span class="font-medium">Address:</span> {{ $loan->library->address }}
                    </p>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection

