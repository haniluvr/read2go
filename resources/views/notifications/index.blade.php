@extends('layouts.mobile')

@section('content')
<div class="px-4 py-6">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-primary-900 mb-2">Notifications</h1>
        <p class="text-sm text-primary-600">Stay updated on your book loans</p>
    </div>

    @if($notifications && $notifications->count() > 0)
        <div class="space-y-3">
            @foreach($notifications as $notification)
                <div class="card p-4 {{ !$notification->is_read ? 'bg-primary-50 border-l-4 border-primary-500' : '' }}">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-1">
                            @if($notification->type === 'book_ready')
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @elseif($notification->type === 'due_reminder')
                                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="font-semibold text-primary-900 mb-1">{{ $notification->title }}</h3>
                            <p class="text-sm text-primary-600 mb-2">{{ $notification->message }}</p>
                            <p class="text-xs text-primary-500">{{ $notification->created_at->diffForHumans() }}</p>
                            @if($notification->bookLoan)
                                <a href="{{ route('loans.show', $notification->bookLoan) }}" class="text-xs text-primary-600 hover:text-primary-700 mt-2 inline-block">
                                    View Loan →
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($notifications->hasPages())
            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 mx-auto mb-4 text-primary-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
            </svg>
            <h3 class="text-lg font-bold text-primary-900 mb-2">No notifications</h3>
            <p class="text-sm text-primary-600">You're all caught up!</p>
        </div>
    @endif
</div>
@endsection

