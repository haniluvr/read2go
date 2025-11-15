<x-admin-layout>
    <x-slot name="header">Users Management</x-slot>

    <!-- Filters -->
    <div class="card p-4 mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-4 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-primary-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or email..." class="w-full px-4 py-2 rounded-lg border border-primary-200 focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-primary-700 mb-2">Suspended</label>
                <select name="suspended" class="px-4 py-2 rounded-lg border border-primary-200 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">All Users</option>
                    <option value="1" {{ $suspended === '1' ? 'selected' : '' }}>Suspended Only</option>
                    <option value="0" {{ $suspended === '0' ? 'selected' : '' }}>Active Only</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary px-6">Filter</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary px-6">Clear</a>
        </form>
    </div>

    <!-- Users Table -->
    <div class="card p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-primary-200">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">Name</th>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">Email</th>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">Username</th>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">Loans</th>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">Penalties</th>
                        <th class="text-left py-3 px-4 font-semibold text-primary-900">Status</th>
                        <th class="text-right py-3 px-4 font-semibold text-primary-900">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b border-primary-100 hover:bg-primary-50 transition">
                            <td class="py-4 px-4">
                                <p class="font-medium text-primary-900">{{ $user->name }}</p>
                            </td>
                            <td class="py-4 px-4 text-sm text-primary-600">{{ $user->email }}</td>
                            <td class="py-4 px-4 text-sm text-primary-600">{{ $user->username }}</td>
                            <td class="py-4 px-4 text-sm text-primary-600">{{ $user->book_loans_count ?? 0 }}</td>
                            <td class="py-4 px-4 text-sm text-primary-600">{{ $user->penalties_count ?? 0 }}</td>
                            <td class="py-4 px-4">
                                @if($user->is_suspended)
                                    <span class="inline-block text-xs px-2 py-1 rounded-full bg-red-100 text-red-700">
                                        Suspended
                                    </span>
                                @else
                                    <span class="inline-block text-xs px-2 py-1 rounded-full bg-green-100 text-green-700">
                                        Active
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}" class="text-primary-600 hover:text-primary-900 text-sm font-medium">
                                        View
                                    </a>
                                    @if($user->is_suspended)
                                        <form action="{{ route('admin.users.unsuspend', $user) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900 text-sm font-medium">
                                                Unsuspend
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.users.suspend', $user) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium" onclick="return confirm('Are you sure you want to suspend this user?')">
                                                Suspend
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-primary-600">
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-admin-layout>

