@extends('adminDashboard.layout.layout')

@php
    $title = 'Notifications';
    $subTitle = 'All Notifications';
@endphp

@section('content')
    <div class="card h-full rounded-xl overflow-hidden border-0">
        <div class="card-body p-6">
            <div class="flex justify-between items-center mb-6">
                <h4 class="text-xl font-semibold text-neutral-900 dark:text-white">All Notifications</h4>
                @if ($notifications->count() > 0)
                    <form action="{{ route('admin.notifications.mark-all-as-read') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary px-3 py-1.5 text-sm font-medium">
                            Mark All as Read
                        </button>
                    </form>
                @endif
            </div>

            <div class="notifications-list">
                @forelse($notifications as $notification)
                    <div dir="rtl"
                        class="notification-item p-4 mb-3 rounded-lg {{ is_null($notification->read_at) ? 'bg-primary-50 dark:bg-primary-600/10' : 'bg-gray-700 dark:bg-neutral-700/20' }} flex justify-between items-start">
                        <div class="flex items-start gap-3">
                            <div
                                class="flex-shrink-0 relative w-11 h-11 bg-success-200 dark:bg-success-600/25 text-success-600 flex justify-center items-center rounded-full">
                                <iconify-icon icon="{{ $notification->data['icon'] ?? 'mdi:bell' }}"
                                    class="text-2xl"></iconify-icon>
                            </div>
                            <div>
                                <h6 class="text-base font-semibold mb-1">
                                    {{ $notification->data['title'] ?? 'Notification' }}</h6>
                                <p class="mb-1 text-sm text-neutral-600 dark:text-neutral-300">
                                    {{ $notification->data['message'] }}</p>
                                <span
                                    class="text-xs text-neutral-500">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="shrink-0 flex gap-2">
                            @if (is_null($notification->read_at))
                                <form action="{{ route('admin.notifications.mark-as-read', $notification->id) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="px-3 py-1.5 text-sm font-medium text-primary-600 border border-primary-600 rounded-md hover:bg-primary-50 dark:text-primary-400 dark:border-primary-400 dark:hover:bg-primary-900/50">
                                        Mark as Read
                                    </button>
                                </form>
                            @else
                                <span
                                    class="text-xs text-success-600 bg-success-100 dark:bg-success-600/20 py-1 px-2 rounded-full">Read</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <iconify-icon icon="mdi:bell-off" class="text-5xl text-neutral-400 mb-3"></iconify-icon>
                        <p class="text-neutral-500">No notifications found</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
@endsection
