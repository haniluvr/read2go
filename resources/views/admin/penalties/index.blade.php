<x-admin-layout>
    <x-slot name="header">Penalties Management</x-slot>

    <!-- Filters -->
    <div class="card p-4 mb-6">
        <form method="GET" action="{{ route('admin.penalties.index') }}" class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-primary-700 mb-2">Type</label>
                <select name="type" class="w-full px-4 py-2 rounded-lg border border-primary-200 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">All Types</option>
                    <option value="late" {{ $type === 'late' ? 'selected' : '' }}>Late Return</option>
                    <option value="damage" {{ $type === 'damage' ? 'selected' : '' }}>Damage</option>
                    <option value="lost" {{ $type === 'lost' ? 'selected' : '' }}>Lost Book</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-primary-700 mb-2">Payment Status</label>
                <select name="paid" class="px-4 py-2 rounded-lg border border-primary-200 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">All</option>
                    <option value="1" {{ $paid === '1' ? 'selected' : '' }}>Paid</option>
                    <option value="0" {{ $paid === '0' ? 'selected' : '' }}>Unpaid</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary px-6">Filter</button>
            <a href="{{ route('admin.penalties.index') }}" class="btn btn-secondary px-6">Clear</a>
        </form>
    </div>

    <!-- Penalties Table -->
    <div class="card p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-primary-200">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">ID</th>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">User</th>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">Book</th>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">Type</th>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">Amount</th>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">Status</th>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">Date</th>
                        <th class="text-right py-3 px-4 font-semibold text-primary-900">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penalties as $penalty)
                        <tr class="border-b border-primary-100 hover:bg-primary-50 transition">
                            <td class="py-4 px-4 text-sm text-primary-600">#{{ $penalty->id }}</td>
                            <td class="py-4 px-4">
                                <p class="font-medium text-primary-900">{{ $penalty->user->name }}</p>
                                <p class="text-xs text-primary-500">{{ $penalty->user->email }}</p>
                            </td>
                            <td class="py-4 px-4">
                                <p class="font-medium text-primary-900">{{ $penalty->loan->book->title }}</p>
                                <p class="text-xs text-primary-500">{{ $penalty->loan->book->author }}</p>
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-block text-xs px-2 py-1 rounded-full bg-primary-100 text-primary-700">
                                    {{ ucfirst($penalty->type) }}
                                </span>
                            </td>
                            <td class="py-4 px-4">
                                <p class="font-bold text-primary-900">₱{{ number_format($penalty->amount, 2) }}</p>
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-block text-xs px-2 py-1 rounded-full {{ $penalty->is_paid ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $penalty->is_paid ? 'Paid' : 'Unpaid' }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-sm text-primary-600">
                                {{ $penalty->created_at->format('M d, Y') }}
                            </td>
                            <td class="py-4 px-4 text-right">
                                <a href="{{ route('admin.penalties.show', $penalty) }}" class="text-primary-600 hover:text-primary-900 text-sm font-medium">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-primary-600">
                                No penalties found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($penalties->hasPages())
            <div class="mt-6">
                {{ $penalties->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>

