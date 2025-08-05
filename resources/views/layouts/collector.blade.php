<!-- resources/views/layouts/collector.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Collector Dashboard</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="min-h-screen">
        @include('collector.partials.navbar') <!-- optional -->
        <main class="p-6">
            @yield('content')
        </main>
    </div>
</body>
</html>