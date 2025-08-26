@extends('admin.layout.app')

@section('content')
<div class="mb-4">
    <h1 class="text-3xl font-bold text-gray-800">User Management</h1>
</div>


<form method="GET" action="{{ route('admin.users') }}" class="flex flex-wrap gap-3 mb-6 items-center">
    <input type="text" name="search" placeholder="Search by name/email"
           value="{{ request('search') }}"
           class="px-4 py-2 border rounded-lg w-64 focus:outline-none focus:ring">

    <select name="status" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring">
        <option value="">All Statuses</option>
        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
        <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
    </select>

    <select name="two_factor" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring">
        <option value="">All 2FA</option>
        <option value="enabled" {{ request('two_factor') == 'enabled' ? 'selected' : '' }}>Enabled</option>
        <option value="disabled" {{ request('two_factor') == 'disabled' ? 'selected' : '' }}>Disabled</option>
    </select>

    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">Filter</button>
    <a href="{{ route('admin.users') }}" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">Reset</a>
</form>

<div class="overflow-x-auto bg-white shadow-md rounded">
    <table class="min-w-full text-sm text-left">
        <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
            <tr>
                <th class="px-4 py-3">Name</th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3">2FA</th>
                <th class="px-4 py-3">Registered</th>
                <th class="px-4 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
            <tr class="border-b hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-900">{{ $user->name }}</td>
                <td class="px-4 py-3">{{ $user->email }}</td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 text-xs rounded 
                        {{ $user->status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $user->status ? 'Active' : 'Blocked' }}
                    </span>
                </td>
                <td class="px-4 py-3">
                    {{ $user->two_factor_enabled ? 'Enabled' : 'Disabled' }}
                </td>
                <td class="px-4 py-3 text-gray-600">{{ $user->created_at->format('Y-m-d') }}</td>
                <td class="px-4 py-3 flex gap-2">
                    {{-- Block/Unblock --}}
                    <form method="POST" action="{{ route('admin.users.toggle', $user->id) }}">
                        @csrf
                        <button class="px-3 py-1 text-white rounded text-xs transition {{ $user->status ? 'bg-red-500 hover:bg-red-600' : 'bg-emerald-500 hover:bg-emerald-600' }}" type="submit">
                            {{ $user->status ? 'Block' : 'Unblock' }}
                        </button>
                    </form>

                    {{-- Delete --}}
                    <form method="POST" action="{{ route('admin.users.delete', $user->id) }}">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Delete this user?')"
                                class="px-3 py-1 bg-red-500 text-white rounded text-xs hover:bg-red-600 transition">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center px-4 py-4 text-gray-500">No resident users found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $users->links() }}
</div>
@endsection