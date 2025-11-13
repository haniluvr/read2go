<x-dashboard-layout>
    <x-slot name="header">Penalties</x-slot>

    <div class="card p-8">
        <h2 class="text-2xl font-bold text-primary-900 mb-6">My Penalties</h2>

        <div class="space-y-4">
            @forelse($penalties ?? [] as $penalty)
                <div class="card p-6 border-l-4 {{ $penalty->is_paid ? 'border-green-500' : 'border-red-500' }}" data-aos="fade-up">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <h3 class="text-lg font-bold text-primary-900">{{ ucfirst($penalty->type) }} Penalty</h3>
                                <span class="inline-block text-xs px-3 py-1 rounded-full {{ $penalty->is_paid ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $penalty->is_paid ? 'Paid' : 'Unpaid' }}
                                </span>
                            </div>

                            @if($penalty->reason)
                                <p class="text-primary-600 mb-3">{{ $penalty->reason }}</p>
                            @endif

                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-primary-500 mb-1">Amount</p>
                                    <p class="font-bold text-xl text-primary-900">₱{{ number_format($penalty->amount, 2) }}</p>
                                </div>
                                <div>
                                    <p class="text-primary-500 mb-1">Date Issued</p>
                                    <p class="font-medium text-primary-900">{{ $penalty->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>

                            @if($penalty->bookLoan)
                                <div class="mt-4 p-3 bg-primary-50 rounded-lg">
                                    <p class="text-sm text-primary-600">Related to: <span class="font-medium text-primary-900">{{ $penalty->bookLoan->book->title }}</span></p>
                                </div>
                            @endif
                        </div>

                        @if(!$penalty->is_paid)
                            <div class="ml-4">
                                <form action="{{ route('payments.create.penalty', $penalty) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Pay Now</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="card p-12 text-center">
                    <svg class="w-16 h-16 text-green-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-xl font-bold text-primary-900 mb-2">No penalties</h3>
                    <p class="text-primary-600">You have a clean record! Keep returning books on time.</p>
                </div>
            @endforelse
        </div>

        @if(isset($penalties) && $penalties->where('is_paid', false)->count() > 0)
            <div class="mt-8 p-6 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h3 class="font-bold text-red-800 mb-2">Outstanding Penalties</h3>
                        <p class="text-red-700 text-sm mb-3">
                            You have unpaid penalties totaling ₱{{ number_format($penalties->where('is_paid', false)->sum('amount'), 2) }}.
                            Please pay these penalties to continue borrowing books.
                        </p>
                        <p class="text-red-600 text-xs">
                            Note: Unpaid penalties may result in account suspension.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-dashboard-layout>

