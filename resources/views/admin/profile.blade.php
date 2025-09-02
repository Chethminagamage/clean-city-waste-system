@extends('admin.layout.app')

@section('title', 'Admin Profile - Clean City')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Profile Header -->
    <div class="mb-6">
        <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-xl p-6 text-white">
            <div class="flex items-center gap-6">
                <div class="relative">
                    @if($admin->profile_photo)
                        <img src="{{ Storage::url($admin->profile_photo) }}" 
                             alt="Profile Photo" 
                             class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-lg">
                    @else
                        <div class="w-20 h-20 rounded-full bg-white bg-opacity-20 flex items-center justify-center border-4 border-white shadow-lg">
                            <i class="fas fa-user text-2xl text-white"></i>
                        </div>
                    @endif
                    <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-400 rounded-full border-2 border-white"></div>
                </div>
                
                <div class="flex-1">
                    <h1 class="text-3xl font-bold">{{ $admin->name }}</h1>
                    <p class="text-green-100 text-lg">{{ $admin->position ?? 'System Administrator' }}</p>
                    <p class="text-green-200">{{ $admin->email }}</p>
                    @if($admin->bio)
                        <p class="text-green-100 text-sm mt-2 max-w-md">{{ Str::limit($admin->bio, 100) }}</p>
                    @endif
                </div>
                
                <div class="text-right">
                    <div class="text-green-100 text-sm">Member Since</div>
                    <div class="text-white font-semibold">{{ $admin->created_at->format('M Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Profile Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-user-edit text-green-600 mr-2"></i>
                        Edit Profile Information
                    </h2>
                </div>
                
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-6" id="main-form">
                    @csrf
                    
                    <div class="space-y-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $admin->name) }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $admin->email) }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone', $admin->phone) }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="+94 XX XXX XXXX">
                                @error('phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Position -->
                            <div>
                                <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Position/Title</label>
                                <select name="position" id="position" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                    <option value="">Select Position</option>
                                    <option value="System Administrator" {{ old('position', $admin->position) == 'System Administrator' ? 'selected' : '' }}>System Administrator</option>
                                    <option value="Operations Manager" {{ old('position', $admin->position) == 'Operations Manager' ? 'selected' : '' }}>Operations Manager</option>
                                    <option value="Waste Management Supervisor" {{ old('position', $admin->position) == 'Waste Management Supervisor' ? 'selected' : '' }}>Waste Management Supervisor</option>
                                    <option value="Customer Service Manager" {{ old('position', $admin->position) == 'Customer Service Manager' ? 'selected' : '' }}>Customer Service Manager</option>
                                    <option value="Environmental Officer" {{ old('position', $admin->position) == 'Environmental Officer' ? 'selected' : '' }}>Environmental Officer</option>
                                </select>
                                @error('position') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <!-- Bio -->
                        <div>
                            <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">Bio/Description</label>
                            <textarea name="bio" id="bio" rows="4" 
                                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                      placeholder="Tell us about yourself and your role...">{{ old('bio', $admin->bio) }}</textarea>
                            <div class="flex justify-between mt-1">
                                <p class="text-xs text-gray-500">Maximum 500 characters</p>
                                <span id="bio-counter" class="text-xs text-gray-400"></span>
                            </div>
                            @error('bio') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Password Section -->
                        <div class="pt-4 border-t border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Change Password</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                                    <input type="password" name="password" id="password"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                           placeholder="Leave blank to keep current password">
                                    @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4 border-t border-gray-200">
                            <button type="submit" 
                                    class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors">
                                <i class="fas fa-save mr-2"></i>
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Photo Management Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-camera text-green-600 mr-2"></i>
                        Profile Photo
                    </h3>
                </div>
                
                <div class="p-6">
                    <div class="text-center">
                        @if($admin->profile_photo)
                            <img src="{{ Storage::url($admin->profile_photo) }}" 
                                 alt="Current Photo" id="photo-preview"
                                 class="w-32 h-32 rounded-full object-cover border-4 border-gray-200 mx-auto mb-4 shadow-lg">
                        @else
                            <div class="w-32 h-32 rounded-full bg-gray-100 flex items-center justify-center border-4 border-gray-200 mx-auto mb-4 shadow-lg" id="photo-placeholder">
                                <i class="fas fa-user text-gray-400 text-3xl"></i>
                            </div>
                        @endif
                        
                        <div class="space-y-3">
                            <input type="file" name="profile_photo" id="profile_photo" 
                                   accept="image/*" class="hidden" onchange="previewImage(this)" form="main-form">
                            <label for="profile_photo" 
                                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 cursor-pointer transition-colors w-full justify-center">
                                <i class="fas fa-upload mr-2"></i>
                                {{ $admin->profile_photo ? 'Change Photo' : 'Upload Photo' }}
                            </label>
                            
                            @if($admin->profile_photo)
                                <form action="{{ route('admin.profile.remove-photo') }}" method="POST" class="w-full">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-flex items-center px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition-colors w-full justify-center"
                                            onclick="return confirm('Are you sure you want to remove your profile photo?')">
                                        <i class="fas fa-trash mr-2"></i>
                                        Remove Photo
                                    </button>
                                </form>
                            @endif
                        </div>
                        
                        <p class="text-xs text-gray-500 mt-3">JPEG, PNG, JPG up to 2MB</p>
                        @error('profile_photo') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            
            <!-- Quick Info Card -->
            <div class="bg-white rounded-lg shadow-sm mt-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-info-circle text-green-600 mr-2"></i>
                        Account Info
                    </h3>
                </div>
                
                <div class="p-6 space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Account Type:</span>
                        <span class="font-medium text-green-600">Administrator</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Member Since:</span>
                        <span class="font-medium">{{ $admin->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Last Updated:</span>
                        <span class="font-medium">{{ $admin->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Profile photo preview
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Update the preview image in sidebar
            const sidebarPreview = document.getElementById('photo-preview');
            const placeholder = document.getElementById('photo-placeholder');
            
            if (sidebarPreview) {
                sidebarPreview.src = e.target.result;
            } else if (placeholder) {
                // Replace placeholder with image
                const img = document.createElement('img');
                img.src = e.target.result;
                img.alt = 'Current Photo';
                img.id = 'photo-preview';
                img.className = 'w-32 h-32 rounded-full object-cover border-4 border-gray-200 mx-auto mb-4 shadow-lg';
                placeholder.replaceWith(img);
            }
            
            // Also update header image
            const headerImg = document.querySelector('.bg-gradient-to-r img');
            if (headerImg) {
                headerImg.src = e.target.result;
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Character counter for bio
document.addEventListener('DOMContentLoaded', function() {
    const bioTextarea = document.getElementById('bio');
    const counter = document.getElementById('bio-counter');
    
    if (bioTextarea && counter) {
        function updateCounter() {
            const length = bioTextarea.value.length;
            counter.textContent = `${length}/500 characters`;
            if (length > 500) {
                counter.className = 'text-xs text-red-500';
            } else {
                counter.className = 'text-xs text-gray-400';
            }
        }
        
        bioTextarea.addEventListener('input', updateCounter);
        updateCounter(); // Initial count
    }
});
</script>
@endsection