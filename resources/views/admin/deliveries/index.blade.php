<x-admin-layout>
    <x-slot name="header">Deliveries Management</x-slot>

    <!-- Filters -->
    <div class="card p-4 mb-6">
        <form method="GET" action="{{ route('admin.deliveries.index') }}" class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-primary-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 rounded-lg border border-primary-200 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-primary-700 mb-2">Type</label>
                <select name="pickup_type" class="px-4 py-2 rounded-lg border border-primary-200 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">All Types</option>
                    <option value="delivery" {{ $pickupType === 'delivery' ? 'selected' : '' }}>Delivery</option>
                    <option value="return" {{ $pickupType === 'return' ? 'selected' : '' }}>Return Pickup</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary px-6">Filter</button>
            <a href="{{ route('admin.deliveries.index') }}" class="btn btn-secondary px-6">Clear</a>
        </form>
    </div>

    <!-- Deliveries Table -->
    <div class="card p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-primary-200">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">ID</th>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">User</th>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">Book</th>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">Type</th>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">Scheduled At</th>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">Status</th>
                        <th class="text-right py-3 px-4 font-semibold text-primary-900">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $schedule)
                        <tr class="border-b border-primary-100 hover:bg-primary-50 transition">
                            <td class="py-4 px-4 text-sm text-primary-600">#{{ $schedule->id }}</td>
                            <td class="py-4 px-4">
                                <p class="font-medium text-primary-900">{{ $schedule->loan->user->name }}</p>
                                <p class="text-xs text-primary-500">{{ $schedule->loan->user->email }}</p>
                            </td>
                            <td class="py-4 px-4">
                                <p class="font-medium text-primary-900">{{ $schedule->loan->book->title }}</p>
                                <p class="text-xs text-primary-500">{{ $schedule->loan->book->author }}</p>
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-block text-xs px-2 py-1 rounded-full bg-primary-100 text-primary-700">
                                    {{ ucfirst($schedule->pickup_type) }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-sm text-primary-600">
                                {{ $schedule->scheduled_at->format('M d, Y h:i A') }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-block text-xs px-2 py-1 rounded-full
                                    {{ $schedule->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $schedule->status === 'in_progress' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $schedule->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $schedule->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $schedule->status)) }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    @if($schedule->status !== 'completed' && $schedule->status !== 'cancelled')
                                        <form action="{{ route('admin.deliveries.update-status', $schedule) }}" method="POST" class="inline">
                                            @csrf
                                            <select name="status" onchange="this.form.submit()" class="text-xs px-2 py-1 rounded border border-primary-200 focus:outline-none focus:ring-2 focus:ring-primary-500">
                                                <option value="pending" {{ $schedule->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="in_progress" {{ $schedule->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                <option value="completed" {{ $schedule->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="cancelled" {{ $schedule->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                            </select>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-primary-600">
                                No delivery schedules found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($schedules->hasPages())
            <div class="mt-6">
                {{ $schedules->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>

