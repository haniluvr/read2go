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
        <h1 class="text-2xl font-bold text-primary-900 mb-2">Payment Successful!</h1>
        <p class="text-primary-600 text-center">Your payment has been processed successfully</p>
    </div>

    <!-- Action Buttons -->
    <div class="space-y-3">
        <div class="flex justify-center">
            <a href="{{ route('loans.index') }}" class="btn btn-primary">
                View My Loans
            </a>
        </div>
        <a href="{{ route('books.index') }}" class="block text-center text-primary-600 text-sm py-2">
            Browse More Books
        </a>
    </div>
</div>
@endsection

