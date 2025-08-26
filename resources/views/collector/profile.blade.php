@extends('layouts.collector')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white shadow-xl">
        <div class="container mx-auto px-4 sm:px-6 py-6 sm:py-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('collector.dashboard') }}" 
                       class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center hover:bg-white/30 transition-colors duration-200">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-xl sm:text-3xl font-bold">My Profile</h1>
                        <p class="text-orange-100 text-sm sm:text-base">Manage your collector account settings</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 bg-white/20 px-3 py-2 rounded-full">
                    <i class="fas fa-user-check text-orange-200"></i>
                    <span class="text-sm font-medium">Collector Account</span>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 py-6">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                    <p class="text-red-700 font-medium">Please correct the following errors:</p>
                </div>
                <ul class="list-disc list-inside text-red-600 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-100 to-orange-200 px-6 py-6 text-center">
                        <div class="relative w-20 h-20 rounded-full overflow-hidden mx-auto mb-4 border-4 border-white/30">
                            @if(Auth::user()->profile_image)
                                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" 
                                     alt="Profile Picture" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-white/20 flex items-center justify-center">
                                    <i class="fas fa-user text-2xl text-white"></i>
                                </div>
                            @endif
                        </div>
                        <h3 class="text-xl font-bold text-orange-800">{{ Auth::user()->name ?? 'Collector' }}</h3>
                        <p class="text-orange-700 text-sm mt-1">Waste Collector</p>
                        
                        <!-- Profile Picture Upload/Remove Buttons -->
                        <div class="flex gap-2 mt-4 justify-center">
                            <form method="POST" action="{{ route('collector.profile.picture') }}" enctype="multipart/form-data" id="profilePictureForm" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="file" name="profile_image" accept="image/*" id="profileImageInput" class="hidden">
                                <button type="button" onclick="document.getElementById('profileImageInput').click()" 
                                        class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded-lg text-xs font-medium transition-colors duration-200">
                                    <i class="fas fa-upload mr-1"></i>Update Picture
                                </button>
                            </form>
                            @if(Auth::user()->profile_image)
                                <button type="button" onclick="removeProfilePicture()" 
                                        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-lg text-xs font-medium transition-colors duration-200">
                                    <i class="fas fa-trash mr-1"></i>Remove
                                </button>
                            @endif
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-gray-600">Email:</span>
                            <span class="font-medium text-sm">{{ Auth::user()->email ?? 'Not set' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-gray-600">Phone:</span>
                            <span class="font-medium text-sm">{{ Auth::user()->contact ?? 'Not set' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-gray-600">Employee ID:</span>
                            <span class="font-medium text-sm">#{{ Auth::user()->id }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <span class="text-gray-600">Status:</span>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                Active
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mt-6">
                    <div class="bg-gradient-to-r from-orange-100 to-orange-200 px-6 py-4">
                        <h3 class="text-lg font-bold text-orange-800">Quick Stats</h3>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-tasks text-orange-500 mr-3"></i>
                                <span class="text-gray-600">Total Reports</span>
                            </div>
                            <span class="font-bold text-gray-800">{{ $totalReports ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                <span class="text-gray-600">Completed</span>
                            </div>
                            <span class="font-bold text-gray-800">{{ $completedReports ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-clock text-blue-500 mr-3"></i>
                                <span class="text-gray-600">Active</span>
                            </div>
                            <span class="font-bold text-gray-800">{{ $activeReports ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Forms -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-100 to-orange-200 px-6 py-4">
                        <h3 class="text-xl font-bold text-orange-800">Personal Information</h3>
                    </div>
                    
                    <div class="p-6">
                        <form method="POST" action="{{ route('collector.profile.update') }}" class="space-y-6">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                    <input type="text" name="name" value="{{ Auth::user()->name ?? '' }}" required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                    <input type="email" name="email" value="{{ Auth::user()->email ?? '' }}" required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                    <input type="tel" name="phone" value="{{ Auth::user()->contact ?? '' }}" placeholder="+1 (555) 123-4567"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-3 pt-4">
                                <button type="submit" class="flex-1 bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                                    <i class="fas fa-save mr-2"></i>Save Changes
                                </button>
                                <button type="reset" class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                                    <i class="fas fa-undo mr-2"></i>Reset
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Password Change -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-100 to-blue-200 px-6 py-4">
                        <h3 class="text-xl font-bold text-blue-800">Change Password</h3>
                    </div>
                    
                    <div class="p-6">
                        <form method="POST" action="{{ route('collector.password.update') }}" class="space-y-6">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Current Password *</label>
                                <input type="password" name="current_password" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">New Password *</label>
                                    <input type="password" name="password" required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password *</label>
                                    <input type="password" name="password_confirmation" required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <div class="bg-blue-50 p-4 rounded-lg">
                                <h4 class="font-medium text-blue-800 mb-2">Password Requirements:</h4>
                                <ul class="text-sm text-blue-700 space-y-1">
                                    <li>• At least 8 characters long</li>
                                    <li>• Include uppercase and lowercase letters</li>
                                    <li>• Include at least one number</li>
                                    <li>• Include at least one special character</li>
                                </ul>
                            </div>

                            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                                <i class="fas fa-key mr-2"></i>Update Password
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Profile image auto-submit functionality
    document.getElementById('profileImageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Check file size (2MB limit)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                this.value = '';
                return;
            }

            // Check file type
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file');
                this.value = '';
                return;
            }

            // Auto-submit the form
            document.getElementById('profilePictureForm').submit();
        }
    });

    // Remove profile picture
    function removeProfilePicture() {
        if (confirm('Are you sure you want to remove your profile picture?')) {
            fetch('{{ route("collector.profile.picture.remove") }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert('Error removing profile picture. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error removing profile picture. Please try again.');
            });
        }
    }
</script>
@endsection
