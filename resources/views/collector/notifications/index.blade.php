@extends('layouts.collector')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-orange-50 to-white dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <div class="max-w-4xl mx-auto px-4 py-8 lg:px-6">
        
        {{-- Header Section --}}
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200 mb-2">
                        <i class="fas fa-bell text-orange-600 dark:text-orange-400 mr-3"></i>
                        Notifications
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">Stay updated with your waste collection assignments</p>
                </div>
                
                @if(auth()->user()->unreadNotifications()->count())
                    <button onclick="markAllAsRead()" 
                            class="px-4 py-2 bg-orange-600 dark:bg-orange-700 text-white rounded-lg hover:bg-orange-700 dark:hover:bg-orange-800 transition-colors duration-200 font-medium">
                        <i class="fas fa-check-double mr-2"></i>
                        Mark All as Read
                    </button>
                @endif
            </div>
        </div>

        {{-- Notifications List --}}
        <div class="space-y-4">
            @forelse($notifications as $notification)
                <div class="bg-white rounded-lg border {{ $notification->read_at ? 'border-gray-200' : 'border-orange-200 border-l-4 border-l-orange-500' }} shadow-sm hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4 flex-1">
                                {{-- Icon based on notification type --}}
                                <div class="flex-shrink-0 mt-1">
                                    @if(($notification->data['type'] ?? '') === 'report_assigned')
                                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-clipboard-list text-orange-600 text-lg"></i>
                                        </div>
                                    @else
                                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-info-circle text-blue-600 text-lg"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900">
                                            {{ $notification->data['message'] ?? 'New Notification' }}
                                        </h3>
                                        
                                        @if(!$notification->read_at)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                New
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Reference and details --}}
                                    @if(isset($notification->data['reference']))
                                        <p class="text-sm text-gray-600 mb-2">
                                            <strong>Reference:</strong> {{ $notification->data['reference'] }}
                                        </p>
                                    @endif

                                    @if(isset($notification->data['waste_type']))
                                        <p class="text-sm text-gray-600 mb-2">
                                            <strong>Waste Type:</strong> {{ ucfirst($notification->data['waste_type']) }}
                                        </p>
                                    @endif

                                    @if(isset($notification->data['location']))
                                        <p class="text-sm text-gray-600 mb-2">
                                            <strong>Location:</strong> {{ $notification->data['location'] }}
                                        </p>
                                    @endif

                                    @if(isset($notification->data['resident_name']))
                                        <p class="text-sm text-gray-600 mb-2">
                                            <strong>Resident:</strong> {{ $notification->data['resident_name'] }}
                                        </p>
                                    @endif

                                    {{-- Urgency indicator --}}
                                    @if(($notification->data['urgency'] ?? '') === 'urgent')
                                        <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 mb-3">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            Urgent
                                        </div>
                                    @endif

                                    {{-- Timestamp --}}
                                    <p class="text-xs text-gray-500">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center space-x-2 ml-4">
                                @if(isset($notification->data['url']) && $notification->data['url'] !== '#')
                                    <a href="{{ route('collector.notifications.show', $notification->id) }}" 
                                       class="px-3 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors duration-200 text-sm font-medium">
                                        <i class="fas fa-eye mr-1"></i>
                                        View
                                    </a>
                                @endif

                                @if(!$notification->read_at)
                                    <button onclick="markAsRead('{{ $notification->id }}')" 
                                            class="px-3 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors duration-200 text-sm font-medium">
                                        <i class="fas fa-check mr-1"></i>
                                        Mark Read
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                    <div class="px-6 py-12 text-center">
                        <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-bell-slash text-gray-400 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No notifications yet</h3>
                        <p class="text-gray-500">You'll see notifications here when reports are assigned to you.</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($notifications->hasPages())
            <div class="mt-8">
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
