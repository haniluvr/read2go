@extends('layouts.mobile')

@section('content')
<div class="px-4 py-6">
    <!-- Back Button -->
    <a href="{{ route('loans.show', $loan) }}" class="inline-flex items-center text-primary-600 mb-4">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back
    </a>

    <!-- Book Info -->
    <div class="card p-4 mb-6">
        <div class="flex gap-4 mb-4">
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
                <p class="text-sm text-primary-600 mb-2">{{ $loan->book->author }}</p>
                <div class="text-xs text-primary-500">
                    <p>Due: {{ $loan->due_date->format('M d, Y') }}</p>
                    @if($loan->isOverdue())
                        <p class="text-red-600 font-medium">Overdue by {{ $loan->due_date->diffInDays(now()) }} days</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Return Form -->
    <form method="POST" action="{{ route('returns.store', $loan) }}" class="space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-medium text-primary-900 mb-3">Return Method</label>
            <div class="space-y-3">
                <label class="flex items-center p-4 border-2 border-primary-200 rounded-lg cursor-pointer hover:border-primary-500 transition {{ old('return_method') === 'pickup' || !old('return_method') ? 'border-primary-500 bg-primary-50' : '' }}">
                    <input type="radio" name="return_method" value="pickup" class="mr-3" {{ old('return_method') === 'pickup' || !old('return_method') ? 'checked' : '' }} required>
                    <div class="flex-1">
                        <div class="font-semibold text-primary-900">Schedule Pickup</div>
                        <div class="text-sm text-primary-600">Read2Go rider will pick up the book from your address</div>
                    </div>
                </label>
                <label class="flex items-center p-4 border-2 border-primary-200 rounded-lg cursor-pointer hover:border-primary-500 transition {{ old('return_method') === 'dropoff' ? 'border-primary-500 bg-primary-50' : '' }}">
                    <input type="radio" name="return_method" value="dropoff" class="mr-3" {{ old('return_method') === 'dropoff' ? 'checked' : '' }}>
                    <div class="flex-1">
                        <div class="font-semibold text-primary-900">Drop Off at Library</div>
                        <div class="text-sm text-primary-600">Return the book directly to {{ $loan->library->name ?? 'the library' }}</div>
                    </div>
                </label>
            </div>
            @error('return_method')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Pickup Date (shown when pickup is selected) -->
        <div id="pickup-date-field" class="space-y-4" style="display: {{ old('return_method') === 'pickup' || !old('return_method') ? 'block' : 'none' }};">
            <div>
                <label for="scheduled_at" class="block text-sm font-medium text-primary-900 mb-2">Preferred Pickup Date</label>
                <input 
                    type="date" 
                    id="scheduled_at" 
                    name="scheduled_at" 
                    value="{{ old('scheduled_at', now()->addDay()->format('Y-m-d')) }}"
                    min="{{ now()->format('Y-m-d') }}"
                    class="w-full px-4 py-3 rounded-lg border border-primary-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                >
            </div>
            @if($loan->delivery_type === 'home' && $loan->delivery_address)
                <div class="bg-primary-50 p-3 rounded-lg text-sm">
                    <p class="text-primary-700 font-medium mb-1">Pickup Address:</p>
                    <p class="text-primary-600">{{ $loan->delivery_address }}, {{ $loan->user->barangay }}</p>
                </div>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary w-full">
            Confirm Return
        </button>
    </form>
</div>

<script>
    document.querySelectorAll('input[name="return_method"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const pickupDateField = document.getElementById('pickup-date-field');
            if (this.value === 'pickup') {
                pickupDateField.style.display = 'block';
                document.getElementById('scheduled_at').required = true;
            } else {
                pickupDateField.style.display = 'none';
                document.getElementById('scheduled_at').required = false;
            }
        });
    });
</script>
@endsection

