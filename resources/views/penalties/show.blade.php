@extends('layouts.mobile')

@section('content')
<div class="px-4 py-6">
    <!-- Back Button -->
    <a href="{{ route('penalties.index') }}" class="inline-flex items-center text-primary-600 mb-4">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to Penalties
    </a>

    <!-- Penalty Details -->
    <div class="card p-4 mb-4">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-xl font-bold text-primary-900">{{ ucfirst($penalty->type) }} Penalty</h1>
            <span class="inline-block text-sm px-3 py-1 rounded-full {{ $penalty->is_paid ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                {{ $penalty->is_paid ? 'Paid' : 'Unpaid' }}
            </span>
        </div>

        <div class="space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-primary-600">Amount:</span>
                <span class="font-bold text-lg text-primary-900">₱{{ number_format($penalty->amount, 2) }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-primary-600">Date Issued:</span>
                <span class="font-medium text-primary-900">{{ $penalty->created_at->format('M d, Y') }}</span>
            </div>
            @if($penalty->is_paid && $penalty->payment)
                <div class="flex justify-between">
                    <span class="text-primary-600">Date Paid:</span>
                    <span class="font-medium text-primary-900">{{ $penalty->payment->updated_at->format('M d, Y') }}</span>
                </div>
            @endif
        </div>

        @if($penalty->reason)
            <div class="mt-4 p-3 bg-primary-50 rounded-lg">
                <p class="text-sm font-medium text-primary-700 mb-1">Reason:</p>
                <p class="text-sm text-primary-600">{{ $penalty->reason }}</p>
            </div>
        @endif
    </div>

    <!-- Related Loan -->
    @if($penalty->loan)
        <div class="card p-4 mb-4">
            <h2 class="text-lg font-bold text-primary-900 mb-3">Related Loan</h2>
            <div class="flex gap-4">
                <div class="w-16 h-24 flex-shrink-0 rounded-lg overflow-hidden bg-primary-100">
                    @if($penalty->loan->book->cover_url)
                        <img src="{{ $penalty->loan->book->cover_url }}" alt="{{ $penalty->loan->book->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-primary-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-primary-900 mb-1">{{ $penalty->loan->book->title }}</h3>
                    <p class="text-sm text-primary-600 mb-2">{{ $penalty->loan->book->author }}</p>
                    <div class="text-xs text-primary-500">
                        <p>Borrowed: {{ $penalty->loan->borrowed_at ? $penalty->loan->borrowed_at->format('M d, Y') : 'N/A' }}</p>
                        <p>Due: {{ $penalty->loan->due_date->format('M d, Y') }}</p>
                    </div>
                    <a href="{{ route('loans.show', $penalty->loan) }}" class="text-sm text-primary-600 underline mt-2 inline-block">
                        View Loan Details
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Payment Action -->
    @if(!$penalty->is_paid)
        <form action="{{ route('payments.create.penalty', $penalty) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary w-full">
                Pay Penalty (₱{{ number_format($penalty->amount, 2) }})
            </button>
        </form>
    @endif
</div>
@endsection

