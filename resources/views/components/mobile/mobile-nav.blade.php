@php
    $currentRoute = request()->route()->getName();
    $activeLoansCount = auth()->check() ? auth()->user()->bookLoans()->where('status', 'active')->count() : 0;
@endphp

<nav class="fixed bottom-0 left-0 right-0 z-50 bg-white border-t border-primary-200">
    <div class="flex items-center justify-around h-16">
        <!-- Home -->
        <a href="{{ route('home') }}" class="flex flex-col items-center justify-center flex-1 h-full {{ str_starts_with($currentRoute, 'home') ? 'text-primary-600' : 'text-primary-400' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
            </svg>
            <span class="text-xs font-medium">Home</span>
        </a>

        <!-- Discover -->
        <a href="{{ route('books.index') }}" class="flex flex-col items-center justify-center flex-1 h-full {{ str_starts_with($currentRoute, 'books') ? 'text-primary-600' : 'text-primary-400' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <span class="text-xs font-medium">Discover</span>
        </a>

        <!-- Shelf -->
        <a href="{{ route('loans.index') }}" class="flex flex-col items-center justify-center flex-1 h-full relative {{ str_starts_with($currentRoute, 'loans') ? 'text-primary-600' : 'text-primary-400' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <span class="text-xs font-medium">Shelf</span>
            @if($activeLoansCount > 0)
                <span class="absolute top-0 right-1/4 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ $activeLoansCount }}</span>
            @endif
        </a>

        <!-- Profile -->
        <a href="{{ auth()->check() ? route('profile.edit') : route('login') }}" class="flex flex-col items-center justify-center flex-1 h-full {{ str_starts_with($currentRoute, 'profile') ? 'text-primary-600' : 'text-primary-400' }}">
            <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            <span class="text-xs font-medium">Profile</span>
        </a>
    </div>
</nav>

