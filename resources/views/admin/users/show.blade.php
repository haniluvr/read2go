<x-admin-layout>
    <x-slot name="header">User Details: {{ $user->name }}</x-slot>

    <div class="space-y-6">
        <!-- Back Button -->
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-primary-600">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Users
        </a>

        <!-- User Information -->
        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-bold text-primary-900">User Information</h2>
                @if($user->is_suspended)
                    <form action="{{ route('admin.users.unsuspend', $user) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="btn btn-success">Unsuspend User</button>
                    </form>
                @else
                    <form action="{{ route('admin.users.suspend', $user) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="btn btn-secondary" onclick="return confirm('Are you sure you want to suspend this user?')">
                            Suspend User
                        </button>
                    </form>
                @endif
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-primary-600 mb-1">Name</p>
                    <p class="font-medium text-primary-900">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-sm text-primary-600 mb-1">Username</p>
                    <p class="font-medium text-primary-900">{{ $user->username }}</p>
                </div>
                <div>
                    <p class="text-sm text-primary-600 mb-1">Email</p>
                    <p class="font-medium text-primary-900">{{ $user->email }}</p>
                </div>
                @if($user->phone)
                    <div>
                        <p class="text-sm text-primary-600 mb-1">Phone</p>
                        <p class="font-medium text-primary-900">{{ $user->phone }}</p>
                    </div>
                @endif
                @if($user->address)
                    <div>
                        <p class="text-sm text-primary-600 mb-1">Address</p>
                        <p class="font-medium text-primary-900">{{ $user->address }}</p>
                    </div>
                @endif
                @if($user->barangay)
                    <div>
                        <p class="text-sm text-primary-600 mb-1">Barangay</p>
                        <p class="font-medium text-primary-900">{{ $user->barangay }}</p>
                    </div>
                @endif
                <div>
                    <p class="text-sm text-primary-600 mb-1">E-Library Card ID</p>
                    <p class="font-medium text-primary-900">{{ $user->e_library_card_id }}</p>
                </div>
                <div>
                    <p class="text-sm text-primary-600 mb-1">Status</p>
                    @if($user->is_suspended)
                        <span class="inline-block text-sm px-3 py-1 rounded-full bg-red-100 text-red-700">
                            Suspended
                        </span>
                    @else
                        <span class="inline-block text-sm px-3 py-1 rounded-full bg-green-100 text-green-700">
                            Active
                        </span>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-primary-600 mb-1">Member Since</p>
                    <p class="font-medium text-primary-900">{{ $user->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Loans -->
        <div class="card p-6">
            <h2 class="text-xl font-bold text-primary-900 mb-4">Book Loans</h2>
            @if($user->bookLoans && $user->bookLoans->count() > 0)
                <div class="space-y-3">
                    @foreach($user->bookLoans->take(10) as $loan)
                        <div class="p-3 bg-primary-50 rounded-lg">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-primary-900">{{ $loan->book->title }}</p>
                                    <p class="text-sm text-primary-600">{{ $loan->book->author }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="text-xs px-2 py-1 rounded-full
                                        {{ $loan->status === 'active' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $loan->status === 'pending_payment' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $loan->status === 'returned' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $loan->status === 'overdue' ? 'bg-red-100 text-red-700' : '' }}">
                                        {{ ucfirst(str_replace('_', ' ', $loan->status)) }}
                                    </span>
                                    <p class="text-xs text-primary-500 mt-1">{{ $loan->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if($user->bookLoans->count() > 10)
                        <p class="text-sm text-primary-600 text-center">... and {{ $user->bookLoans->count() - 10 }} more</p>
                    @endif
                </div>
            @else
                <p class="text-sm text-primary-600">No loans found.</p>
            @endif
        </div>

        <!-- Penalties -->
        @if($user->penalties && $user->penalties->count() > 0)
            <div class="card p-6">
                <h2 class="text-xl font-bold text-primary-900 mb-4">Penalties</h2>
                <div class="space-y-3">
                    @foreach($user->penalties as $penalty)
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

        <!-- Payments -->
        @if($user->payments && $user->payments->count() > 0)
            <div class="card p-6">
                <h2 class="text-xl font-bold text-primary-900 mb-4">Payment History</h2>
                <div class="space-y-3">
                    @foreach($user->payments->take(10) as $payment)
                        <div class="p-3 bg-primary-50 rounded-lg">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-medium text-primary-900">{{ $payment->description }}</p>
                                    <p class="text-sm text-primary-600">{{ $payment->created_at->format('M d, Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-primary-900">₱{{ number_format($payment->amount, 2) }}</p>
                                    <span class="text-xs px-2 py-1 rounded-full
                                        {{ $payment->status === 'paid' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $payment->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $payment->status === 'failed' ? 'bg-red-100 text-red-700' : '' }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if($user->payments->count() > 10)
                        <p class="text-sm text-primary-600 text-center">... and {{ $user->payments->count() - 10 }} more</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-admin-layout>

