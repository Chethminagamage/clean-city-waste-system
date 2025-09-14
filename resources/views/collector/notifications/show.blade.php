@extends('collector.layouts.app')

@section('content')
<div class="container mx-auto py-8">
    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-4">Notification Details</h2>
        <div class="mb-4">
            <strong>Type:</strong> {{ $notification->data['type'] ?? 'N/A' }}<br>
            <strong>Message:</strong> {{ $notification->data['message'] ?? 'N/A' }}<br>
            @if(isset($notification->data['report_id']))
                <strong>Report ID:</strong> {{ $notification->data['report_id'] }}<br>
            @endif
            <strong>Created At:</strong> {{ $notification->created_at->format('Y-m-d H:i') }}<br>
            <strong>Status:</strong> {{ $notification->read_at ? 'Read' : 'Unread' }}
        </div>
        <a href="{{ url()->previous() }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Back</a>
    </div>
</div>
@endsection
