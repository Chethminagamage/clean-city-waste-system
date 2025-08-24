@extends('admin.layout.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Collection Schedule Details</h1>
    
    <div class="mb-4">
        <a href="{{ route('admin.dashboard') }}" class="text-blue-500 hover:underline">Dashboard</a> / 
        <a href="{{ route('admin.schedules.index') }}" class="text-blue-500 hover:underline">Collection Schedules</a> / 
        <span class="text-gray-700">Schedule Details</span>
    </div>
    
    <div class="bg-white rounded shadow p-4 mb-6">
        <div class="mb-4 pb-2 border-b flex items-center">
            <i class="fas fa-calendar-alt mr-2"></i>
            <h2 class="text-lg font-semibold">Collection Schedule Information</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase">Area</h3>
                <p class="mt-1">{{ $schedule->area->name }} ({{ $schedule->area->district }})</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase">Waste Type</h3>
                <p class="mt-1">
                    <span class="px-2 py-1 rounded text-xs text-white {{ 
                        $schedule->waste_type == 'organic' ? 'bg-green-500' : 
                        ($schedule->waste_type == 'recyclable' ? 'bg-blue-500' : 
                        ($schedule->waste_type == 'hazardous' ? 'bg-red-500' : 
                        ($schedule->waste_type == 'electronics' ? 'bg-yellow-500' : 
                        ($schedule->waste_type == 'construction' ? 'bg-gray-500' : 'bg-indigo-500')))) 
                    }}">
                        {{ ucfirst($schedule->waste_type) }}
                    </span>
                </p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase">Collection Date</h3>
                <p class="mt-1">{{ \Carbon\Carbon::parse($schedule->collection_date)->format('F d, Y') }}</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase">Frequency</h3>
                <p class="mt-1">{{ ucfirst($schedule->frequency) }}</p>
            </div>
        </div>
        
        <div class="mb-4">
            <h3 class="text-sm font-semibold text-gray-500 uppercase">Notes</h3>
            <p class="mt-1">{{ $schedule->notes ?? 'No notes provided.' }}</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4 pt-4 border-t">
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase">Created At</h3>
                <p class="mt-1">{{ $schedule->created_at->format('F d, Y h:i A') }}</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-gray-500 uppercase">Updated At</h3>
                <p class="mt-1">{{ $schedule->updated_at->format('F d, Y h:i A') }}</p>
            </div>
        </div>
        
        <div class="flex justify-end space-x-2 mt-6">
            <a href="{{ route('admin.schedules.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded text-sm">Back to List</a>
            <a href="{{ route('admin.schedules.edit', $schedule) }}" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded text-sm">Edit Schedule</a>
            <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded text-sm">Delete Schedule</button>
            </form>
        </div>
    </div>
@endsection
