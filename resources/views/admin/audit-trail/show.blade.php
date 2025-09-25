@extends('admin.layout.app')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    
    <!-- Page header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <!-- Left: Title -->
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-gray-800 font-bold">Activity Log Details</h1>
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.audit-trail.index') }}" 
                           class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                            Audit Trail
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 text-xs mx-2"></i>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">Activity #{{ $activity->id }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Right: Actions -->
        <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-3">
            <a href="{{ route('admin.audit-trail.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                <i class="fas fa-arrow-left text-sm mr-2"></i>
                <span>Back to List</span>
            </a>
            <button onclick="window.print()" 
                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-medium rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                <i class="fas fa-print text-sm mr-2"></i>
                <span>Print</span>
            </button>
        </div>
    </div>

    <!-- Activity Overview -->
    <div class="col-span-full xl:col-span-8 bg-white shadow-lg rounded-xl border border-gray-200 mb-8 overflow-hidden">
        <header class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100 flex justify-between items-center">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-info-circle text-blue-600"></i>
                </div>
                <div>
                    <h2 class="font-semibold text-gray-800 text-lg">Activity Overview</h2>
                    <p class="text-sm text-gray-600">Detailed activity information</p>
                </div>
            </div>
            @php
                $severityClasses = [
                    'low' => 'bg-green-100 text-green-800',
                    'medium' => 'bg-yellow-100 text-yellow-800',
                    'high' => 'bg-red-100 text-red-800',
                    'critical' => 'bg-purple-100 text-purple-800'
                ];
                $severityClass = $severityClasses[$activity->severity] ?? 'bg-gray-100 text-gray-800';
            @endphp
            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $severityClass }}">
                {{ ucfirst($activity->severity) }} Severity
            </span>
        </header>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h3>
                    
                    <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100">
                        <div class="font-medium text-gray-700">Activity ID:</div>
                        <div class="col-span-2 text-gray-900">{{ $activity->id }}</div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100">
                        <div class="font-medium text-gray-700">Date/Time:</div>
                        <div class="col-span-2 text-gray-900">{{ $activity->created_at->format('F j, Y g:i:s A') }}</div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100">
                        <div class="font-medium text-gray-700">Event Type:</div>
                        <div class="col-span-2">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold bg-blue-100 text-blue-800 rounded-full">
                                {{ ucwords(str_replace('_', ' ', $activity->event_type)) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100">
                        <div class="font-medium text-gray-700">Category:</div>
                        <div class="col-span-2">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded-full">
                                {{ ucwords(str_replace('_', ' ', $activity->activity_category)) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100">
                        <div class="font-medium text-gray-700">Status:</div>
                        <div class="col-span-2">
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
                    </div>
                </div>

                <!-- Actor & Context Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Actor & Context</h3>
                    
                    <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100">
                        <div class="font-medium text-gray-700">Actor:</div>
                        <div class="col-span-2 text-gray-900">
                            @if($activity->actor_email)
                                <div class="font-medium">{{ $activity->actor_email }}</div>
                                <div class="text-sm text-gray-500">{{ class_basename($activity->actor_type) }}</div>
                            @else
                                <span class="text-gray-500">System</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100">
                        <div class="font-medium text-gray-700">IP Address:</div>
                        <div class="col-span-2 text-gray-900 font-mono text-sm">{{ $activity->ip_address ?: 'N/A' }}</div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100">
                        <div class="font-medium text-gray-700">User Agent:</div>
                        <div class="col-span-2 text-gray-900 text-sm break-all">{{ $activity->user_agent ?: 'N/A' }}</div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100">
                        <div class="font-medium text-gray-700">Session ID:</div>
                        <div class="col-span-2 text-gray-900 font-mono text-sm">{{ $activity->session_id ?: 'N/A' }}</div>
                    </div>
                    
                    @if($activity->subject_type && $activity->subject_id)
                    <div class="grid grid-cols-3 gap-4 py-3 border-b border-gray-100">
                        <div class="font-medium text-gray-700">Subject:</div>
                        <div class="col-span-2 text-gray-900">
                            <div class="font-medium">{{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}</div>
                        </div>
                    </div>
                    @endif
                </div>
                
            </div>
        </div>
    </div>

    <!-- Description -->
    <div class="col-span-full xl:col-span-8 bg-white shadow-lg rounded-xl border border-gray-200 mb-8 overflow-hidden">
        <header class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-file-text text-purple-600"></i>
                </div>
                <h2 class="font-semibold text-gray-800 text-lg">Description</h2>
            </div>
        </header>
        <div class="p-6">
            <p class="text-gray-700 leading-relaxed">{{ $activity->activity_description }}</p>
        </div>
    </div>

    @if($activity->request_url)
    <!-- Request Information -->
    <div class="col-span-full xl:col-span-8 bg-white shadow-lg rounded-xl border border-gray-200 mb-8 overflow-hidden">
        <header class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-globe text-green-600"></i>
                </div>
                <h2 class="font-semibold text-gray-800 text-lg">Request Information</h2>
            </div>
        </header>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 py-3 border-b border-gray-100">
                <div class="font-medium text-gray-700">URL:</div>
                <div class="lg:col-span-2 text-gray-900 font-mono text-sm break-all">{{ $activity->request_url }}</div>
            </div>
            
            @if($activity->request_method)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 py-3 border-b border-gray-100">
                <div class="font-medium text-gray-700">Method:</div>
                <div class="lg:col-span-2">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-800 rounded">
                        {{ strtoupper($activity->request_method) }}
                    </span>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    @if($activity->old_values || $activity->new_values)
    <!-- Data Changes -->
    <div class="col-span-full xl:col-span-8 bg-white shadow-lg rounded-xl border border-gray-200 mb-8 overflow-hidden">
        <header class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-exchange-alt text-orange-600"></i>
                </div>
                <h2 class="font-semibold text-gray-800 text-lg">Data Changes</h2>
            </div>
        </header>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @if($activity->old_values)
                <div>
                    <h3 class="text-lg font-semibold text-red-600 mb-4">Previous Values</h3>
                    <div class="bg-red-50 rounded-lg p-4 font-mono text-sm overflow-x-auto">
                        <pre class="whitespace-pre-wrap">{{ json_encode($activity->old_values, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
                @endif
                
                @if($activity->new_values)
                <div>
                    <h3 class="text-lg font-semibold text-green-600 mb-4">New Values</h3>
                    <div class="bg-green-50 rounded-lg p-4 font-mono text-sm overflow-x-auto">
                        <pre class="whitespace-pre-wrap">{{ json_encode($activity->new_values, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    @if($activity->additional_data && count($activity->additional_data) > 0)
    <!-- Additional Data -->
    <div class="col-span-full xl:col-span-8 bg-white shadow-lg rounded-xl border border-gray-200 mb-8 overflow-hidden">
        <header class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-gray-100">
            <div class="flex items-center">
                <div class="flex-shrink-0 w-8 h-8 bg-teal-100 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-database text-teal-600"></i>
                </div>
                <h2 class="font-semibold text-gray-800 text-lg">Additional Data</h2>
            </div>
        </header>
        <div class="p-6">
            <div class="bg-gray-50 rounded-lg p-4 font-mono text-sm overflow-x-auto">
                <pre class="whitespace-pre-wrap">{{ json_encode($activity->additional_data, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
    </div>
    @endif

    @if($activity->isSuspicious())
    <!-- Security Alert -->
    <div class="col-span-full xl:col-span-8 bg-gradient-to-r from-red-50 to-red-100 border border-red-200 rounded-xl mb-8 shadow-lg">
        <div class="p-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-400 text-xl"></i>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Security Alert</h3>
                    <p class="mt-2 text-sm text-red-700">
                        This activity has been flagged as suspicious. Please review the details carefully and take appropriate action if necessary.
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection