<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Collector Panel</title>
    <link rel="stylesheet" href="{{ asset('css/collector.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dark-mode.css') }}">
</head>
<body class="bg-gray-100 dark:bg-gray-900">
    @include('collector.partials.navbar')
    <main class="min-h-screen">
        @yield('content')
    </main>
</body>
</html>
