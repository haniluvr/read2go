<x-admin-layout>
    <x-slot name="header">Admin Dashboard</x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="card p-6 border-l-4 border-primary-500" data-aos="fade-up" data-aos-delay="100">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-primary-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-primary-600 mb-1">Total Users</p>
            <p class="text-3xl font-bold text-primary-900">{{ $totalUsers ?? 0 }}</p>
        </div>

        <div class="card p-6 border-l-4 border-blue-500" data-aos="fade-up" data-aos-delay="200">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-primary-600 mb-1">Active Loans</p>
            <p class="text-3xl font-bold text-primary-900">{{ $activeLoans ?? 0 }}</p>
        </div>

        <div class="card p-6 border-l-4 border-yellow-500" data-aos="fade-up" data-aos-delay="300">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-primary-600 mb-1">Pending Penalties</p>
            <p class="text-3xl font-bold text-primary-900">₱{{ number_format($totalPenalties ?? 0, 2) }}</p>
        </div>

        <div class="card p-6 border-l-4 border-green-500" data-aos="fade-up" data-aos-delay="400">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-primary-600 mb-1">Total Revenue</p>
            <p class="text-3xl font-bold text-primary-900">₱{{ number_format($totalRevenue ?? 0, 2) }}</p>
        </div>
    </div>

    <!-- Recent Activity Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Loans -->
        <div class="card p-6" data-aos="fade-up">
            <h3 class="text-xl font-bold text-primary-900 mb-4">Recent Loans</h3>
            <div class="space-y-3">
                @forelse($recentLoans ?? [] as $loan)
                    <div class="flex items-center justify-between p-3 bg-primary-50 rounded-lg">
                        <div class="flex-1">
                            <p class="font-medium text-primary-900">{{ $loan->book->title }}</p>
                            <p class="text-sm text-primary-600">{{ $loan->user->name }}</p>
                        </div>
                        <span class="text-xs px-2 py-1 rounded-full {{ $loan->status === 'active' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ ucfirst($loan->status) }}
                        </span>
                    </div>
                @empty
                    <p class="text-sm text-primary-600 text-center py-4">No recent loans</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Penalties -->
        <div class="card p-6" data-aos="fade-up" data-aos-delay="100">
            <h3 class="text-xl font-bold text-primary-900 mb-4">Recent Penalties</h3>
            <div class="space-y-3">
                @forelse($recentPenalties ?? [] as $penalty)
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                        <div class="flex-1">
                            <p class="font-medium text-primary-900">{{ $penalty->user->name }}</p>
                            <p class="text-sm text-primary-600">{{ ucfirst($penalty->type) }} penalty</p>
                        </div>
                        <span class="font-bold text-red-800">₱{{ number_format($penalty->amount, 2) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-primary-600 text-center py-4">No recent penalties</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
        <a href="{{ route('admin.loans.index') }}" class="card p-6 hover:shadow-lg transition text-center" data-aos="fade-up">
            <svg class="w-12 h-12 text-primary-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3 class="font-bold text-primary-900">Manage Loans</h3>
        </a>

        <a href="{{ route('admin.users.index') }}" class="card p-6 hover:shadow-lg transition text-center" data-aos="fade-up" data-aos-delay="100">
            <svg class="w-12 h-12 text-primary-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <h3 class="font-bold text-primary-900">Manage Users</h3>
        </a>

        <a href="{{ route('admin.deliveries.index') }}" class="card p-6 hover:shadow-lg transition text-center" data-aos="fade-up" data-aos-delay="200">
            <svg class="w-12 h-12 text-primary-500 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
            </svg>
            <h3 class="font-bold text-primary-900">Manage Deliveries</h3>
        </a>
    </div>
</x-admin-layout>

