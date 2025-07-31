@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-cover bg-center bg-no-repeat relative" style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('path/to/your/background-image.jpg');">
    <div class="min-h-screen flex items-center justify-center px-4 py-8">
        <div class="flex max-w-6xl w-full bg-white/10 backdrop-blur-sm rounded-2xl shadow-2xl overflow-hidden">
            
            <!-- Left Side - Illustration Panel -->
            <div class="hidden lg:flex lg:w-1/2 bg-white/20 backdrop-blur-md items-center justify-center p-12">
                <div class="text-center">
                    <div class="mb-8">
                        <svg class="w-64 h-48 mx-auto" viewBox="0 0 400 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <!-- Email envelope illustration -->
                            <rect x="50" y="100" width="300" height="200" rx="20" fill="#4ADE80" opacity="0.8"/>
                            <path d="M50 120 L200 180 L350 120" stroke="white" stroke-width="4" stroke-linecap="round"/>
                            <circle cx="200" cy="50" r="30" fill="#34D399"/>
                            <path d="M185 50 L195 60 L215 40" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            
                            <!-- Decorative elements -->
                            <circle cx="100" cy="60" r="8" fill="#86EFAC" opacity="0.6"/>
                            <circle cx="300" cy="70" r="6" fill="#86EFAC" opacity="0.4"/>
                            <circle cx="320" cy="50" r="4" fill="#86EFAC" opacity="0.8"/>
                        </svg>
                    </div>
                    
                    <div class="text-white">
                        <div class="flex items-center justify-center mb-4">
                            <div class="w-6 h-6 bg-green-400 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-lg">Secure email verification</span>
                        </div>
                        
                        <div class="flex items-center justify-center mb-4">
                            <div class="w-6 h-6 bg-green-400 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-lg">Account protection</span>
                        </div>
                        
                        <div class="flex items-center justify-center">
                            <div class="w-6 h-6 bg-green-400 rounded-full flex items-center justify-center mr-3">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-lg">Quick activation process</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Verification Form -->
            <div class="w-full lg:w-1/2 bg-white rounded-r-2xl p-8 lg:p-12">
                <div class="max-w-md mx-auto">
                    <!-- Header -->
                    <div class="text-center mb-8">
                        <div class="mb-6">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <h1 class="text-3xl font-bold text-gray-800 mb-2">Check Your Email</h1>
                        <p class="text-gray-600">We've sent a verification link to activate your Clean City account</p>
                    </div>

                    <!-- Status Card -->
                    <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-green-500 mt-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-semibold text-green-800 mb-1">Verification Email Sent!</h3>
                                <p class="text-sm text-green-700">Please check your inbox and click the verification link to complete your registration.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="space-y-4 mb-8">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-1">
                                <span class="text-sm font-semibold text-blue-600">1</span>
                            </div>
                            <p class="text-gray-700">Check your email inbox (and spam folder)</p>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-1">
                                <span class="text-sm font-semibold text-blue-600">2</span>
                            </div>
                            <p class="text-gray-700">Click the verification link in the email</p>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-1">
                                <span class="text-sm font-semibold text-blue-600">3</span>
                            </div>
                            <p class="text-gray-700">Start managing waste smartly in your community</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-4">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-6 rounded-xl transition duration-200 transform hover:scale-[1.02] focus:outline-none focus:ring-4 focus:ring-green-200">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Resend Verification Email
                            </button>
                        </form>

                        <div class="text-center">
                            <a href="{{ route('login') }}" class="text-green-600 hover:text-green-800 font-medium transition duration-200">
                                ← Back to Login
                            </a>
                        </div>
                    </div>

                    <!-- Help Section -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-2">Need help?</p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center text-sm">
                                <a href="mailto:contact@cleancity.com" class="text-green-600 hover:text-green-800 font-medium transition duration-200">
                                    📧 contact@cleancity.com
                                </a>
                                <a href="tel:+81456789" class="text-green-600 hover:text-green-800 font-medium transition duration-200">
                                    📞 +94 81 456 7890
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if (session('resent'))
    <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50" id="success-alert">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            A fresh verification link has been sent to your email address.
        </div>
    </div>
    
    <script>
        setTimeout(function() {
            document.getElementById('success-alert').style.display = 'none';
        }, 5000);
    </script>
@endif
@endsection