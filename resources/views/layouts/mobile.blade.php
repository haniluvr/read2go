<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Read2Go') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="font-sans antialiased bg-primary-50">
    <!-- Top Navigation -->
    <header class="fixed top-0 left-0 right-0 z-50 bg-white shadow-sm">
        <div class="flex items-center justify-between px-4 h-14">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-xl font-bold text-primary-600">
                    Read2Go
                </a>
            </div>
            <div class="flex items-center gap-3">
                @auth
                    @php
                        $unreadCount = auth()->user()->notifications()->where('is_read', false)->count();
                    @endphp
                    <a href="{{ route('notifications.index') }}" class="relative">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        @if($unreadCount > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">{{ $unreadCount }}</span>
                        @endif
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-14 pb-20 min-h-screen">
        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mx-4 mt-4 rounded">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mx-4 mt-4 rounded">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bottom Navigation -->
    @include('components.mobile.mobile-nav')
</body>
</html>

