{{-- resources/views/notifications/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-green-50 to-white dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
  <div class="max-w-4xl mx-auto px-3 sm:px-4 lg:px-6 py-4 sm:py-6 lg:py-8">
    
    {{-- Header Section --}}
    <div class="mb-4 sm:mb-6 lg:mb-8">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
        <div>
          <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-800 dark:text-white mb-1 sm:mb-2">
            <i class="fas fa-bell text-green-600 dark:text-green-400 mr-2 sm:mr-3 text-lg sm:text-xl lg:text-2xl"></i>
            Notifications
          </h1>
          <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300">Stay updated with your waste collection reports</p>
        </div>
        
        @if(auth()->user()->unreadNotifications()->count())
          <form action="{{ route('notifications.readAll') }}" method="POST">
            @csrf
            <button class="inline-flex items-center px-3 py-2 sm:px-4 sm:py-2 bg-green-600 dark:bg-green-700 text-white font-medium rounded-lg sm:rounded-xl hover:bg-green-700 dark:hover:bg-green-800 transition-all duration-200 shadow-lg hover:shadow-xl text-sm sm:text-base">
              <i class="fas fa-check-double mr-1.5 sm:mr-2 text-sm sm:text-base"></i>
              <span class="hidden sm:inline">Mark All Read</span>
              <span class="sm:hidden">Mark Read</span>
            </button>
          </form>
        @endif
      </div>
    </div>

    {{-- Toast Messages --}}
    @if (session('toast.error'))
      <div class="mb-3 sm:mb-4 lg:mb-6 rounded-lg sm:rounded-xl bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 px-3 sm:px-4 lg:px-6 py-3 sm:py-4 border-l-4 border-red-400 dark:border-red-500 shadow-sm">
        <div class="flex items-center">
          <i class="fas fa-exclamation-circle mr-2 sm:mr-3 text-sm sm:text-base"></i>
          <span class="text-sm sm:text-base">{{ session('toast.error') }}</span>
        </div>
      </div>
    @endif
    
    @if (session('toast.success'))
      <div class="mb-3 sm:mb-4 lg:mb-6 rounded-lg sm:rounded-xl bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 px-3 sm:px-4 lg:px-6 py-3 sm:py-4 border-l-4 border-green-400 dark:border-green-500 shadow-sm">
        <div class="flex items-center">
          <i class="fas fa-check-circle mr-2 sm:mr-3 text-sm sm:text-base"></i>
          <span class="text-sm sm:text-base">{{ session('toast.success') }}</span>
        </div>
      </div>
    @endif

    {{-- Notifications Container --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl lg:rounded-2xl shadow-lg sm:shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700">
      @forelse ($notifications as $n)
  <a href="{{ route('notifications.open', $n->id) }}" class="block hover:bg-gradient-to-r hover:from-green-50 hover:to-white dark:hover:from-green-900/20 dark:hover:to-gray-800 transition-all duration-200 border-b border-gray-100 dark:border-gray-700 last:border-b-0">
          <div class="px-3 sm:px-4 lg:px-6 py-3 sm:py-4 lg:py-5 flex items-start gap-2 sm:gap-3 lg:gap-4">
            {{-- Status Indicator --}}
            <div class="flex-shrink-0 mt-1">
              @if(is_null($n->read_at))
                <div class="w-2 h-2 sm:w-3 sm:h-3 bg-green-500 dark:bg-green-400 rounded-full shadow-sm animate-pulse"></div>
              @else
                <div class="w-2 h-2 sm:w-3 sm:h-3 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
              @endif
            </div>

            {{-- Icon based on notification type --}}
            <div class="flex-shrink-0 mt-0.5">
              @php
                $notificationType = $n->data['type'] ?? '';
                $status = strtolower($n->data['status'] ?? '');
              @endphp
              
              @if($notificationType === 'feedback_response')
                <div class="w-8 h-8 sm:w-9 sm:h-9 lg:w-10 lg:h-10 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/50 dark:to-blue-800/50 rounded-lg sm:rounded-xl flex items-center justify-center">
                  <i class="fas fa-reply text-blue-600 dark:text-blue-400 text-sm sm:text-base"></i>
                </div>
              @else
                <div class="w-8 h-8 sm:w-9 sm:h-9 lg:w-10 lg:h-10 bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900/50 dark:to-green-800/50 rounded-lg sm:rounded-xl flex items-center justify-center">
                  @if($status === 'assigned')
                    <i class="fas fa-user-check text-green-600 dark:text-green-400 text-sm sm:text-base"></i>
                  @elseif($status === 'collected')
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-sm sm:text-base"></i>
                  @elseif($status === 'closed')
                    <i class="fas fa-archive text-green-600 dark:text-green-400 text-sm sm:text-base"></i>
                  @else
                    <i class="fas fa-file-alt text-green-600 dark:text-green-400 text-sm sm:text-base"></i>
                  @endif
                </div>
              @endif
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0">
              <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-1 sm:gap-2">
                <div class="flex-1 min-w-0">
                  @if($notificationType === 'feedback_response')
                    <p class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white truncate sm:whitespace-normal">
                      Response to Your Feedback
                    </p>
                  @elseif(isset($n->data['reason']))
                    <p class="text-sm sm:text-base font-semibold text-gray-900 dark:text-white truncate sm:whitespace-normal">
                      Report {{ ucfirst($n->data['reason']) }}
                    </p>
                  @endif
                  <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-300 mt-0.5 sm:mt-1 line-clamp-2 sm:line-clamp-none">
                    {{-- Always show message if present --}}
                    @if(!empty($n->data['message']))
                      {{ $n->data['message'] }}
                    @endif
                    @if($notificationType === 'feedback_response' && !empty($n->data['feedback_type']))
                      <span class="inline-flex px-1.5 py-0.5 text-xs font-medium rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 ml-1">
                        {{ ucfirst(str_replace('_', ' ', $n->data['feedback_type'])) }}
                      </span>
                    @endif
                    @if($notificationType !== 'feedback_response')
                      @if(isset($n->data['status']))
                        <span class="hidden sm:inline">Status changed to </span>
                        <span class="inline-flex px-1.5 py-0.5 text-xs font-medium rounded-full 
                          @if($status === 'assigned') bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300
                          @elseif($status === 'collected') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                          @elseif($status === 'closed') bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                          @else bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300 @endif">
                          {{ ucfirst($n->data['status']) }}
                        </span>
                      @endif
                      @if(!empty($n->data['reference']))
                        <span class="hidden sm:inline"> • </span>
                        <span class="font-mono text-xs block sm:inline mt-0.5 sm:mt-0">{{ $n->data['reference'] }}</span>
                      @endif
                    @endif
                  </p>
                </div>
                
                {{-- Timestamp --}}
                <div class="text-xs text-gray-500 dark:text-gray-400 sm:ml-4 flex-shrink-0 mt-1 sm:mt-0">
                  {{ $n->created_at->diffForHumans() }}
                </div>
              </div>
            </div>

            {{-- Arrow indicator --}}
            <div class="flex-shrink-0 ml-1 sm:ml-2 mt-1 sm:mt-2">
              <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500 text-xs sm:text-sm"></i>
            </div>
          </div>
        </a>
      @empty
        <div class="px-4 sm:px-6 py-8 sm:py-12 text-center">
          <div class="w-12 h-12 sm:w-14 sm:h-14 lg:w-16 lg:h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
            <i class="fas fa-bell-slash text-gray-400 dark:text-gray-500 text-lg sm:text-xl"></i>
          </div>
          <h3 class="text-base sm:text-lg font-medium text-gray-900 dark:text-white mb-2">No notifications yet</h3>
          <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400">You'll receive notifications about your waste collection reports here.</p>
        </div>
      @endforelse
    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())
      <div class="mt-4 sm:mt-6 lg:mt-8 flex justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-lg sm:rounded-xl shadow-lg px-3 sm:px-4 lg:px-6 py-2 sm:py-3">
          {{ $notifications->links() }}
        </div>
      </div>
    @endif
  </div>
</div>
@endsection