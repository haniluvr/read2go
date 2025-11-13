<x-dashboard-layout>
    <x-slot name="header">Dashboard</x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="card p-6" data-aos="fade-up" data-aos-delay="100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-primary-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-primary-600 mb-1">Active Loans</p>
            <p class="text-3xl font-bold text-primary-900">{{ $activeLoans ?? 0 }}</p>
        </div>

        <div class="card p-6" data-aos="fade-up" data-aos-delay="200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-accent-beige rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-primary-600 mb-1">Overdue Books</p>
            <p class="text-3xl font-bold text-primary-900">{{ $overdueLoans ?? 0 }}</p>
        </div>

        <div class="card p-6" data-aos="fade-up" data-aos-delay="300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-primary-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-primary-600 mb-1">Pending Penalties</p>
            <p class="text-3xl font-bold text-primary-900">₱{{ number_format($pendingPenalties ?? 0, 2) }}</p>
        </div>

        <div class="card p-6" data-aos="fade-up" data-aos-delay="400">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-accent-dusty rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-primary-600 mb-1">Books Borrowed</p>
            <p class="text-3xl font-bold text-primary-900">{{ $totalBorrowed ?? 0 }}</p>
        </div>
    </div>

    <!-- E-Library Card -->
    <div class="card p-8 mb-8" data-aos="fade-up">
        <h2 class="text-2xl font-bold text-primary-900 mb-6">Your E-Library Card</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl p-8 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
                <div class="relative z-10">
                    <div class="flex items-center space-x-2 mb-8">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                        </svg>
                        <span class="text-xl font-bold">Read2Go</span>
                    </div>
                    <p class="text-sm mb-2 opacity-80">Card Holder</p>
                    <p class="text-2xl font-bold mb-6">{{ auth()->user()->name }}</p>
                    <p class="text-sm opacity-80">Card ID: {{ auth()->user()->e_library_card_id }}</p>
                </div>
            </div>
            <div class="flex items-center justify-center">
                <div class="bg-white p-4 rounded-lg">
                    <!-- QR Code placeholder - would use a QR code generator package -->
                    <div class="w-48 h-48 bg-primary-100 rounded flex items-center justify-center">
                        <p class="text-sm text-primary-600 text-center">QR Code<br/>{{ auth()->user()->e_library_card_id }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="card p-8" data-aos="fade-up">
        <h2 class="text-2xl font-bold text-primary-900 mb-6">Recent Loans</h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-primary-200">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">Book</th>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">Borrowed</th>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">Due Date</th>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">Status</th>
                        <th class="text-right py-3 px-4 font-semibold text-primary-900">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentLoans ?? [] as $loan)
                        <tr class="border-b border-primary-100 hover:bg-primary-50 transition">
                            <td class="py-4 px-4">
                                <p class="font-medium text-primary-900">{{ $loan->book->title ?? 'N/A' }}</p>
                                <p class="text-sm text-primary-600">{{ $loan->book->author ?? '' }}</p>
                            </td>
                            <td class="py-4 px-4 text-sm text-primary-600">
                                {{ $loan->borrowed_at?->format('M d, Y') ?? 'N/A' }}
                            </td>
                            <td class="py-4 px-4 text-sm text-primary-600">
                                {{ $loan->due_date?->format('M d, Y') ?? 'N/A' }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-block text-xs px-3 py-1 rounded-full 
                                    {{ $loan->status === 'active' ? 'bg-blue-100 text-blue-800' : '' }}
                                    {{ $loan->status === 'overdue' ? 'bg-red-100 text-red-800' : '' }}
                                    {{ $loan->status === 'returned' ? 'bg-green-100 text-green-800' : '' }}">
                                    {{ ucfirst($loan->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-right">
                                <a href="{{ route('loans.show', $loan) }}" class="text-primary-500 hover:text-primary-700 text-sm font-medium">View Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-primary-600">
                                No recent loans found. <a href="{{ route('books.index') }}" class="text-primary-500 hover:text-primary-700 font-medium">Browse books</a> to get started!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-dashboard-layout>
