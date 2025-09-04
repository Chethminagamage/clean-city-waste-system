@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-emerald-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-8 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center space-x-3 mb-2">
                <div class="p-2 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg transition-colors duration-300">
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Profile Settings</h1>
                    <p class="text-gray-600 dark:text-gray-400">Manage your account information and preferences</p>
                </div>
            </div>
        </div>

        <!-- Success/Error Alerts -->
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-700 text-emerald-800 dark:text-emerald-200 px-4 py-3 rounded-lg flex items-center transition-colors duration-300">
                <svg class="w-5 h-5 text-emerald-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-200 px-4 py-3 rounded-lg transition-colors duration-300">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Profile Cards Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Profile Photo Card -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-colors duration-300">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 transition-colors duration-300">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Profile Photo</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Update your profile picture</p>
                    </div>
                    <div class="p-6">
                        <div class="text-center">
                            <div class="relative inline-block">
                                <img src="{{ $user->profile_image_url }}" 
                                     class="w-32 h-32 rounded-full object-cover border-4 border-emerald-100 dark:border-emerald-800 shadow-lg" 
                                     id="imagePreview">
                                <div class="absolute bottom-0 right-0 bg-emerald-500 dark:bg-emerald-600 rounded-full p-2 border-4 border-white dark:border-gray-800 shadow-lg">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <!-- Upload Form -->
                            <form action="{{ route('resident.profile.update') }}" method="POST" enctype="multipart/form-data" class="mt-4">
                                @csrf
                                <input type="hidden" name="action" value="photo">
                                
                                <div class="space-y-3">
                                    <label for="profile_image" class="flex flex-col items-center justify-center w-full h-20 border-2 border-emerald-300 dark:border-emerald-700 border-dashed rounded-lg cursor-pointer bg-emerald-50 dark:bg-emerald-900/20 hover:bg-emerald-100 dark:hover:bg-emerald-900/30 transition-colors duration-200">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-5 h-5 mb-1 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                            </svg>
                                            <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">Choose Photo</p>
                                        </div>
                                        <input id="profile_image" name="profile_image" type="file" class="hidden" accept="image/*">
                                    </label>
                                    
                                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-700 dark:hover:bg-emerald-800 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200">
                                        Upload Photo
                                    </button>
                                </div>
                            </form>
                            
                            <!-- Remove Form (separate form to avoid nesting) -->
                            @if($user->profile_image)
                            <form action="{{ route('resident.profile.image.remove') }}" method="POST" class="mt-3">
                                @csrf
                                <button type="submit" onclick="return confirm('Are you sure you want to remove your profile picture?')" class="w-full px-4 py-2 bg-red-50 dark:bg-red-900/20 hover:bg-red-100 dark:hover:bg-red-900/30 text-red-600 dark:text-red-400 rounded-lg text-sm font-medium transition-colors duration-200">
                                    Remove Photo
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Profile Information -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Personal Information Card -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-colors duration-300">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 transition-colors duration-300">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Personal Information
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Update your personal details and contact information</p>
                    </div>
                    <form action="{{ route('resident.profile.update') }}" method="POST" class="p-6">
                        @csrf
                        <input type="hidden" name="action" value="info">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Full Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 focus:border-emerald-500 dark:focus:border-emerald-400 transition-colors duration-200"
                                    placeholder="Enter your full name" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email Address</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 focus:border-emerald-500 dark:focus:border-emerald-400 transition-colors duration-200"
                                    placeholder="Enter your email address" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Contact Number</label>
                                <input type="text" name="contact" value="{{ old('contact', $user->contact) }}"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 focus:border-emerald-500 dark:focus:border-emerald-400 transition-colors duration-200"
                                    placeholder="Enter your contact number">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Account Status</label>
                                <div class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 flex items-center">
                                    <div class="flex items-center">
                                        <div class="w-2 h-2 bg-emerald-500 dark:bg-emerald-400 rounded-full mr-2"></div>
                                        <span class="text-gray-700 dark:text-gray-300 font-medium">Active Account</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-700 dark:hover:bg-emerald-800 text-white px-6 py-2.5 rounded-lg font-semibold transition-colors duration-200 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Update Information
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Security Settings Card -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-colors duration-300">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 transition-colors duration-300">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Security Settings
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Manage your password and security preferences</p>
                    </div>
                    
                    <!-- Two-Factor Authentication Section -->
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h4 class="text-base font-semibold text-gray-900 dark:text-white">Two-Factor Authentication</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Add an extra layer of security to your account</p>
                            </div>
                            <form action="{{ route('resident.2fa.toggle') }}" method="POST" id="toggleForm">
                                @csrf
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="two_factor_enabled" class="sr-only peer" 
                                           {{ $user->two_factor_enabled ? 'checked' : '' }}
                                           onchange="this.form.submit()">
                                    <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 dark:peer-focus:ring-emerald-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600 dark:peer-checked:bg-emerald-500"></div>
                                </label>
                            </form>
                        </div>
                        <div id="twoFactorStatus" class="flex items-center text-sm {{ $user->two_factor_enabled ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-500 dark:text-gray-400' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $user->two_factor_enabled ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' }}"/>
                            </svg>
                            {{ $user->two_factor_enabled ? 'Two-factor authentication is enabled' : 'Two-factor authentication is disabled' }}
                        </div>
                    </div>

                    <!-- Password Change Section -->
                    <form action="{{ route('resident.profile.update') }}" method="POST" class="p-6">
                        @csrf
                        <input type="hidden" name="action" value="password">
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Current Password</label>
                                <input type="password" name="current_password"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 focus:border-emerald-500 dark:focus:border-emerald-400 transition-colors duration-200"
                                    placeholder="Enter your current password">
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">New Password</label>
                                    <input type="password" name="new_password"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 focus:border-emerald-500 dark:focus:border-emerald-400 transition-colors duration-200"
                                        placeholder="Enter new password">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Confirm Password</label>
                                    <input type="password" name="new_password_confirmation"
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 dark:focus:ring-emerald-400 focus:border-emerald-500 dark:focus:border-emerald-400 transition-colors duration-200"
                                        placeholder="Confirm new password">
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-700 dark:hover:bg-emerald-800 text-white px-6 py-2.5 rounded-lg font-semibold transition-colors duration-200 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Theme Settings Card -->
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-colors duration-300">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 transition-colors duration-300">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            Theme Preferences
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Choose your preferred color theme</p>
                    </div>
                    
                    <div class="p-6">
                        <form action="{{ route('resident.profile.update') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="action" value="theme">
                            
                            <div class="space-y-3">
                                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 block mb-3">Select Theme:</label>
                                
                                <!-- Light Theme Option -->
                                <label class="relative flex items-center p-4 border-2 border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 {{ ($user->theme_preference ?? 'light') === 'light' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : '' }}">
                                    <input type="radio" name="theme_preference" value="light" class="sr-only" 
                                           {{ ($user->theme_preference ?? 'light') === 'light' ? 'checked' : '' }}>
                                    <div class="flex items-center space-x-4 w-full">
                                        <div class="flex-shrink-0">
                                            <div class="w-12 h-12 bg-white border-2 border-gray-300 rounded-lg flex items-center justify-center shadow-sm">
                                                <svg class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-base font-semibold text-gray-900 dark:text-white">Light Mode</h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Clean, bright interface with light backgrounds</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="w-5 h-5 border-2 border-gray-300 rounded-full flex items-center justify-center {{ ($user->theme_preference ?? 'light') === 'light' ? 'border-blue-500 bg-blue-500' : '' }}">
                                                {{ ($user->theme_preference ?? 'light') === 'light' ? '●' : '' }}
                                                <span class="text-white text-xs">{{ ($user->theme_preference ?? 'light') === 'light' ? '●' : '' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </label>

                                <!-- Dark Theme Option -->
                                <label class="relative flex items-center p-4 border-2 border-gray-200 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 {{ ($user->theme_preference ?? 'light') === 'dark' ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20' : '' }}">
                                    <input type="radio" name="theme_preference" value="dark" class="sr-only" 
                                           {{ ($user->theme_preference ?? 'light') === 'dark' ? 'checked' : '' }}>
                                    <div class="flex items-center space-x-4 w-full">
                                        <div class="flex-shrink-0">
                                            <div class="w-12 h-12 bg-gray-800 border-2 border-gray-600 rounded-lg flex items-center justify-center shadow-sm">
                                                <svg class="w-6 h-6 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-base font-semibold text-gray-900 dark:text-white">Dark Mode</h4>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Easy on the eyes with dark backgrounds and light text</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="w-5 h-5 border-2 border-gray-300 rounded-full flex items-center justify-center {{ ($user->theme_preference ?? 'light') === 'dark' ? 'border-blue-500 bg-blue-500' : '' }}">
                                                <span class="text-white text-xs">{{ ($user->theme_preference ?? 'light') === 'dark' ? '●' : '' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-600 text-white px-6 py-2.5 rounded-lg font-semibold transition-colors duration-200 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    Save Theme Preference
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Information Summary -->
        <div class="mt-8 bg-white dark:bg-gray-800 shadow-sm rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden transition-colors duration-300">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50 transition-colors duration-300">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Account Information</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Overview of your account details</p>
            </div>
            <div class="p-6">
                <dl class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Member Since</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->created_at->format('M d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-white">{{ $user->updated_at->format('M d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Account Type</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 dark:bg-emerald-800 text-emerald-800 dark:text-emerald-200">
                                {{ ucfirst($user->role ?? 'Resident') }}
                            </span>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Navigation Actions -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-between">
            <a href="{{ route('resident.dashboard') }}" 
               class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 font-medium transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Dashboard
            </a>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Profile image preview functionality
document.getElementById('profile_image').addEventListener('change', function(e) {
    const [file] = e.target.files;
    if (file) {
        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            this.value = '';
            return;
        }

        // Validate file type
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!validTypes.includes(file.type)) {
            alert('Please upload a valid image file (JPG, JPEG, PNG, or GIF)');
            this.value = '';
            return;
        }

        // Show preview
        document.getElementById('imagePreview').src = URL.createObjectURL(file);
    } else {
        // Reset to default avatar if no file selected
        document.getElementById('imagePreview').src = '{{ $user->profile_image_url }}';
    }
});

// Auto-save indication (Jetstream-like feature)
let forms = document.querySelectorAll('form');
forms.forEach(form => {
    form.addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing...
            `;
            submitBtn.disabled = true;
        }
    });
});
</script>
@endpush