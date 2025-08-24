@extends('admin.layout.app')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Collection Schedules</h1>
    
    <div class="flex justify-between items-center mb-4">
        <div class="flex items-center">
            <i class="fas fa-calendar-alt mr-2"></i>
            <span>Manage Collection Schedules</span>
        </div>
        <div class="space-x-2">
            <a href="{{ route('admin.schedules.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-3 rounded text-sm">
                <i class="fas fa-plus"></i> Create New Schedule
            </a>
            <button type="button" class="bg-green-500 hover:bg-green-700 text-white py-1 px-3 rounded text-sm" onclick="document.getElementById('generateSchedulesModal').classList.remove('hidden')">
                <i class="fas fa-magic"></i> Generate Schedules
            </button>
        </div>
    </div>
    
    <div class="bg-white rounded shadow p-4 mb-6">
            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border rounded shadow">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-2 px-4">ID</th>
                            <th class="py-2 px-4">Area</th>
                            <th class="py-2 px-4">Waste Type</th>
                            <th class="py-2 px-4">Collection Date</th>
                            <th class="py-2 px-4">Frequency</th>
                            <th class="py-2 px-4">Notes</th>
                            <th class="py-2 px-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $schedule)
                            <tr class="border-t">
                                <td class="py-2 px-4">{{ $schedule->id }}</td>
                                <td class="py-2 px-4">{{ $schedule->area->name }}</td>
                                <td class="py-2 px-4">
                                    <span class="px-2 py-1 rounded text-xs text-white {{ 
                                        $schedule->waste_type == 'organic' ? 'bg-green-500' : 
                                        ($schedule->waste_type == 'recyclable' ? 'bg-blue-500' : 
                                        ($schedule->waste_type == 'hazardous' ? 'bg-red-500' : 
                                        ($schedule->waste_type == 'electronics' ? 'bg-yellow-500' : 
                                        ($schedule->waste_type == 'construction' ? 'bg-gray-500' : 'bg-indigo-500')))) 
                                    }}">
                                        {{ ucfirst($schedule->waste_type) }}
                                    </span>
                                </td>
                                <td class="py-2 px-4">{{ \Carbon\Carbon::parse($schedule->collection_date)->format('Y-m-d') }}</td>
                                <td class="py-2 px-4">{{ ucfirst($schedule->frequency) }}</td>
                                <td class="py-2 px-4">{{ $schedule->notes }}</td>
                                <td class="py-2 px-4">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('admin.schedules.edit', $schedule) }}" class="bg-blue-500 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.schedules.destroy', $schedule) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-700 text-white px-2 py-1 rounded text-xs">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="border-t">
                                <td colspan="7" class="py-4 px-4 text-center text-gray-500">No collection schedules found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $schedules->links() }}
            </div>
        </div>
    </div>

<!-- Generate Schedules Modal -->
<div id="generateSchedulesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50" x-show="open">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="flex flex-col">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium">Generate Collection Schedules</h3>
                <button type="button" class="text-gray-400 hover:text-gray-500" onclick="document.getElementById('generateSchedulesModal').classList.add('hidden')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form action="{{ route('admin.schedules.generate') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="area_id" class="block text-sm font-medium text-gray-700 mb-1">Select Area</label>
                    <select name="area_id" id="area_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        <option value="">-- Select Area --</option>
                        @foreach(App\Models\Area::all() as $area)
                            <option value="{{ $area->id }}">{{ $area->name }} ({{ $area->district }})</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-4">
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Area Type</label>
                    <select name="type" id="type" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        <option value="">-- Select Type --</option>
                        <option value="urban">Urban</option>
                        <option value="suburban">Suburban</option>
                        <option value="rural">Rural</option>
                    </select>
                </div>
                
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                This will delete all existing schedules for the selected area and generate new ones.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-end space-x-3 mt-4">
                    <button type="button" class="bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded text-sm" onclick="document.getElementById('generateSchedulesModal').classList.add('hidden')">
                        Close
                    </button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded text-sm">
                        Generate Schedules
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function closeModal() {
        document.getElementById('generateSchedulesModal').classList.add('hidden');
    }
    
    // Close modal when clicking outside of it
    window.onclick = function(event) {
        const modal = document.getElementById('generateSchedulesModal');
        if (event.target == modal) {
            modal.classList.add('hidden');
        }
    }
</script>
@endsection
