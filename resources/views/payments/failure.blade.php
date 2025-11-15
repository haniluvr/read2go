@extends('layouts.mobile')

@section('content')
<div class="px-4 py-6">
    <!-- Error Icon -->
    <div class="flex flex-col items-center justify-center py-8">
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mb-4">
            <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-primary-900 mb-2">Payment Failed</h1>
        <p class="text-primary-600 text-center">Your payment could not be processed. Please try again.</p>
    </div>

    <!-- Action Buttons -->
    <div class="space-y-3">
        <a href="{{ route('loans.index') }}" class="btn btn-primary w-full">
            Go to My Loans
        </a>
        <a href="{{ route('books.index') }}" class="block text-center text-primary-600 text-sm py-2">
            Browse Books
        </a>
    </div>
</div>
@endsection

