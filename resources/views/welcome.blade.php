<x-home-layout>
    <!-- Zoom Parallax Section - FIRST VIEW -->
    <div class="parallax-container">
        <div class="parallax-sticky">
            <!-- Parallax Images -->
            <div class="parallax-image">
                <div>
                    <img src="https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=800&h=600&fit=crop" alt="Library books" loading="lazy">
                </div>
            </div>
            <div class="parallax-image">
                <div>
                    <img src="https://images.unsplash.com/photo-1495446815901-a7297e633e8d?w=800&h=600&fit=crop" alt="Open book" loading="lazy">
                </div>
            </div>
            <div class="parallax-image">
                <div>
                    <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?w=800&h=600&fit=crop" alt="Reading book" loading="lazy">
                </div>
            </div>
            <div class="parallax-image">
                <div>
                    <img src="https://images.unsplash.com/photo-1521587760476-6c12a4b040da?w=800&h=600&fit=crop" alt="Library interior" loading="lazy">
                </div>
            </div>
            <div class="parallax-image">
                <div>
                    <img src="https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=800&h=600&fit=crop" alt="Stacked books" loading="lazy">
                </div>
            </div>
            <div class="parallax-image">
                <div>
                    <img src="https://images.unsplash.com/photo-1507842217343-583bb7270b66?w=800&h=600&fit=crop" alt="Library shelves" loading="lazy">
                </div>
            </div>
            <div class="parallax-image">
                <div>
                    <img src="https://images.unsplash.com/photo-1516979187457-637abb4f9353?w=800&h=600&fit=crop" alt="Book pages" loading="lazy">
                </div>
            </div>

            <!-- Hero Content Overlay - Appears as you scroll -->
            <div class="parallax-content">
                <div class="text-center">
                    <h1 class="text-5xl md:text-6xl font-bold mb-6 text-white">
                        Your Library, <span class="text-accent-beige">Delivered</span>
                    </h1>
                    <p class="text-xl md:text-2xl text-primary-100 mb-8 max-w-3xl mx-auto">
                        Browse thousands of books from Quezon City libraries and have them delivered right to your doorstep
                    </p>

                    <!-- Search Bar -->
                    <form action="{{ route('books.search') }}" method="GET" class="max-w-2xl mx-auto">
                        <div class="flex gap-2">
                            <input 
                                type="text" 
                                name="q" 
                                placeholder="Search for books, authors, or ISBN..." 
                                class="flex-1 px-6 py-4 rounded-lg text-primary-900 placeholder-primary-400 focus:ring-2 focus:ring-accent-beige"
                                required
                            >
                            <button type="submit" class="btn btn-accent px-8">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <h2 class="text-4xl font-bold text-center mb-16 text-primary-900" data-aos="fade-up">How It Works</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="card p-8 text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-primary-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-primary-900">Browse & Search</h3>
                    <p class="text-primary-600">Search our catalog from multiple Quezon City libraries</p>
                </div>

                <div class="card p-8 text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-primary-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-primary-900">Borrow Books</h3>
                    <p class="text-primary-600">Choose home delivery or pickup from the library</p>
                </div>

                <div class="card p-8 text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-primary-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                    </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-primary-900">Easy Returns</h3>
                    <p class="text-primary-600">Return via pickup service or drop off at the library</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Books Section -->
    <section class="py-20 bg-primary-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-12">
            <div class="flex justify-between items-center mb-12" data-aos="fade-up">
                <h2 class="text-4xl font-bold text-primary-900">Featured Books</h2>
                <a href="{{ route('books.index') }}" class="btn btn-secondary">View All Books</a>
            </div>

            <!-- Bento Grid Layout for Featured Books -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @for($i = 0; $i < 8; $i++)
                <div class="card overflow-hidden group cursor-pointer {{ $i === 0 ? 'md:col-span-2 md:row-span-2' : '' }}" data-aos="fade-up" data-aos-delay="{{ $i * 50 }}">
                    <div class="relative {{ $i === 0 ? 'h-96' : 'h-48' }} bg-gradient-to-br from-primary-100 to-accent-purple overflow-hidden">
                        <div class="absolute inset-0 bg-primary-500 opacity-10"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <svg class="w-20 h-20 text-primary-300" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                    </svg>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-primary-900 mb-1 group-hover:text-primary-500 transition">Sample Book Title</h3>
                        <p class="text-sm text-primary-600">Author Name</p>
                        <span class="inline-block mt-2 text-xs px-2 py-1 bg-accent-cream rounded-full text-primary-700">Available</span>
                    </div>
                </div>
                @endfor
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-hero text-white">
        <div class="max-w-4xl mx-auto px-6 lg:px-12 text-center" data-aos="fade-up">
            <h2 class="text-4xl font-bold mb-6">Ready to Start Reading?</h2>
            <p class="text-xl text-primary-100 mb-8">Join Read2Go today and get access to thousands of books delivered to your doorstep</p>
            <div class="flex justify-center gap-4">
                <a href="{{ route('register') }}" class="btn btn-accent px-8 py-3">Get Started</a>
                <a href="{{ route('books.index') }}" class="btn btn-secondary px-8 py-3">Browse Books</a>
            </div>
        </div>
    </section>
</x-home-layout>
