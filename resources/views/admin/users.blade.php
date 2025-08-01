@extends('admin.layout.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">User Management</h1>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<form method="GET" action="{{ route('admin.users') }}" class="flex flex-wrap gap-3 mb-6">
    <input type="text" name="search" placeholder="Search by name/email"
           value="{{ request('search') }}"
           class="px-3 py-2 border rounded w-60">

    <select name="status" class="px-3 py-2 border rounded">
        <option value="">All Statuses</option>
        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
        <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
    </select>

    <select name="two_factor" class="px-3 py-2 border rounded">
        <option value="">All 2FA</option>
        <option value="enabled" {{ request('two_factor') == 'enabled' ? 'selected' : '' }}>Enabled</option>
        <option value="disabled" {{ request('two_factor') == 'disabled' ? 'selected' : '' }}>Disabled</option>
    </select>

    <button type="submit" class="px-4 py-2 bg-green-700 text-white rounded">Filter</button>
    <a href="{{ route('admin.users') }}" class="px-4 py-2 bg-gray-300 rounded">Reset</a>
</form>

<table class="w-full table-auto text-sm text-left bg-white shadow-md rounded mt-4">
    <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
        <tr>
            <th class="px-6 py-3">Name</th>
            <th>Email</th>
            <th>Status</th>
            <th>2FA</th>
            <th>Registered</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($users as $user)
        <tr class="border-b hover:bg-gray-50">
            <td class="px-6 py-4 font-medium text-gray-900">{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
                <span class="px-2 py-1 text-xs rounded {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                    {{ ucfirst($user->status) }}
                </span>
            </td>
            <td>
                {{ $user->two_factor_enabled ? 'Enabled' : 'Disabled' }}
            </td>
            <td>{{ $user->created_at->format('Y-m-d') }}</td>
            <td class="flex gap-2 py-3">
                {{-- Block/Unblock --}}
                <form method="POST" action="{{ route('admin.users.toggle', $user->id) }}">
                    @csrf
                    <button class="text-blue-600 hover:underline text-xs" type="submit">
                        {{ $user->status === 'active' ? 'Block' : 'Unblock' }}
                    </button>
                </form>

                {{-- Delete --}}
                <form method="POST" action="{{ route('admin.users.delete', $user->id) }}">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-600 hover:underline text-xs" onclick="return confirm('Delete this user?')">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="text-center py-4 text-gray-500">No resident users found.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-6">
    {{ $users->links() }} {{-- Laravel pagination --}}
</div>
@endsection