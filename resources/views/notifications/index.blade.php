{{-- resources/views/notifications/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-green-50 to-white">
  <div class="max-w-4xl mx-auto px-4 py-8 lg:px-6">
    
    {{-- Header Section --}}
    <div class="mb-8">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-800 mb-2">
            <i class="fas fa-bell text-green-600 mr-3"></i>
            Notifications
          </h1>
          <p class="text-gray-600">Stay updated with your waste collection reports</p>
        </div>
        
        @if(auth()->user()->unreadNotifications()->count())
          <form action="{{ route('notifications.readAll') }}" method="POST">
            @csrf
            <button class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 transition-all duration-200 shadow-lg hover:shadow-xl">
              <i class="fas fa-check-double mr-2"></i>
              Mark All Read
            </button>
          </form>
        @endif
      </div>
    </div>

    {{-- Toast Messages --}}
    @if (session('toast.error'))
      <div class="mb-6 rounded-xl bg-red-50 text-red-700 px-6 py-4 border-l-4 border-red-400 shadow-sm">
        <div class="flex items-center">
          <i class="fas fa-exclamation-circle mr-3"></i>
          {{ session('toast.error') }}
        </div>
      </div>
    @endif
    
    @if (session('toast.success'))
      <div class="mb-6 rounded-xl bg-green-50 text-green-700 px-6 py-4 border-l-4 border-green-400 shadow-sm">
        <div class="flex items-center">
          <i class="fas fa-check-circle mr-3"></i>
          {{ session('toast.success') }}
        </div>
      </div>
    @endif

    {{-- Notifications Container --}}
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
      @forelse ($notifications as $n)
        <a href="{{ route('notifications.open', $n->id) }}" class="block hover:bg-gradient-to-r hover:from-green-50 hover:to-white transition-all duration-200 border-b border-gray-100 last:border-b-0">
          <div class="px-6 py-5 flex items-start gap-4">
            {{-- Status Indicator --}}
            <div class="flex-shrink-0 mt-1">
              @if(is_null($n->read_at))
                <div class="w-3 h-3 bg-green-500 rounded-full shadow-sm animate-pulse"></div>
              @else
                <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
              @endif
            </div>

            {{-- Icon based on notification type --}}
            <div class="flex-shrink-0 mt-0.5">
              <div class="w-10 h-10 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center">
                @php
                  $status = strtolower($n->data['status'] ?? '');
                @endphp
                @if($status === 'assigned')
                  <i class="fas fa-user-check text-green-600"></i>
                @elseif($status === 'collected')
                  <i class="fas fa-check-circle text-green-600"></i>
                @elseif($status === 'closed')
                  <i class="fas fa-archive text-green-600"></i>
                @else
                  <i class="fas fa-file-alt text-green-600"></i>
                @endif
              </div>
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0">
              <div class="flex items-start justify-between">
                <div>
                  <p class="text-sm font-semibold text-gray-900">
                    Report {{ ucfirst($n->data['reason'] ?? 'Updated') }}
                  </p>
                  <p class="text-sm text-gray-600 mt-1">
                    Status changed to 
                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full 
                      @if($status === 'assigned') bg-blue-100 text-blue-800
                      @elseif($status === 'collected') bg-green-100 text-green-800  
                      @elseif($status === 'closed') bg-gray-100 text-gray-800
                      @else bg-amber-100 text-amber-800 @endif">
                      {{ ucfirst($n->data['status'] ?? '-') }}
                    </span>
                    @if(!empty($n->data['reference']))
                      • <span class="font-mono text-xs">{{ $n->data['reference'] }}</span>
                    @endif
                  </p>
                </div>
                
                {{-- Timestamp --}}
                <div class="text-xs text-gray-500 ml-4 flex-shrink-0">
                  {{ $n->created_at->diffForHumans() }}
                </div>
              </div>
            </div>

            {{-- Arrow indicator --}}
            <div class="flex-shrink-0 ml-2 mt-2">
              <i class="fas fa-chevron-right text-gray-400 text-sm"></i>
            </div>
          </div>
        </a>
      @empty
        <div class="px-6 py-12 text-center">
          <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-bell-slash text-gray-400 text-xl"></i>
          </div>
          <h3 class="text-lg font-medium text-gray-900 mb-2">No notifications yet</h3>
          <p class="text-gray-500">You'll receive notifications about your waste collection reports here.</p>
        </div>
      @endforelse
    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())
      <div class="mt-8 flex justify-center">
        <div class="bg-white rounded-xl shadow-lg px-6 py-3">
          {{ $notifications->links() }}
        </div>
      </div>
    @endif
  </div>
</div>
@endsection