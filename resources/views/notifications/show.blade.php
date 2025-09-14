@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-10">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
        <h1 class="text-2xl font-bold mb-4">Notification</h1>
        <div class="text-gray-700 dark:text-gray-200">
            <strong>Message:</strong>
            <div class="mt-2 mb-4">{{ $notification->data['message'] ?? 'No message.' }}</div>
            <strong>Type:</strong> {{ $notification->data['type'] ?? 'N/A' }}<br>
            <strong>Date:</strong> {{ $notification->created_at->toDayDateTimeString() }}
        </div>
    </div>
</div>
@endsection
