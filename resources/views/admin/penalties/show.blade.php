<x-admin-layout>
    <x-slot name="header">Penalty Details #{{ $penalty->id }}</x-slot>

    <div class="space-y-6">
        <!-- Back Button -->
        <a href="{{ route('admin.penalties.index') }}" class="inline-flex items-center text-primary-600">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Penalties
        </a>

        <!-- Penalty Information -->
        <div class="card p-6">
            <h2 class="text-xl font-bold text-primary-900 mb-4">Penalty Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-primary-600 mb-1">Type</p>
                    <span class="inline-block text-sm px-3 py-1 rounded-full bg-primary-100 text-primary-700">
                        {{ ucfirst($penalty->type) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-primary-600 mb-1">Amount</p>
                    <p class="font-bold text-2xl text-primary-900">₱{{ number_format($penalty->amount, 2) }}</p>
                </div>
                <div>
                    <p class="text-sm text-primary-600 mb-1">Status</p>
                    <span class="inline-block text-sm px-3 py-1 rounded-full {{ $penalty->is_paid ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $penalty->is_paid ? 'Paid' : 'Unpaid' }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-primary-600 mb-1">Date Issued</p>
                    <p class="font-medium text-primary-900">{{ $penalty->created_at->format('M d, Y') }}</p>
                </div>
                @if($penalty->is_paid && $penalty->payment)
                    <div>
                        <p class="text-sm text-primary-600 mb-1">Date Paid</p>
                        <p class="font-medium text-primary-900">{{ $penalty->payment->updated_at->format('M d, Y') }}</p>
                    </div>
                @endif
                @if($penalty->reason)
                    <div class="md:col-span-2">
                        <p class="text-sm text-primary-600 mb-1">Reason</p>
                        <p class="font-medium text-primary-900">{{ $penalty->reason }}</p>
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
                    <p class="font-medium text-primary-900">{{ $penalty->user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-primary-600 mb-1">Email</p>
                    <p class="font-medium text-primary-900">{{ $penalty->user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-primary-600 mb-1">Username</p>
                    <p class="font-medium text-primary-900">{{ $penalty->user->username }}</p>
                </div>
                <div>
                    <p class="text-sm text-primary-600 mb-1">E-Library Card ID</p>
                    <p class="font-medium text-primary-900">{{ $penalty->user->e_library_card_id }}</p>
                </div>
            </div>
        </div>

        <!-- Loan Information -->
        <div class="card p-6">
            <h2 class="text-xl font-bold text-primary-900 mb-4">Related Loan</h2>
            <div class="flex gap-4">
                <div class="w-24 h-32 flex-shrink-0 rounded-lg overflow-hidden bg-primary-100">
                    @if($penalty->loan->book->cover_url)
                        <img src="{{ $penalty->loan->book->cover_url }}" alt="{{ $penalty->loan->book->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-primary-400">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                    @endif
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-primary-900 mb-1">{{ $penalty->loan->book->title }}</h3>
                    <p class="text-primary-600 mb-2">{{ $penalty->loan->book->author }}</p>
                    <div class="space-y-1 text-sm text-primary-500">
                        <p>Loan ID: #{{ $penalty->loan->id }}</p>
                        <p>Borrowed: {{ $penalty->loan->borrowed_at ? $penalty->loan->borrowed_at->format('M d, Y') : 'N/A' }}</p>
                        <p>Due: {{ $penalty->loan->due_date->format('M d, Y') }}</p>
                        <p>Library: {{ $penalty->loan->library->name }}</p>
                    </div>
                    <a href="{{ route('admin.loans.show', $penalty->loan) }}" class="text-sm text-primary-600 underline mt-2 inline-block">
                        View Loan Details
                    </a>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        @if($penalty->payment)
            <div class="card p-6">
                <h2 class="text-xl font-bold text-primary-900 mb-4">Payment Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-primary-600 mb-1">Payment ID</p>
                        <p class="font-medium text-primary-900">#{{ $penalty->payment->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-primary-600 mb-1">Amount</p>
                        <p class="font-bold text-primary-900">₱{{ number_format($penalty->payment->amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-primary-600 mb-1">Status</p>
                        <span class="inline-block text-sm px-3 py-1 rounded-full
                            {{ $penalty->payment->status === 'paid' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $penalty->payment->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $penalty->payment->status === 'failed' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($penalty->payment->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-primary-600 mb-1">Date</p>
                        <p class="font-medium text-primary-900">{{ $penalty->payment->created_at->format('M d, Y') }}</p>
                    </div>
                    @if($penalty->payment->xendit_payment_id)
                        <div class="md:col-span-2">
                            <p class="text-sm text-primary-600 mb-1">Xendit Payment ID</p>
                            <p class="font-medium text-primary-900">{{ $penalty->payment->xendit_payment_id }}</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-admin-layout>

