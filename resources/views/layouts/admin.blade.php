<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Admin - Read2Go' }}</title>

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
                class="fixed left-0 top-0 h-full w-64 bg-primary-900 text-white z-40"
            >
                <div class="flex flex-col h-full">
                    <!-- Logo -->
                    <div class="h-16 flex items-center px-6 border-b border-primary-800">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                            </svg>
                            <div>
                                <span class="text-xl font-bold block">Read2Go</span>
                                <span class="text-xs text-primary-300">Admin Panel</span>
                            </div>
                        </a>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-primary-700' : 'hover:bg-primary-800' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            <span class="font-medium">Dashboard</span>
                        </a>

                        <a href="{{ route('admin.loans.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.loans.*') ? 'bg-primary-700' : 'hover:bg-primary-800' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span class="font-medium">Loans</span>
                        </a>

                        <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-primary-700' : 'hover:bg-primary-800' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <span class="font-medium">Users</span>
                        </a>

                        <a href="{{ route('admin.penalties.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.penalties.*') ? 'bg-primary-700' : 'hover:bg-primary-800' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-medium">Penalties</span>
                        </a>

                        <a href="{{ route('admin.deliveries.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg {{ request()->routeIs('admin.deliveries.*') ? 'bg-primary-700' : 'hover:bg-primary-800' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                            </svg>
                            <span class="font-medium">Deliveries</span>
                        </a>

                        <div class="pt-4 mt-4 border-t border-primary-800">
                            <a href="{{ route('books.index') }}" class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-primary-800 text-primary-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                <span class="font-medium">Back to Site</span>
                            </a>
                        </div>
                    </nav>

                    <!-- User Section -->
                    <div class="p-4 border-t border-primary-800">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-primary-700 rounded-full flex items-center justify-center font-semibold">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-primary-300">Administrator</p>
                            </div>
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

                        <h1 class="text-xl font-bold text-primary-900">{{ $header ?? 'Admin Dashboard' }}</h1>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-sm text-primary-600 hover:text-primary-900 font-medium">Logout</button>
                        </form>
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

