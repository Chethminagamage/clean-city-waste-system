@extends('layouts.collector')

@section('content')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>

<div class="min-h-screen bg-gradient-to-br from-gray-50 via-orange-50 to-white dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="max-w-4xl mx-auto px-3 sm:px-4 py-4 sm:py-6 lg:py-8 lg:px-6">
        
        {{-- Header Section --}}
        <div class="mb-4 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800 dark:text-gray-200 mb-1 sm:mb-2">
                        <i class="fas fa-bell text-orange-600 dark:text-orange-400 mr-2 sm:mr-3 text-lg sm:text-xl lg:text-2xl"></i>
                        Notifications
                    </h1>
                    <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400">Stay updated with your waste collection assignments</p>
                </div>
                
                @if(auth()->user()->unreadNotifications()->count())
                    <button onclick="markAllAsRead()" 
                            class="px-3 sm:px-4 py-2 bg-orange-600 dark:bg-orange-700 text-white rounded-lg hover:bg-orange-700 dark:hover:bg-orange-800 transition-colors duration-200 font-medium text-sm sm:text-base whitespace-nowrap">
                        <i class="fas fa-check-double mr-1 sm:mr-2"></i>
                        Mark All as Read
                    </button>
                @endif
            </div>
        </div>

        {{-- Notifications List --}}
        <div class="space-y-2 sm:space-y-4">
            @forelse($notifications as $notification)
                <div class="bg-white rounded-lg border {{ $notification->read_at ? 'border-gray-200' : 'border-orange-200 border-l-4 border-l-orange-500' }} shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="p-3 sm:p-4 lg:p-6">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-start space-x-2 sm:space-x-4 flex-1 min-w-0">
                                {{-- Icon based on notification type --}}
                                <div class="flex-shrink-0 mt-0.5 sm:mt-1">
                                    @if(($notification->data['type'] ?? '') === 'report_assigned')
                                        <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-orange-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-clipboard-list text-orange-600 text-sm sm:text-base lg:text-lg"></i>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 sm:w-10 sm:h-10 lg:w-12 lg:h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-info-circle text-blue-600 text-sm sm:text-base lg:text-lg"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between mb-1 sm:mb-2 gap-2">
                                        <h3 class="text-sm sm:text-base lg:text-lg font-semibold text-gray-900 line-clamp-2 flex-1">
                                            {{ $notification->data['message'] ?? 'New Notification' }}
                                        </h3>
                                        
                                        @if(!$notification->read_at)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 flex-shrink-0">
                                                New
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Compact details grid for mobile --}}
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-1 sm:gap-2 text-xs sm:text-sm text-gray-600 mb-2">
                                        @if(isset($notification->data['reference']))
                                            <p class="truncate">
                                                <strong>Ref:</strong> {{ $notification->data['reference'] }}
                                            </p>
                                        @endif

                                        @if(isset($notification->data['waste_type']))
                                            <p class="truncate">
                                                <strong>Type:</strong> {{ ucfirst($notification->data['waste_type']) }}
                                            </p>
                                        @endif

                                        @if(isset($notification->data['location']))
                                            <p class="col-span-full sm:col-span-2 truncate">
                                                <strong>Location:</strong> {{ $notification->data['location'] }}
                                            </p>
                                        @endif

                                        @if(isset($notification->data['resident_name']))
                                            <p class="truncate">
                                                <strong>Resident:</strong> {{ $notification->data['resident_name'] }}
                                            </p>
                                        @endif
                                    </div>

                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            {{-- Urgency indicator --}}
                                            @if(($notification->data['urgency'] ?? '') === 'urgent')
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-exclamation-triangle mr-1 text-xs"></i>
                                                    Urgent
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Timestamp --}}
                                        <p class="text-xs text-gray-500 flex items-center">
                                            <i class="fas fa-clock mr-1 text-xs"></i>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex flex-col sm:flex-row items-end sm:items-center space-y-1 sm:space-y-0 sm:space-x-2 ml-2 sm:ml-4">
                                @if(isset($notification->data['url']) && $notification->data['url'] !== '#')
                                    <a href="{{ route('collector.notifications.show', $notification->id) }}" 
                                       class="px-2.5 sm:px-3 py-1.5 sm:py-2 bg-orange-600 text-white rounded-md sm:rounded-lg hover:bg-orange-700 transition-colors duration-200 text-xs sm:text-sm font-medium whitespace-nowrap">
                                        <i class="fas fa-eye mr-1"></i>
                                        View
                                    </a>
                                @endif

                                @if(!$notification->read_at)
                                    <button onclick="markAsRead('{{ $notification->id }}')" 
                                            class="px-2.5 sm:px-3 py-1.5 sm:py-2 bg-gray-600 text-white rounded-md sm:rounded-lg hover:bg-gray-700 transition-colors duration-200 text-xs sm:text-sm font-medium whitespace-nowrap">
                                        <i class="fas fa-check mr-1"></i>
                                        <span class="hidden sm:inline">Mark Read</span>
                                        <span class="sm:hidden">Read</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                    <div class="px-4 sm:px-6 py-8 sm:py-12 text-center">
                        <div class="mx-auto w-16 h-16 sm:w-20 sm:h-20 lg:w-24 lg:h-24 bg-gray-100 rounded-full flex items-center justify-center mb-3 sm:mb-4">
                            <i class="fas fa-bell-slash text-gray-400 text-lg sm:text-xl lg:text-2xl"></i>
                        </div>
                        <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-1 sm:mb-2">No notifications yet</h3>
                        <p class="text-sm sm:text-base text-gray-500 px-4">You'll see notifications here when reports are assigned to you.</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($notifications->hasPages())
            <div class="mt-4 sm:mt-6 lg:mt-8">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    async function markAsRead(notificationId) {
        try {
            const response = await fetch(`/collector/notifications/${notificationId}/mark-read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                location.reload();
            }
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }

    async function markAllAsRead() {
        try {
            const response = await fetch('/collector/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                location.reload();
            }
        } catch (error) {
            console.error('Error marking all notifications as read:', error);
        }
    }
</script>
@endsection
