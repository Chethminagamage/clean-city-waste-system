@extends('layouts.app')

@section('title', 'Server Error')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="mx-auto h-12 w-12 text-red-600">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Server Error
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                {{ $message ?? 'Something went wrong on our end.' }}
            </p>
            @if(isset($error) && $error)
                <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-md">
                    <p class="text-sm text-red-600 font-mono">{{ $error }}</p>
                </div>
            @endif
        </div>
        
        <div class="space-y-4">
            <a href="{{ url()->previous() }}" 
               class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                Go Back
            </a>
            
            @auth('web')
                <a href="{{ route('dashboard.main') }}" 
                   class="group relative w-full flex justify-center py-2 px-4 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    Go to Dashboard
                </a>
            @endauth
            @auth('collector')
                <a href="{{ route('collector.dashboard') }}" 
                   class="group relative w-full flex justify-center py-2 px-4 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    Go to Dashboard
                </a>
            @endauth
            @auth('admin')
                <a href="{{ route('admin.dashboard.main') }}" 
                   class="group relative w-full flex justify-center py-2 px-4 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    Go to Dashboard
                </a>
            @endauth
            @guest
                <a href="{{ route('landing.home') }}" 
                   class="group relative w-full flex justify-center py-2 px-4 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500">
                    Go to Home
                </a>
            @endguest
        </div>
    </div>
</div>
@endsection