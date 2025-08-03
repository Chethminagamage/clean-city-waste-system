@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-10 px-6 bg-white rounded-xl shadow-md">

    <h2 class="text-3xl font-bold text-gray-800 mb-1">Edit Profile</h2>
    <p class="text-gray-500 mb-8">Update your account information and preferences</p>

    {{-- ✅ Success Alert --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- ✅ Error Alert --}}
    @if($errors->any())
        <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded mb-6">
            <ul class="list-disc pl-5 text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ✅ Profile Image --}}
    <div class="mb-8 text-center">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Profile Picture</h3>
        @if ($user->profile_image)
            <img src="{{ $user->profile_image_url }}" class="w-24 h-24 mx-auto rounded-full object-cover border-4 border-green-100 mb-2" id="imagePreview">
            <form action="{{ route('resident.profile.image.remove') }}" method="POST" class="mt-1">
                @csrf
                <button type="submit" class="text-red-600 text-sm hover:underline">Remove Image</button>
            </form>
        @else
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}" class="w-24 h-24 mx-auto rounded-full object-cover border-4 border-green-100 mb-2" id="imagePreview">
        @endif
    </div>

    {{-- ✅ Main Form --}}
    <form action="{{ route('resident.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        {{-- ✅ File input --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Upload New Image</label>
            <label for="profile_image" class="flex flex-col items-center justify-center w-full h-24 border-2 border-green-300 border-dashed rounded-lg cursor-pointer bg-green-50 hover:bg-green-100 transition">
                <div class="flex flex-col items-center justify-center pt-2">
                    <svg class="w-6 h-6 mb-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                    </svg>
                    <p class="text-sm text-gray-600 font-semibold">Click to upload</p>
                    <p class="text-xs text-gray-400">PNG, JPG or JPEG (MAX. 2MB)</p>
                </div>
                <input id="profile_image" name="profile_image" type="file" class="hidden" accept="image/*" />
            </label>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Number</label>
            <input type="text" name="contact" value="{{ old('contact', $user->contact) }}"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
        </div>

        {{-- ✅ 2FA Toggle --}}
        <div class="bg-gray-50 p-4 rounded-lg">
            <label class="flex items-center">
                <input type="checkbox" name="two_factor_enabled"
                    class="w-4 h-4 text-green-600 border-gray-300 rounded"
                    {{ $user->two_factor_enabled ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-700 font-medium">Enable Two-Factor Authentication (2FA)</span>
            </label>
            <p class="ml-6 text-xs text-gray-500">Add an extra layer of security to your account</p>
        </div>

        <hr class="my-10">

        <h3 class="text-lg font-semibold text-green-700">Change Password</h3>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
            <input type="password" name="current_password"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                placeholder="Enter current password">
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                <input type="password" name="new_password"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    placeholder="Enter new password">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                <input type="password" name="new_password_confirmation"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    placeholder="Confirm new password">
            </div>
        </div>

        <div class="pt-6 flex flex-col sm:flex-row gap-4">
            <button type="submit"
                class="w-full sm:w-auto bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold shadow-md transition">
                Update Profile
            </button>
            <a href="{{ route('resident.dashboard') }}"
                class="w-full sm:w-auto bg-gray-200 hover:bg-gray-300 text-gray-700 text-center px-6 py-3 rounded-lg font-semibold transition">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('profile_image').addEventListener('change', function(e) {
    const [file] = e.target.files;
    if (file) {
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            this.value = '';
            return;
        }

        const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
        if (!validTypes.includes(file.type)) {
            alert('Please upload a valid image file (JPG, JPEG, or PNG)');
            this.value = '';
            return;
        }

        document.getElementById('imagePreview').src = URL.createObjectURL(file);
    }
});
</script>
@endpush