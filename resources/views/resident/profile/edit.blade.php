@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-4">
    <h2 class="text-2xl font-bold mb-6">Edit Profile</h2>

    {{-- ✅ Success Alert --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- ✅ Error Alert --}}
    @if($errors->any())
        <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ✅ Profile Image --}}
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-1">Profile Image</label>

        @if ($user->profile_image)
            <img src="{{ $user->profile_image_url }}" class="w-24 h-24 rounded-full object-cover mb-3" id="imagePreview">
            <form action="{{ route('resident.profile.image.remove') }}" method="POST">
                @csrf
                <button type="submit" class="text-red-600 text-sm underline hover:text-red-800">Remove Image</button>
            </form>
        @else
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}" class="w-24 h-24 rounded-full object-cover mb-3" id="imagePreview">
        @endif

        {{-- ✅ Image input goes outside of image section but inside the form --}}
    </div>

    {{-- ✅ Main Form --}}
    <form action="{{ route('resident.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        {{-- ✅ File input --}}
        <div>
            <input type="file" name="profile_image" id="profile_image" accept="image/*" class="block">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-3 border border-gray-300 rounded">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-3 border border-gray-300 rounded">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
            <input type="text" name="contact" value="{{ old('contact', $user->contact) }}" class="w-full px-4 py-3 border border-gray-300 rounded">
        </div>

        <hr class="my-6">

        <h3 class="text-lg font-semibold">Change Password</h3>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
            <input type="password" name="current_password" class="w-full px-4 py-3 border border-gray-300 rounded">
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <input type="password" name="new_password" class="w-full px-4 py-3 border border-gray-300 rounded">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input type="password" name="new_password_confirmation" class="w-full px-4 py-3 border border-gray-300 rounded">
            </div>
        </div>

        <div class="pt-6">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded shadow">Update Profile</button>
        </div>
    </form>
</div>
@endsection

{{-- ✅ Live Image Preview --}}
@push('scripts')
<script>
document.getElementById('profile_image').addEventListener('change', function(e) {
    const [file] = e.target.files;
    if (file) {
        document.getElementById('imagePreview').src = URL.createObjectURL(file);
    }
});
</script>
@endpush