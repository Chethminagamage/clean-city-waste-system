@extends('layouts.collector')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 transition-colors duration-300">
    <!-- Header Section -->
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 dark:from-orange-600 dark:to-orange-700 text-white shadow-xl transition-colors duration-300">
        <div class="container mx-auto px-4 sm:px-6 py-6 sm:py-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('collector.dashboard') }}" 
                       class="w-10 h-10 bg-white/20 dark:bg-white/30 rounded-full flex items-center justify-center hover:bg-white/30 dark:hover:bg-white/40 transition-colors duration-200">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="text-xl sm:text-3xl font-bold">My Profile</h1>
                        <p class="text-orange-100 dark:text-orange-200 text-sm sm:text-base transition-colors duration-300">Manage your collector account settings</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2 bg-white/20 dark:bg-white/30 px-3 py-2 rounded-full transition-colors duration-300">
                    <i class="fas fa-user-check text-orange-200 dark:text-orange-300"></i>
                    <span class="text-sm font-medium">Collector Account</span>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 sm:px-6 py-6">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/30 border-l-4 border-green-500 dark:border-green-400 p-4 rounded-lg transition-colors duration-300">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-500 dark:text-green-400 mr-3"></i>
                    <p class="text-green-700 dark:text-green-300 font-medium transition-colors duration-300">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 dark:border-red-400 p-4 rounded-lg transition-colors duration-300">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-triangle text-red-500 dark:text-red-400 mr-3"></i>
                    <p class="text-red-700 dark:text-red-300 font-medium transition-colors duration-300">Please correct the following errors:</p>
                </div>
                <ul class="list-disc list-inside text-red-600 dark:text-red-400 space-y-1 transition-colors duration-300">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors duration-300">
                    <div class="bg-gradient-to-r from-orange-100 to-orange-200 dark:from-orange-900/50 dark:to-orange-800/50 px-6 py-6 text-center transition-colors duration-300">
                        <div class="relative w-20 h-20 rounded-full overflow-hidden mx-auto mb-4 border-4 border-white/30 dark:border-white/40">
                            @if(Auth::user()->profile_image)
                                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" 
                                     alt="Profile Picture" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-white/20 dark:bg-white/30 flex items-center justify-center">
                                    <i class="fas fa-user text-2xl text-white"></i>
                                </div>
                            @endif
                        </div>
                        <h3 class="text-xl font-bold text-orange-800 dark:text-orange-200 transition-colors duration-300">{{ Auth::user()->name ?? 'Collector' }}</h3>
                        <p class="text-orange-700 dark:text-orange-300 text-sm mt-1 transition-colors duration-300">Waste Collector</p>
                        
                        <!-- Profile Picture Upload/Remove Buttons -->
                        <div class="flex gap-2 mt-4 justify-center">
                            <form method="POST" action="{{ route('collector.profile.picture') }}" enctype="multipart/form-data" id="profilePictureForm" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="file" name="profile_image" accept="image/*" id="profileImageInput" class="hidden">
                                <button type="button" onclick="document.getElementById('profileImageInput').click()" 
                                        class="bg-orange-500 hover:bg-orange-600 dark:bg-orange-600 dark:hover:bg-orange-700 text-white px-3 py-1 rounded-lg text-xs font-medium transition-colors duration-200">
                                    <i class="fas fa-upload mr-1"></i>Update Picture
                                </button>
                            </form>
                            @if(Auth::user()->profile_image)
                                <button type="button" onclick="removeProfilePicture()" 
                                        class="bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700 text-white px-3 py-1 rounded-lg text-xs font-medium transition-colors duration-200">
                                    <i class="fas fa-trash mr-1"></i>Remove
                                </button>
                            @endif
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700 transition-colors duration-300">
                            <span class="text-gray-600 dark:text-gray-400 transition-colors duration-300">Email:</span>
                            <span class="font-medium text-sm text-gray-800 dark:text-gray-200 transition-colors duration-300">{{ Auth::user()->email ?? 'Not set' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700 transition-colors duration-300">
                            <span class="text-gray-600 dark:text-gray-400 transition-colors duration-300">Phone:</span>
                            <span class="font-medium text-sm text-gray-800 dark:text-gray-200 transition-colors duration-300">{{ Auth::user()->contact ?? 'Not set' }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700 transition-colors duration-300">
                            <span class="text-gray-600 dark:text-gray-400 transition-colors duration-300">Employee ID:</span>
                            <span class="font-medium text-sm text-gray-800 dark:text-gray-200 transition-colors duration-300">#{{ Auth::user()->id }}</span>
                        </div>
                        <div class="flex items-center justify-between py-3">
                            <span class="text-gray-600 dark:text-gray-400 transition-colors duration-300">Status:</span>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 transition-colors duration-300">
                                Active
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden mt-6 transition-colors duration-300">
                    <div class="bg-gradient-to-r from-orange-100 to-orange-200 dark:from-orange-900/50 dark:to-orange-800/50 px-6 py-4 transition-colors duration-300">
                        <h3 class="text-lg font-bold text-orange-800 dark:text-orange-200 transition-colors duration-300">Quick Stats</h3>
                    </div>
                    
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-tasks text-orange-500 dark:text-orange-400 mr-3"></i>
                                <span class="text-gray-600 dark:text-gray-400 transition-colors duration-300">Total Reports</span>
                            </div>
                            <span class="font-bold text-gray-800 dark:text-gray-200 transition-colors duration-300">{{ $totalReports ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-500 dark:text-green-400 mr-3"></i>
                                <span class="text-gray-600 dark:text-gray-400 transition-colors duration-300">Completed</span>
                            </div>
                            <span class="font-bold text-gray-800 dark:text-gray-200 transition-colors duration-300">{{ $completedReports ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-clock text-blue-500 dark:text-blue-400 mr-3"></i>
                                <span class="text-gray-600 dark:text-gray-400 transition-colors duration-300">Active</span>
                            </div>
                            <span class="font-bold text-gray-800 dark:text-gray-200 transition-colors duration-300">{{ $activeReports ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Forms -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors duration-300">
                    <div class="bg-gradient-to-r from-orange-100 to-orange-200 dark:from-orange-900/50 dark:to-orange-800/50 px-6 py-4 transition-colors duration-300">
                        <h3 class="text-xl font-bold text-orange-800 dark:text-orange-200 transition-colors duration-300">Personal Information</h3>
                    </div>
                    
                    <div class="p-6">
                        <form method="POST" action="{{ route('collector.profile.update') }}" class="space-y-6">
                            @csrf
                            @method('PUT')
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">Full Name *</label>
                                    <input type="text" name="name" value="{{ Auth::user()->name ?? '' }}" required
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 dark:focus:ring-orange-400 focus:border-orange-500 dark:focus:border-orange-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-colors duration-300">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">Email Address *</label>
                                    <input type="email" name="email" value="{{ Auth::user()->email ?? '' }}" required
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 dark:focus:ring-orange-400 focus:border-orange-500 dark:focus:border-orange-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-colors duration-300">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">Phone Number</label>
                                    <input type="tel" name="phone" value="{{ Auth::user()->contact ?? '' }}" placeholder="+1 (555) 123-4567"
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-orange-500 dark:focus:ring-orange-400 focus:border-orange-500 dark:focus:border-orange-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 transition-colors duration-300">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-3 pt-4">
                                <button type="submit" class="flex-1 bg-orange-500 hover:bg-orange-600 dark:bg-orange-600 dark:hover:bg-orange-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                                    <i class="fas fa-save mr-2"></i>Save Changes
                                </button>
                                <button type="reset" class="flex-1 bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
                                    <i class="fas fa-undo mr-2"></i>Reset
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Password Change -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden transition-colors duration-300">
                    <div class="bg-gradient-to-r from-blue-100 to-blue-200 dark:from-blue-900/50 dark:to-blue-800/50 px-6 py-4 transition-colors duration-300">
                        <h3 class="text-xl font-bold text-blue-800 dark:text-blue-200 transition-colors duration-300">Change Password</h3>
                    </div>
                    
                    <div class="p-6">
                        <form method="POST" action="{{ route('collector.password.update') }}" class="space-y-6">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">Current Password *</label>
                                <input type="password" name="current_password" required
                                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-colors duration-300">
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">New Password *</label>
                                    <input type="password" name="password" required
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-colors duration-300">
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 transition-colors duration-300">Confirm New Password *</label>
                                    <input type="password" name="password_confirmation" required
                                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-blue-500 dark:focus:border-blue-400 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 transition-colors duration-300">
                                </div>
                            </div>

                            <div class="bg-blue-50 dark:bg-blue-900/30 p-4 rounded-lg border border-blue-200 dark:border-blue-800 transition-colors duration-300">
                                <h4 class="font-medium text-blue-800 dark:text-blue-200 mb-2 transition-colors duration-300">Password Requirements:</h4>
                                <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1 transition-colors duration-300">
                                    <li>• At least 8 characters long</li>
                                    <li>• Include uppercase and lowercase letters</li>
                                    <li>• Include at least one number</li>
                                    <li>• Include at least one special character</li>
                                </ul>
                            </div>

                            <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200">
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
