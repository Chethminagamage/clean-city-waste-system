{{-- resources/views/notifications/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-green-50 to-white dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
  <div class="max-w-4xl mx-auto px-4 py-8 lg:px-6">
    
    {{-- Header Section --}}
    <div class="mb-8">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">
            <i class="fas fa-bell text-green-600 dark:text-green-400 mr-3"></i>
            Notifications
          </h1>
          <p class="text-gray-600 dark:text-gray-300">Stay updated with your waste collection reports</p>
        </div>
        
        @if(auth()->user()->unreadNotifications()->count())
          <form action="{{ route('notifications.readAll') }}" method="POST">
            @csrf
            <button class="inline-flex items-center px-4 py-2 bg-green-600 dark:bg-green-700 text-white font-medium rounded-xl hover:bg-green-700 dark:hover:bg-green-800 transition-all duration-200 shadow-lg hover:shadow-xl">
              <i class="fas fa-check-double mr-2"></i>
              Mark All Read
            </button>
          </form>
        @endif
      </div>
    </div>

    {{-- Toast Messages --}}
    @if (session('toast.error'))
      <div class="mb-6 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 px-6 py-4 border-l-4 border-red-400 dark:border-red-500 shadow-sm">
        <div class="flex items-center">
          <i class="fas fa-exclamation-circle mr-3"></i>
          {{ session('toast.error') }}
        </div>
      </div>
    @endif
    
    @if (session('toast.success'))
      <div class="mb-6 rounded-xl bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 px-6 py-4 border-l-4 border-green-400 dark:border-green-500 shadow-sm">
        <div class="flex items-center">
          <i class="fas fa-check-circle mr-3"></i>
          {{ session('toast.success') }}
        </div>
      </div>
    @endif

    {{-- Notifications Container --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700">
      @forelse ($notifications as $n)
  <a href="{{ route('notifications.open', $n->id) }}" class="block hover:bg-gradient-to-r hover:from-green-50 hover:to-white dark:hover:from-green-900/20 dark:hover:to-gray-800 transition-all duration-200 border-b border-gray-100 dark:border-gray-700 last:border-b-0">
          <div class="px-6 py-5 flex items-start gap-4">
            {{-- Status Indicator --}}
            <div class="flex-shrink-0 mt-1">
              @if(is_null($n->read_at))
                <div class="w-3 h-3 bg-green-500 dark:bg-green-400 rounded-full shadow-sm animate-pulse"></div>
              @else
                <div class="w-3 h-3 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
              @endif
            </div>

            {{-- Icon based on notification type --}}
            <div class="flex-shrink-0 mt-0.5">
              @php
                $notificationType = $n->data['type'] ?? '';
                $status = strtolower($n->data['status'] ?? '');
              @endphp
              
              @if($notificationType === 'feedback_response')
                <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/50 dark:to-blue-800/50 rounded-xl flex items-center justify-center">
                  <i class="fas fa-reply text-blue-600 dark:text-blue-400"></i>
                </div>
              @else
                <div class="w-10 h-10 bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900/50 dark:to-green-800/50 rounded-xl flex items-center justify-center">
                  @if($status === 'assigned')
                    <i class="fas fa-user-check text-green-600 dark:text-green-400"></i>
                  @elseif($status === 'collected')
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                  @elseif($status === 'closed')
                    <i class="fas fa-archive text-green-600 dark:text-green-400"></i>
                  @else
                    <i class="fas fa-file-alt text-green-600 dark:text-green-400"></i>
                  @endif
                </div>
              @endif
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between">
                <div>
                  @if($notificationType === 'feedback_response')
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                      Response to Your Feedback
                    </p>
                  @elseif(isset($n->data['reason']))
                    <p class="text-sm font-semibold text-gray-900 dark:text-white">
                      Report {{ ucfirst($n->data['reason']) }}
                    </p>
                  @endif
                  <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                    {{-- Always show message if present --}}
                    @if(!empty($n->data['message']))
                      {{ $n->data['message'] }}
                    @endif
                    @if($notificationType === 'feedback_response' && !empty($n->data['feedback_type']))
                      <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 ml-1">
                        {{ ucfirst(str_replace('_', ' ', $n->data['feedback_type'])) }}
                      </span>
                    @endif
                    @if($notificationType !== 'feedback_response')
                      @if(isset($n->data['status']))
                        Status changed to 
                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full 
                          @if($status === 'assigned') bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300
                          @elseif($status === 'collected') bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                          @elseif($status === 'closed') bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                          @else bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300 @endif">
                          {{ ucfirst($n->data['status']) }}
                        </span>
                      @endif
                      @if(!empty($n->data['reference']))
                        • <span class="font-mono text-xs">{{ $n->data['reference'] }}</span>
                      @endif
                    @endif
                  </p>
                </div>
                
                {{-- Timestamp --}}
                <div class="text-xs text-gray-500 dark:text-gray-400 ml-4 flex-shrink-0">
                  {{ $n->created_at->diffForHumans() }}
                </div>
              </div>
            </div>

            {{-- Arrow indicator --}}
            <div class="flex-shrink-0 ml-2 mt-2">
              <i class="fas fa-chevron-right text-gray-400 dark:text-gray-500 text-sm"></i>
            </div>
          </div>
        </a>
      @empty
        <div class="px-6 py-12 text-center">
          <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-bell-slash text-gray-400 dark:text-gray-500 text-xl"></i>
          </div>
          <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No notifications yet</h3>
          <p class="text-gray-500 dark:text-gray-400">You'll receive notifications about your waste collection reports here.</p>
        </div>
      @endforelse
    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())
      <div class="mt-8 flex justify-center">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg px-6 py-3">
          {{ $notifications->links() }}
        </div>
      </div>
    @endif
  </div>
</div>
@endsection