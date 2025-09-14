@extends('admin.layout.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Edit Collection Schedule</h1>
    
    <div class="mb-4">
        <a href="{{ route('admin.dashboard.main') }}" class="text-blue-500 hover:underline">Dashboard</a> / 
        <a href="{{ route('admin.schedules.index') }}" class="text-blue-500 hover:underline">Collection Schedules</a> / 
        <span class="text-gray-700">Edit Schedule</span>
    </div>
    
    <div class="bg-white rounded shadow p-4 mb-6">
        <div class="mb-4 pb-2 border-b flex items-center">
            <i class="fas fa-edit mr-2"></i>
            <h2 class="text-lg font-semibold">Edit Collection Schedule</h2>
        </div>
        
        @if ($errors->any())
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.schedules.update', $schedule) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="area_id" class="block text-sm font-medium text-gray-700 mb-1">Area</label>
                    <select name="area_id" id="area_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        <option value="">-- Select Area --</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ (old('area_id', $schedule->area_id) == $area->id) ? 'selected' : '' }}>
                                {{ $area->name }} ({{ $area->district }})
                            </option>
                        @endforeach
                    </select>
                    @error('area_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="waste_type" class="block text-sm font-medium text-gray-700 mb-1">Waste Type</label>
                    <select name="waste_type" id="waste_type" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        <option value="">-- Select Waste Type --</option>
                        @foreach($wasteTypes as $value => $label)
                            <option value="{{ $value }}" {{ (old('waste_type', $schedule->waste_type) == $value) ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('waste_type')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Collection Date</label>
                    <input type="date" name="date" id="date" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('date', \Carbon\Carbon::parse($schedule->date)->format('Y-m-d')) }}" required>
                    @error('date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="frequency" class="block text-sm font-medium text-gray-700 mb-1">Frequency</label>
                    <select name="frequency" id="frequency" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        <option value="">-- Select Frequency --</option>
                        <option value="weekly" {{ (old('frequency', $schedule->frequency) == 'weekly') ? 'selected' : '' }}>Weekly</option>
                        <option value="bi-weekly" {{ (old('frequency', $schedule->frequency) == 'bi-weekly') ? 'selected' : '' }}>Bi-weekly</option>
                        <option value="monthly" {{ (old('frequency', $schedule->frequency) == 'monthly') ? 'selected' : '' }}>Monthly</option>
                    </select>
                    @error('frequency')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="mb-4">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                <textarea name="notes" id="notes" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" rows="3">{{ old('notes', $schedule->notes) }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="flex justify-end space-x-2 mt-6">
                <a href="{{ route('admin.schedules.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded text-sm">Cancel</a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded text-sm">Update Schedule</button>
            </div>
        </form>
    </div>
@endsection
