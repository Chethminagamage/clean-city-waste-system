@extends('admin.layout.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">My Profile</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.profile.update') }}" class="bg-white p-6 rounded shadow space-y-4 max-w-xl">
        @csrf

        <div>
            <label class="block text-sm font-medium">Name</label>
            <input type="text" name="name" value="{{ old('name', $admin->name) }}" class="w-full mt-1 p-2 border rounded">
            @error('name') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium">Email</label>
            <input type="email" name="email" value="{{ old('email', $admin->email) }}" class="w-full mt-1 p-2 border rounded">
            @error('email') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium">New Password <span class="text-xs text-gray-400">(leave blank to keep current)</span></label>
            <input type="password" name="password" class="w-full mt-1 p-2 border rounded">
            @error('password') <p class="text-red-500 text-sm">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium">Confirm Password</label>
            <input type="password" name="password_confirmation" class="w-full mt-1 p-2 border rounded">
        </div>

        <div>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                Save Changes
            </button>
        </div>
    </form>
@endsection