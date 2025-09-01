@extends('admin.layout.app')

@section('content')
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">System Alerts</h1>
        <p class="text-gray-600">Monitor urgent bin reports and system notifications</p>
    </div>
    
    @if($notifications && $notifications->where('read_at', null)->count() > 0)
        <form action="{{ route('admin.alerts.markAllRead') }}" method="POST">
            @csrf
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                <i class="fas fa-check-double mr-2"></i>
                Mark All Read
            </button>
        </form>
    @endif
</div>

{{-- Success/Error Messages --}}
@if(session('success'))
    <div class="mb-6 rounded-xl bg-green-50 text-green-700 px-6 py-4 border-l-4 border-green-400 shadow-sm">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-3"></i>
            {{ session('success') }}
        </div>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 rounded-xl bg-red-50 text-red-700 px-6 py-4 border-l-4 border-red-400 shadow-sm">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle mr-3"></i>
            {{ session('error') }}
        </div>
    </div>
@endif

{{-- Notifications Container --}}
<div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
    @forelse ($notifications as $notification)
        <div class="border-b border-gray-100 last:border-b-0 {{ is_null($notification->read_at) ? 'bg-red-50' : 'bg-white' }} hover:bg-gray-50 transition-colors duration-200">
            <div class="px-6 py-5 flex items-start gap-4">
                {{-- Status Indicator --}}
                <div class="flex-shrink-0 mt-1">
                    @if(is_null($notification->read_at))
                        <div class="w-3 h-3 bg-red-500 rounded-full shadow-sm animate-pulse"></div>
                    @else
                        <div class="w-3 h-3 bg-gray-300 rounded-full"></div>
                    @endif
                </div>

                {{-- Icon --}}
                <div class="flex-shrink-0 mt-0.5">
                    <div class="w-12 h-12 bg-gradient-to-br {{ is_null($notification->read_at) ? 'from-red-100 to-red-200' : 'from-gray-100 to-gray-200' }} rounded-xl flex items-center justify-center">
                        @if($notification->data['type'] === 'urgent_bin_report')
                            <i class="fas fa-exclamation-triangle {{ is_null($notification->read_at) ? 'text-red-600' : 'text-gray-600' }} text-lg"></i>
                        @elseif($notification->data['type'] === 'new_waste_report')
                            <i class="fas fa-trash-alt {{ is_null($notification->read_at) ? 'text-blue-600' : 'text-gray-600' }} text-lg"></i>
                        @else
                            <i class="fas fa-bell {{ is_null($notification->read_at) ? 'text-red-600' : 'text-gray-600' }}"></i>
                        @endif
                    </div>
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-lg font-bold {{ is_null($notification->read_at) ? 'text-red-900' : 'text-gray-900' }}">
                                {{ $notification->data['title'] ?? 'System Alert' }}
                            </p>
                            <p class="text-sm {{ is_null($notification->read_at) ? 'text-red-700' : 'text-gray-600' }} mt-1">
                                {{ $notification->data['message'] ?? 'No message available' }}
                            </p>
                            
                            {{-- Report Details --}}
                            @if(isset($notification->data['reference_code']))
                                <div class="mt-3 space-y-1">
                                    <p class="text-sm text-gray-700">
                                        <strong>Report:</strong> {{ $notification->data['reference_code'] }}
                                    </p>
                                    @if(isset($notification->data['location']))
                                        <p class="text-sm text-gray-700">
                                            <strong>Location:</strong> {{ $notification->data['location'] }}
                                        </p>
                                    @endif
                                    @if(isset($notification->data['waste_type']))
                                        <p class="text-sm text-gray-700">
                                            <strong>Waste Type:</strong> {{ $notification->data['waste_type'] }}
                                        </p>
                                    @endif
                                    @if(isset($notification->data['urgent_message']))
                                        <p class="text-sm text-gray-700">
                                            <strong>Resident Message:</strong> "{{ $notification->data['urgent_message'] }}"
                                        </p>
                                    @endif
                                </div>
                            @endif

                            {{-- Status Badge --}}
                            @if(isset($notification->data['status']))
                                <div class="mt-2">
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full 
                                        @if($notification->data['status'] === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($notification->data['status'] === 'assigned') bg-blue-100 text-blue-800
                                        @elseif($notification->data['status'] === 'collected') bg-green-100 text-green-800  
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($notification->data['status']) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        {{-- Timestamp --}}
                        <div class="text-xs {{ is_null($notification->read_at) ? 'text-red-500' : 'text-gray-500' }} ml-4 flex-shrink-0 text-right">
                            <div>{{ $notification->created_at->format('M d, Y') }}</div>
                            <div>{{ $notification->created_at->format('h:i A') }}</div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="mt-4 flex items-center space-x-3">
                        @if(isset($notification->data['report_id']))
                            <a href="{{ route('admin.reports.show', $notification->data['report_id']) }}" 
                               class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-eye mr-2"></i>
                                View Report
                            </a>
                        @endif
                        
                        @if(is_null($notification->read_at))
                            <form action="{{ route('admin.alerts.markRead', $notification->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                    <i class="fas fa-check mr-2"></i>
                                    Mark as Read
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="px-6 py-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-bell-slash text-gray-400 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Alerts</h3>
            <p class="text-gray-500">All system alerts and urgent notifications will appear here.</p>
        </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($notifications && $notifications->hasPages())
    <div class="mt-8 flex justify-center">
        <div class="bg-white rounded-xl shadow-lg px-6 py-3">
            {{ $notifications->links() }}
        </div>
    </div>
@endif

@endsection