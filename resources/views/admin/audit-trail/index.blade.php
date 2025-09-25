@extends('admin.layout.app')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    
    <!-- Page header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <!-- Left: Title -->
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-gray-800 font-bold">Activity Audit Trail</h1>
            <p class="text-gray-600">Monitor and track all system activities and security events</p>
        </div>

        <!-- Right: Actions -->
        <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">
            <a href="{{ route('admin.audit-trail.export', request()->query()) }}" 
               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                <i class="fas fa-download text-sm mr-2"></i>
                <span>Export CSV</span>
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 col-span-full sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
        
        <!-- Today's Activities -->
        <div class="flex flex-col col-span-full sm:col-span-1 bg-white shadow-lg rounded-lg border border-gray-200">
            <div class="px-5 pt-5">
                <h2 class="text-lg text-gray-800 font-semibold mb-2">Today</h2>
                <div class="text-xs font-semibold text-gray-400 uppercase mb-1">Activities Today</div>
                <div class="flex items-start">
                    <div class="text-3xl font-bold text-gray-800 mr-2">{{ number_format($stats['today']) }}</div>
                </div>
            </div>
            <div class="px-5 pb-5">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">System Events</div>
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar-day text-blue-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Failed Logins -->
        <div class="flex flex-col col-span-full sm:col-span-1 bg-white shadow-lg rounded-lg border border-gray-200">
            <div class="px-5 pt-5">
                <h2 class="text-lg text-gray-800 font-semibold mb-2">Failed Logins</h2>
                <div class="text-xs font-semibold text-gray-400 uppercase mb-1">Today</div>
                <div class="flex items-start">
                    <div class="text-3xl font-bold text-gray-800 mr-2">{{ number_format($stats['failed_logins_today']) }}</div>
                </div>
            </div>
            <div class="px-5 pb-5">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">Security Alerts</div>
                    <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-amber-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Alerts -->
        <div class="flex flex-col col-span-full sm:col-span-1 bg-white shadow-lg rounded-lg border border-gray-200">
            <div class="px-5 pt-5">
                <h2 class="text-lg text-gray-800 font-semibold mb-2">Security Alerts</h2>
                <div class="text-xs font-semibold text-gray-400 uppercase mb-1">This Week</div>
                <div class="flex items-start">
                    <div class="text-3xl font-bold text-gray-800 mr-2">{{ number_format($stats['security_events_week']) }}</div>
                </div>
            </div>
            <div class="px-5 pb-5">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">High Priority Events</div>
                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-orange-500"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Critical Events -->
        <div class="flex flex-col col-span-full sm:col-span-1 bg-white shadow-lg rounded-lg border border-gray-200">
            <div class="px-5 pt-5">
                <h2 class="text-lg text-gray-800 font-semibold mb-2">Critical Events</h2>
                <div class="text-xs font-semibold text-gray-400 uppercase mb-1">This Month</div>
                <div class="flex items-start">
                    <div class="text-3xl font-bold text-gray-800 mr-2">{{ number_format($stats['critical_events_month']) }}</div>
                </div>
            </div>
            <div class="px-5 pb-5">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">Urgent Issues</div>
                    <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Filters -->
    <div class="col-span-full xl:col-span-8 bg-white shadow-lg rounded-xl border border-gray-200 mb-8 overflow-hidden">
        <header class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-filter text-blue-600"></i>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-800 text-lg">Advanced Filters</h2>
                    <p class="text-sm text-gray-600">Filter activities by criteria</p>
                </div>
            </div>
        </header>
        <div class="p-6 bg-gray-50">
            <form method="GET" action="{{ route('admin.audit-trail.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                    
                    <!-- Actor Type -->
                    <div>
                        <label for="actor_type" class="block text-sm font-medium text-gray-700 mb-1">Actor Type</label>
                        <select class="form-select w-full" id="actor_type" name="actor_type">
                            <option value="">All Types</option>
                            <option value="App\Models\User" {{ request('actor_type') == 'App\Models\User' ? 'selected' : '' }}>Residents</option>
                            <option value="App\Models\Admin" {{ request('actor_type') == 'App\Models\Admin' ? 'selected' : '' }}>Admins</option>
                            <option value="App\Models\Collector" {{ request('actor_type') == 'App\Models\Collector' ? 'selected' : '' }}>Collectors</option>
                        </select>
                    </div>
                    
                    <!-- Event Type -->
                    <div>
                        <label for="event_type" class="block text-sm font-medium text-gray-700 mb-1">Event Type</label>
                        <select class="form-select w-full" id="event_type" name="event_type">
                            <option value="">All Events</option>
                            <option value="login" {{ request('event_type') == 'login' ? 'selected' : '' }}>Login</option>
                            <option value="logout" {{ request('event_type') == 'logout' ? 'selected' : '' }}>Logout</option>
                            <option value="failed_login" {{ request('event_type') == 'failed_login' ? 'selected' : '' }}>Failed Login</option>
                            <option value="create" {{ request('event_type') == 'create' ? 'selected' : '' }}>Create</option>
                            <option value="update" {{ request('event_type') == 'update' ? 'selected' : '' }}>Update</option>
                            <option value="delete" {{ request('event_type') == 'delete' ? 'selected' : '' }}>Delete</option>
                            <option value="view" {{ request('event_type') == 'view' ? 'selected' : '' }}>View</option>
                        </select>
                    </div>
                    
                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select class="form-select w-full" id="category" name="category">
                            <option value="">All Categories</option>
                            <option value="authentication" {{ request('category') == 'authentication' ? 'selected' : '' }}>Authentication</option>
                            <option value="report_management" {{ request('category') == 'report_management' ? 'selected' : '' }}>Report Management</option>
                            <option value="user_management" {{ request('category') == 'user_management' ? 'selected' : '' }}>User Management</option>
                            <option value="security" {{ request('category') == 'security' ? 'selected' : '' }}>Security</option>
                            <option value="admin_action" {{ request('category') == 'admin_action' ? 'selected' : '' }}>Admin Actions</option>
                        </select>
                    </div>
                    
                    <!-- Severity -->
                    <div>
                        <label for="severity" class="block text-sm font-medium text-gray-700 mb-1">Severity</label>
                        <select class="form-select w-full" id="severity" name="severity">
                            <option value="">All Severities</option>
                            <option value="low" {{ request('severity') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ request('severity') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ request('severity') == 'high' ? 'selected' : '' }}>High</option>
                            <option value="critical" {{ request('severity') == 'critical' ? 'selected' : '' }}>Critical</option>
                        </select>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                    <!-- Date From -->
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                        <input type="date" class="form-input w-full" id="date_from" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    
                    <!-- Date To -->
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                        <input type="date" class="form-input w-full" id="date_to" name="date_to" value="{{ request('date_to') }}">
                    </div>
                    
                    <!-- Search -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" class="form-input w-full" id="search" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search by description, email, or IP address...">
                    </div>
                </div>
                
                <div class="flex flex-wrap justify-end space-x-3 pt-6">
                    <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                        <i class="fas fa-search text-sm mr-2"></i>
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.audit-trail.index') }}" class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                        <i class="fas fa-times text-sm mr-2"></i>
                        Clear Filters
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Activity Logs Table -->
    <div class="col-span-full xl:col-span-8 bg-white shadow-lg rounded-xl border border-gray-200 overflow-hidden">
        <header class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-list-alt text-indigo-600"></i>
                    </div>
                    <div>
                        <h2 class="font-semibold text-gray-800 text-lg">Activity Logs</h2>
                        <p class="text-sm text-gray-600">{{ $activities->total() }} total activities (showing {{ $activities->count() }})</p>
                    </div>
                </div>
            </div>
        </header>
        <div class="p-3">
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="table-auto w-full">
                    <!-- Table header -->
                    <thead class="text-xs font-semibold uppercase text-gray-400 bg-gray-50">
                        <tr>
                            <th class="p-2 whitespace-nowrap">
                                <div class="font-semibold text-left">Date/Time</div>
                            </th>
                            <th class="p-2 whitespace-nowrap">
                                <div class="font-semibold text-left">Actor</div>
                            </th>
                            <th class="p-2 whitespace-nowrap">
                                <div class="font-semibold text-left">Event</div>
                            </th>
                            <th class="p-2 whitespace-nowrap">
                                <div class="font-semibold text-left">Category</div>
                            </th>
                            <th class="p-2 whitespace-nowrap">
                                <div class="font-semibold text-left">Description</div>
                            </th>
                            <th class="p-2 whitespace-nowrap">
                                <div class="font-semibold text-left">IP Address</div>
                            </th>
                            <th class="p-2 whitespace-nowrap">
                                <div class="font-semibold text-center">Severity</div>
                            </th>
                            <th class="p-2 whitespace-nowrap">
                                <div class="font-semibold text-center">Status</div>
                            </th>
                            <th class="p-2 whitespace-nowrap">
                                <div class="font-semibold text-center">Actions</div>
                            </th>
                        </tr>
                    </thead>
                    <!-- Table body -->
                    <tbody class="text-sm divide-y divide-gray-100">
                        @forelse($activities as $activity)
                        <tr class="hover:bg-gray-50">
                            <td class="p-2 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $activity->created_at->format('M j, Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $activity->created_at->format('g:i A') }}</div>
                            </td>
                            <td class="p-2 whitespace-nowrap">
                                @if($activity->actor_email)
                                    <div class="text-sm font-medium text-gray-900">{{ $activity->actor_email }}</div>
                                    <div class="text-xs text-gray-500">{{ class_basename($activity->actor_type) }}</div>
                                @else
                                    <span class="text-sm text-gray-500">System</span>
                                @endif
                            </td>
                            <td class="p-2 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                                    {{ ucwords(str_replace('_', ' ', $activity->event_type)) }}
                                </span>
                            </td>
                            <td class="p-2 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">
                                    {{ ucwords(str_replace('_', ' ', $activity->activity_category)) }}
                                </span>
                            </td>
                            <td class="p-2">
                                <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $activity->activity_description }}">
                                    {{ Str::limit($activity->activity_description, 50) }}
                                </div>
                            </td>
                            <td class="p-2 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $activity->ip_address ?: 'N/A' }}</div>
                            </td>
                            <td class="p-2 whitespace-nowrap">
                                <div class="text-center">
                                    @php
                                        $severityClasses = [
                                            'low' => 'bg-green-100 text-green-800',
                                            'medium' => 'bg-yellow-100 text-yellow-800',
                                            'high' => 'bg-red-100 text-red-800',
                                            'critical' => 'bg-purple-100 text-purple-800'
                                        ];
                                        $severityClass = $severityClasses[$activity->severity] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $severityClass }}">
                                        {{ ucfirst($activity->severity) }}
                                    </span>
                                </div>
                            </td>
                            <td class="p-2 whitespace-nowrap">
                                <div class="text-center">
                                    @php
                                        $statusClasses = [
                                            'success' => 'bg-green-100 text-green-800',
                                            'failed' => 'bg-red-100 text-red-800',
                                            'error' => 'bg-red-100 text-red-800'
                                        ];
                                        $statusClass = $statusClasses[$activity->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                        {{ ucfirst($activity->status) }}
                                    </span>
                                </div>
                            </td>
                            <td class="p-2 whitespace-nowrap">
                                <div class="text-center">
                                    <a href="{{ route('admin.audit-trail.show', $activity->id) }}" 
                                       class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-xs font-medium rounded-lg shadow-md hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                                        <i class="fas fa-eye text-xs mr-1.5"></i>
                                        View
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr class="hover:bg-gray-50">
                            <td class="p-2" colspan="9">
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-clipboard-list text-3xl mb-3 text-gray-300"></i>
                                    <p class="text-lg font-medium">No activity logs found</p>
                                    <p class="text-sm">Try adjusting your filters to see more results.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($activities->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $activities->withQueryString()->links() }}
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-refresh page every 5 minutes for real-time monitoring
    setTimeout(function() {
        location.reload();
    }, 300000); // 5 minutes
});
</script>
@endpush