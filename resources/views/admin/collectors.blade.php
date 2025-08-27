@extends('admin.layout.app')

@section('content')
<div class="mb-4">
    <h1 class="text-3xl font-bold text-gray-800">Collectors Management</h1>
</div>

{{-- Search & Add --}}
<div class="flex justify-between items-center mb-4">
    <form method="GET" action="{{ route('admin.collectors') }}" class="flex">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search collectors..."
               class="px-4 py-2 border rounded-l-lg focus:outline-none focus:ring w-64">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r-lg">Search</button>
    </form>

    <button onclick="document.getElementById('addModal').classList.remove('hidden')"
            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">+ Add Collector</button>
</div>

{{-- Collectors Table --}}
<div class="overflow-x-auto bg-white shadow-md rounded">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3">Contact</th>
                <th class="px-4 py-3">Location</th>
                <th class="px-4 py-3">Date Registered</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($collectors as $collector)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-3">{{ $collector->name }}</td>
                <td class="px-4 py-3">{{ $collector->email }}</td>
                <td class="px-4 py-3">{{ $collector->contact ?? '-' }}</td>
                <td class="px-4 py-3">{{ $collector->location ?? '-' }}</td>
                <td class="px-4 py-3 text-sm text-gray-600">{{ $collector->created_at->format('d M Y') }}</td>
                <td class="px-4 py-3">
                    <span class="{{ $collector->status === 'active' ? 'text-green-600' : 'text-red-600' }} font-semibold">
                        {{ ucfirst($collector->status) }}
                    </span>
                </td>
                <td class="px-4 py-3 flex gap-2">
                    {{-- Toggle --}}
                    <form action="{{ route('admin.collectors.toggle', $collector->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="{{ $collector->status === 'active' ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }} px-3 py-1 text-white rounded text-xs transition">
                            {{ $collector->status === 'active' ? 'Block' : 'Unblock' }}
                        </button>
                    </form>

                    {{-- Edit --}}
                    <button
                        class="edit-btn px-3 py-1 bg-teal-600 text-white rounded text-xs hover:bg-teal-700 transition"
                        data-collector='@json($collector)'>
                        Edit
                    </button>

                    {{-- Delete --}}
                    <form action="{{ route('admin.collectors.delete', $collector->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure to delete this collector?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button class="px-3 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600 transition">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @empty
                <tr><td colspan="7" class="px-4 py-4 text-center text-gray-500">No collectors found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $collectors->links() }}
</div>

<!-- Add Collector Modal -->
<div id="addModal" class="fixed inset-0 bg-black bg-opacity-40 z-50 flex items-center justify-center hidden">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-2xl p-8 relative transition-transform transform scale-100">
        
        <!-- Close button -->
        <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')" 
                class="absolute top-4 right-4 text-gray-500 hover:text-red-600 text-2xl font-bold leading-none focus:outline-none">
            &times;
        </button>

        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Add Collector</h2>

        <form method="POST" action="{{ route('admin.collectors.store') }}" class="space-y-5">
            @csrf

            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <input type="text" name="name" placeholder="Collector's full name"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" placeholder="example@domain.com"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>

            <!-- Contact -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
                <input type="text" name="contact" placeholder="Mobile number"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>

            <!-- Location -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Assigned Location</label>
                <input type="text" name="location" placeholder="Area assigned"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>

            <!-- Actions -->
            <div class="flex justify-end pt-4 gap-3">
                <button type="button" onclick="document.getElementById('addModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                    Create Collector
                </button>
            </div>
        </form>
    </div>
</div>

{{-- EDIT COLLECTOR MODAL --}}
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-40 z-50 hidden flex items-center justify-center">
    <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-6 relative">
        <button onclick="document.getElementById('editModal').classList.add('hidden')"
                class="absolute top-3 right-3 text-gray-500 hover:text-red-500 text-xl font-bold">
            &times;
        </button>

        <h2 class="text-xl font-semibold mb-4">Edit Collector</h2>
        <form method="POST" id="editForm" class="space-y-4" action="">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            @method('POST')
            
            <div>
                <label class="block font-medium">Name</label>
                <input type="text" id="editName" name="name" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-medium">Email</label>
                <input type="email" id="editEmail" name="email" class="w-full border rounded px-3 py-2" required>
            </div>
            <div>
                <label class="block font-medium">Contact</label>
                <input type="text" id="editContact" name="contact" class="w-full border rounded px-3 py-2">
            </div>
            <div>
                <label class="block font-medium">Location</label>
                <input type="text" id="editLocation" name="location" class="w-full border rounded px-3 py-2">
            </div>
            <div class="text-right">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Update Collector
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Attach listeners to buttons after DOM loads
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', () => {
                const collector = JSON.parse(button.getAttribute('data-collector'));
                fillEditForm(collector);
            });
        });
    });

    function fillEditForm(collector) {
    document.getElementById('editForm').action = `/admin/collectors/update/${collector.id}`;
    document.getElementById('editName').value = collector.name;
    document.getElementById('editEmail').value = collector.email;
    document.getElementById('editContact').value = collector.contact ?? '';
    document.getElementById('editLocation').value = collector.location ?? '';
    document.getElementById('editModal').classList.remove('hidden');
}
</script>
@endpush