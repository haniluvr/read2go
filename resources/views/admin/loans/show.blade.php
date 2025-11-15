<x-admin-layout>
    <x-slot name="header">Loan Details #{{ $loan->id }}</x-slot>

    <div class="space-y-6">
        <!-- Back Button -->
        <a href="{{ route('admin.loans.index') }}" class="inline-flex items-center text-primary-600">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Loans
        </a>

        <!-- Loan Information -->
        <div class="card p-6">
            <h2 class="text-xl font-bold text-primary-900 mb-4">Loan Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-primary-600 mb-1">Status</p>
                    <span class="inline-block text-sm px-3 py-1 rounded-full
                        {{ $loan->status === 'active' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $loan->status === 'pending_payment' ? 'bg-yellow-100 text-yellow-700' : '' }}
                        {{ $loan->status === 'returned' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $loan->status === 'overdue' ? 'bg-red-100 text-red-700' : '' }}
                        {{ $loan->status === 'lost' ? 'bg-gray-100 text-gray-700' : '' }}">
                        {{ ucfirst(str_replace('_', ' ', $loan->status)) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-primary-600 mb-1">Delivery Type</p>
                    <p class="font-medium text-primary-900">{{ ucfirst($loan->delivery_type) }}</p>
                </div>
                <div>
                    <p class="text-sm text-primary-600 mb-1">Borrowed Date</p>
                    <p class="font-medium text-primary-900">{{ $loan->borrowed_at ? $loan->borrowed_at->format('M d, Y') : 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-sm text-primary-600 mb-1">Due Date</p>
                    <p class="font-medium text-primary-900 {{ $loan->isOverdue() ? 'text-red-600' : '' }}">
                        {{ $loan->due_date ? $loan->due_date->format('M d, Y') : 'N/A' }}
                    </p>
                </div>
                @if($loan->returned_at)
                    <div>
                        <p class="text-sm text-primary-600 mb-1">Returned Date</p>
                        <p class="font-medium text-primary-900">{{ $loan->returned_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-primary-600 mb-1">Return Method</p>
                        <p class="font-medium text-primary-900">{{ ucfirst($loan->return_method ?? 'N/A') }}</p>
                    </div>
                @endif
                @if($loan->delivery_fee > 0)
                    <div>
                        <p class="text-sm text-primary-600 mb-1">Delivery Fee</p>
                        <p class="font-medium text-primary-900">₱{{ number_format($loan->delivery_fee, 2) }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- User Information -->
        <div class="card p-6">
            <h2 class="text-xl font-bold text-primary-900 mb-4">User Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-primary-600 mb-1">Name</p>
                    <p class="font-medium text-primary-900">{{ $loan->user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-primary-600 mb-1">Email</p>
                    <p class="font-medium text-primary-900">{{ $loan->user->email }}</p>
                </div>
                @if($loan->user->phone)
                    <div>
                        <p class="text-sm text-primary-600 mb-1">Phone</p>
                        <p class="font-medium text-primary-900">{{ $loan->user->phone }}</p>
                    </div>
                @endif
                @if($loan->delivery_type === 'home' && $loan->delivery_address)
                    <div>
                        <p class="text-sm text-primary-600 mb-1">Delivery Address</p>
                        <p class="font-medium text-primary-900">{{ $loan->delivery_address }}, {{ $loan->user->barangay }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Book Information -->
        <div class="card p-6">
            <h2 class="text-xl font-bold text-primary-900 mb-4">Book Information</h2>
            <div class="flex gap-4">
                <div class="w-24 h-32 flex-shrink-0 rounded-lg overflow-hidden bg-primary-100">
                    @if($loan->book->cover_url)
                        <img src="{{ $loan->book->cover_url }}" alt="{{ $loan->book->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-primary-400">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-primary-900 mb-1">{{ $loan->book->title }}</h3>
                    <p class="text-primary-600 mb-2">{{ $loan->book->author }}</p>
                    <p class="text-sm text-primary-500">ISBN: {{ $loan->book->isbn ?? 'N/A' }}</p>
                    <p class="text-sm text-primary-500">Library: {{ $loan->library->name }}</p>
                </div>
            </div>
        </div>

        <!-- Penalties -->
        @if($loan->penalties && $loan->penalties->count() > 0)
            <div class="card p-6">
                <h2 class="text-xl font-bold text-primary-900 mb-4">Penalties</h2>
                <div class="space-y-3">
                    @foreach($loan->penalties as $penalty)
                        <div class="p-3 bg-red-50 rounded-lg border border-red-200">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-red-900">{{ ucfirst($penalty->type) }} Penalty</p>
                                    <p class="text-sm text-red-600">{{ $penalty->reason }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-red-700">₱{{ number_format($penalty->amount, 2) }}</p>
                                    <span class="text-xs {{ $penalty->is_paid ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $penalty->is_paid ? 'Paid' : 'Unpaid' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Delivery Schedules -->
        @if($loan->deliverySchedules && $loan->deliverySchedules->count() > 0)
            <div class="card p-6">
                <h2 class="text-xl font-bold text-primary-900 mb-4">Delivery Schedules</h2>
                <div class="space-y-3">
                    @foreach($loan->deliverySchedules as $schedule)
                        <div class="p-3 bg-primary-50 rounded-lg">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-primary-900">{{ ucfirst($schedule->pickup_type) }}</p>
                                    <p class="text-sm text-primary-600">Scheduled: {{ $schedule->scheduled_at->format('M d, Y h:i A') }}</p>
                                </div>
                                <span class="text-xs px-2 py-1 rounded-full
                                    {{ $schedule->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $schedule->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $schedule->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $schedule->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ ucfirst($schedule->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-admin-layout>

