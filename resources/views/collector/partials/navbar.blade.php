<!-- resources/views/collector/partials/navbar.blade.php -->
<nav class="bg-white shadow p-4 flex justify-between items-center">
    <div class="text-xl font-bold text-green-700">
        Collector Panel
    </div>
    <div>
        <form method="POST" action="{{ route('collector.logout') }}">
            @csrf
            <button type="submit" class="text-red-600 hover:underline">Logout</button>
        </form>
    </div>
</nav>