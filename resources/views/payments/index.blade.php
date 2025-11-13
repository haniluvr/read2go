<x-dashboard-layout>
    <x-slot name="header">Payments</x-slot>

    <div class="card p-8">
        <h2 class="text-2xl font-bold text-primary-900 mb-6">Payment History</h2>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-primary-200">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">Date</th>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">Description</th>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">Amount</th>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">Status</th>
                        <th class="text-right py-3 px-4 font-semibold text-primary-900">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments ?? [] as $payment)
                        <tr class="border-b border-primary-100 hover:bg-primary-50 transition">
                            <td class="py-4 px-4 text-sm text-primary-600">
                                {{ $payment->created_at->format('M d, Y') }}
                            </td>
                            <td class="py-4 px-4">
                                <p class="font-medium text-primary-900">{{ $payment->description }}</p>
                                @if($payment->xendit_payment_id)
                                    <p class="text-xs text-primary-500">ID: {{ $payment->xendit_payment_id }}</p>
                                @endif
                            </td>
                            <td class="py-4 px-4">
                                <p class="font-bold text-primary-900">₱{{ number_format($payment->amount, 2) }}</p>
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-block text-xs px-3 py-1 rounded-full
                                    {{ $payment->status === 'paid' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $payment->status === 'failed' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-right">
                                @if($payment->status === 'pending')
                                    <button class="text-primary-500 hover:text-primary-700 text-sm font-medium">Pay Now</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-primary-600">
                                No payment history found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-dashboard-layout>

