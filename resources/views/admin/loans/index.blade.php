<x-admin-layout>
    <x-slot name="header">Loans Management</x-slot>

    <!-- Filters -->
    <div class="card p-4 mb-6">
        <form method="GET" action="{{ route('admin.loans.index') }}" class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-primary-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 rounded-lg border border-primary-200 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">All Statuses</option>
                    <option value="pending_payment" {{ $status === 'pending_payment' ? 'selected' : '' }}>Pending Payment</option>
                    <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="returned" {{ $status === 'returned' ? 'selected' : '' }}>Returned</option>
                    <option value="overdue" {{ $status === 'overdue' ? 'selected' : '' }}>Overdue</option>
                    <option value="lost" {{ $status === 'lost' ? 'selected' : '' }}>Lost</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary px-6">Filter</button>
            <a href="{{ route('admin.loans.index') }}" class="btn btn-secondary px-6">Clear</a>
        </form>
    </div>

    <!-- Loans Table -->
    <div class="card p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-primary-200">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">ID</th>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">User</th>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">Book</th>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">Library</th>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">Status</th>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">Due Date</th>
                        <th class="text-right py-3 px-4 font-semibold text-primary-900">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                        <tr class="border-b border-primary-100 hover:bg-primary-50 transition">
                            <td class="py-4 px-4 text-sm text-primary-600">#{{ $loan->id }}</td>
                            <td class="py-4 px-4">
                                <p class="font-medium text-primary-900">{{ $loan->user->name }}</p>
                                <p class="text-xs text-primary-500">{{ $loan->user->email }}</p>
                            </td>
                            <td class="py-4 px-4">
                                <p class="font-medium text-primary-900">{{ $loan->book->title }}</p>
                                <p class="text-xs text-primary-500">{{ $loan->book->author }}</p>
                            </td>
                            <td class="py-4 px-4 text-sm text-primary-600">{{ $loan->library->name }}</td>
                            <td class="py-4 px-4">
                                <span class="inline-block text-xs px-2 py-1 rounded-full
                                    {{ $loan->status === 'active' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $loan->status === 'pending_payment' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $loan->status === 'returned' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $loan->status === 'overdue' ? 'bg-red-100 text-red-700' : '' }}
                                    {{ $loan->status === 'lost' ? 'bg-gray-100 text-gray-700' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $loan->status)) }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-sm text-primary-600">
                                {{ $loan->due_date ? $loan->due_date->format('M d, Y') : 'N/A' }}
                            </td>
                            <td class="py-4 px-4 text-right">
                                <a href="{{ route('admin.loans.show', $loan) }}" class="text-primary-600 hover:text-primary-900 text-sm font-medium">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-primary-600">
                                No loans found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($loans->hasPages())
            <div class="mt-6">
                {{ $loans->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>

