<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'Read2Go') }}</title>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" x-data="{ sidebarOpen: true }">
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <aside 
                x-show="sidebarOpen" 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="-translate-x-full"
                x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-300"
                x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full"
                class="fixed left-0 top-0 h-full w-64 bg-white border-r border-primary-100 z-40"
            >
                <div class="flex flex-col h-full">
                    <!-- Logo -->
                    <div class="h-16 flex items-center px-6 border-b border-primary-100">
                        <a href="{{ route('books.index') }}" class="flex items-center space-x-2">
                            <svg class="w-8 h-8 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                            </svg>
                            <span class="text-xl font-bold text-primary-900">Read2Go</span>
                        </a>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-primary-500 text-white' : 'text-primary-600 hover:bg-primary-50' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span class="font-medium">Dashboard</span>
                        </a>

                        <a href="{{ route('books.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('books.*') ? 'bg-primary-500 text-white' : 'text-primary-600 hover:bg-primary-50' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <span class="font-medium">Browse Books</span>
                        </a>

                        <a href="{{ route('loans.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('loans.*') ? 'bg-primary-500 text-white' : 'text-primary-600 hover:bg-primary-50' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span class="font-medium">My Loans</span>
                        </a>

                        <a href="{{ route('penalties.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('penalties.*') ? 'bg-primary-500 text-white' : 'text-primary-600 hover:bg-primary-50' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-medium">Penalties</span>
                        </a>

                        <a href="{{ route('payments.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('payments.*') ? 'bg-primary-500 text-white' : 'text-primary-600 hover:bg-primary-50' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <span class="font-medium">Payments</span>
                        </a>
                    </nav>

                    <!-- User Section -->
                    <div class="p-4 border-t border-primary-100">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-primary-500 rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-primary-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-primary-500">{{ auth()->user()->email }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 space-y-1">
                            <a href="{{ route('profile.edit') }}" class="block text-sm text-primary-600 hover:text-primary-900">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left text-sm text-primary-600 hover:text-primary-900">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1" :class="sidebarOpen ? 'ml-64' : 'ml-0'" style="transition: margin-left 0.3s;">
                <!-- Top Navigation -->
                <nav class="fixed top-0 right-0 left-0 h-16 bg-white border-b border-primary-100 z-30" :class="sidebarOpen ? 'ml-64' : 'ml-0'" style="transition: margin-left 0.3s;">
                    <div class="h-full px-6 flex items-center justify-between">
                        <button @click="sidebarOpen = !sidebarOpen" class="text-primary-600 hover:text-primary-900">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>

                        <h1 class="text-xl font-bold text-primary-900">{{ $header ?? 'Dashboard' }}</h1>

                        <div></div>
                    </div>
                </nav>

                <!-- Page Content -->
                <main class="pt-16 p-6">
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-lg" data-aos="fade-down">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-lg" data-aos="fade-down">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>

