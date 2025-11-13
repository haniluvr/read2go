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
    <body class="font-sans antialiased">
        <!-- Fixed Top Navigation -->
        <nav class="fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-sm border-b border-primary-100 transition-all duration-300" id="main-nav">
            <div class="w-full px-6 lg:px-12">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <a href="{{ route('books.index') }}" class="flex items-center space-x-2">
                        <svg class="w-8 h-8 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                        </svg>
                        <span class="text-xl font-bold text-primary-900">Read2Go</span>
                    </a>

                    <!-- Navigation Links -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('books.index') }}" class="text-primary-600 hover:text-primary-900 font-medium transition">Browse Books</a>
                        
                        @auth
                            <a href="{{ route('loans.index') }}" class="text-primary-600 hover:text-primary-900 font-medium transition">My Loans</a>
                            <a href="{{ route('dashboard') }}" class="text-primary-600 hover:text-primary-900 font-medium transition">Dashboard</a>
                            
                            @if(auth()->user()->is_admin)
                                <a href="{{ route('admin.dashboard') }}" class="text-primary-600 hover:text-primary-900 font-medium transition">Admin</a>
                            @endif
                            
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-primary-600 hover:text-primary-900 font-medium transition">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-primary-600 hover:text-primary-900 font-medium transition">Login</a>
                            <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                        @endauth
                    </div>

                    <!-- Mobile Menu Button -->
                    <button x-data="{ open: false }" @click="open = !open" class="md:hidden text-primary-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="pt-16">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-primary-900 text-white mt-20">
            <div class="max-w-7xl mx-auto px-6 lg:px-12 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="col-span-1">
                        <div class="flex items-center space-x-2 mb-4">
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                            </svg>
                            <span class="text-xl font-bold">Read2Go</span>
                        </div>
                        <p class="text-primary-300 text-sm">Your doorstep library service in Quezon City.</p>
                    </div>
                    
                    <div>
                        <h3 class="font-semibold mb-3">Quick Links</h3>
                        <ul class="space-y-2 text-sm text-primary-300">
                            <li><a href="{{ route('books.index') }}" class="hover:text-white transition">Browse Books</a></li>
                            <li><a href="#" class="hover:text-white transition">How It Works</a></li>
                            <li><a href="#" class="hover:text-white transition">Pricing</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="font-semibold mb-3">Support</h3>
                        <ul class="space-y-2 text-sm text-primary-300">
                            <li><a href="#" class="hover:text-white transition">FAQ</a></li>
                            <li><a href="#" class="hover:text-white transition">Contact Us</a></li>
                            <li><a href="#" class="hover:text-white transition">Terms of Service</a></li>
                        </ul>
                    </div>
                    
                    <div>
                        <h3 class="font-semibold mb-3">Contact</h3>
                        <ul class="space-y-2 text-sm text-primary-300">
                            <li>Quezon City, Philippines</li>
                            <li>support@read2go.ph</li>
                        </ul>
                    </div>
                </div>
                
                <div class="border-t border-primary-800 mt-8 pt-8 text-sm text-primary-300 text-center">
                    <p>&copy; {{ date('Y') }} Read2Go. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </body>
</html>

