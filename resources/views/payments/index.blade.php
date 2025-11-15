@extends('layouts.mobile')

@section('content')
<div class="px-4 py-6">
    <h1 class="text-2xl font-bold text-primary-900 mb-6">Payment History</h1>

    <div class="space-y-4">
        @forelse($payments ?? [] as $payment)
            <div class="card p-4">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <h3 class="font-semibold text-primary-900 mb-1">{{ $payment->description }}</h3>
                        <p class="text-sm text-primary-600 mb-2">{{ $payment->created_at->format('M d, Y') }}</p>
                        @if($payment->xendit_payment_id)
                            <p class="text-xs text-primary-500">ID: {{ $payment->xendit_payment_id }}</p>
                        @endif
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-lg text-primary-900 mb-2">₱{{ number_format($payment->amount, 2) }}</p>
                        <span class="inline-block text-xs px-2 py-1 rounded-full
                            {{ $payment->status === 'paid' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $payment->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $payment->status === 'failed' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>
                </div>

                @if($payment->status === 'pending')
                    <div class="flex gap-2 mt-3">
                        @if($payment->loan_id && $payment->loan && $payment->loan->status === 'pending_payment')
                            <form action="{{ route('payments.createForLoan', $payment->loan_id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="btn btn-primary w-full text-sm py-2">Retry Payment</button>
                            </form>
                        @elseif($payment->penalty_id)
                            <form action="{{ route('payments.create.penalty', $payment->penalty_id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="btn btn-primary w-full text-sm py-2">Pay Now</button>
                            </form>
                        @endif
                        @if($payment->xendit_payment_id)
                            <form action="{{ route('payments.checkStatus', $payment->id) }}" method="POST" class="flex-1">
                                @csrf
                                <button type="submit" class="btn btn-secondary w-full text-sm py-2">Check Status</button>
                            </form>
                        @endif
                    </div>
                @endif

                @if($payment->loan)
                    <div class="mt-3 pt-3 border-t border-primary-200">
                        <a href="{{ route('loans.show', $payment->loan) }}" class="text-sm text-primary-600 underline">
                            View Loan Details
                        </a>
                    </div>
                @endif
            </div>
        @empty
            <div class="card p-8 text-center">
                <svg class="w-16 h-16 text-primary-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="text-xl font-bold text-primary-900 mb-2">No payment history</h3>
                <p class="text-primary-600">You haven't made any payments yet.</p>
            </div>
        @endforelse
    </div>

    @if(isset($payments) && $payments->hasPages())
        <div class="mt-6">
            {{ $payments->links() }}
        </div>
    @endif
</div>
@endsection

