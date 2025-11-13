@extends('layouts.mobile')

@section('content')
<div class="px-4 py-6">
    <!-- Back Button -->
    <a href="{{ route('books.show', $book) }}" class="inline-flex items-center text-primary-600 mb-4">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back
    </a>

    <!-- Book Info -->
    <div class="card p-4 mb-6">
        <div class="flex gap-4">
            <div class="w-20 h-28 flex-shrink-0 rounded-lg overflow-hidden bg-primary-100">
                @if($book->cover_url)
                    <img src="{{ $book->cover_url }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-primary-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                @endif
            </div>
            <div class="flex-1">
                <h2 class="font-bold text-primary-900 mb-1">{{ $book->title }}</h2>
                <p class="text-sm text-primary-600">{{ $book->author }}</p>
            </div>
        </div>
    </div>

    <!-- Loan Form -->
    <form method="POST" action="{{ route('loans.store', $book) }}" class="space-y-6">
        @csrf

        <!-- Delivery Type -->
        <div>
            <label class="block text-sm font-medium text-primary-900 mb-3">Delivery Method</label>
            <div class="space-y-3">
                <label class="flex items-center p-4 border-2 border-primary-200 rounded-lg cursor-pointer hover:border-primary-500 transition {{ old('delivery_type') === 'home' || !old('delivery_type') ? 'border-primary-500 bg-primary-50' : '' }}">
                    <input type="radio" name="delivery_type" value="home" class="mr-3" {{ old('delivery_type') === 'home' || !old('delivery_type') ? 'checked' : '' }} required>
                    <div class="flex-1">
                        <div class="font-semibold text-primary-900">Home Delivery</div>
                        <div class="text-sm text-primary-600">Delivered to your doorstep (fee applies)</div>
                    </div>
                </label>
                <label class="flex items-center p-4 border-2 border-primary-200 rounded-lg cursor-pointer hover:border-primary-500 transition {{ old('delivery_type') === 'pickup' ? 'border-primary-500 bg-primary-50' : '' }}">
                    <input type="radio" name="delivery_type" value="pickup" class="mr-3" {{ old('delivery_type') === 'pickup' ? 'checked' : '' }}>
                    <div class="flex-1">
                        <div class="font-semibold text-primary-900">Pickup</div>
                        <div class="text-sm text-primary-600">Pick up from library (free)</div>
                    </div>
                </label>
            </div>
            @error('delivery_type')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Address Fields (shown when home delivery is selected) -->
        <div id="address-fields" class="space-y-4" style="display: {{ old('delivery_type') === 'home' || !old('delivery_type') ? 'block' : 'none' }};">
            <div>
                <label for="delivery_address" class="block text-sm font-medium text-primary-900 mb-2">Delivery Address</label>
                <input 
                    type="text" 
                    id="delivery_address" 
                    name="delivery_address" 
                    value="{{ old('delivery_address', auth()->user()->address) }}"
                    placeholder="Enter your Quezon City address"
                    class="input-underline w-full @error('delivery_address') border-red-500 @enderror"
                >
                @error('delivery_address')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="barangay" class="block text-sm font-medium text-primary-900 mb-2">Barangay</label>
                <select 
                    id="barangay" 
                    name="barangay" 
                    class="w-full px-4 py-3 rounded-lg border border-primary-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent @error('barangay') border-red-500 @enderror"
                >
                    <option value="">Select Barangay</option>
                    @foreach($barangays as $barangay)
                        <option value="{{ $barangay }}" {{ old('barangay', auth()->user()->barangay) === $barangay ? 'selected' : '' }}>
                            {{ $barangay }}
                        </option>
                    @endforeach
                </select>
                @error('barangay')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary w-full">
            Request Book
        </button>
    </form>
</div>

<script>
    document.querySelectorAll('input[name="delivery_type"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const addressFields = document.getElementById('address-fields');
            if (this.value === 'home') {
                addressFields.style.display = 'block';
                document.getElementById('delivery_address').required = true;
                document.getElementById('barangay').required = true;
            } else {
                addressFields.style.display = 'none';
                document.getElementById('delivery_address').required = false;
                document.getElementById('barangay').required = false;
            }
        });
    });
</script>
@endsection

