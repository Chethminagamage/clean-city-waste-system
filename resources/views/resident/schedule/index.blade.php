{{-- resources/views/resident/schedule/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6 space-y-6">
  <h1 class="text-xl font-semibold">Collection Schedule</h1>

  {{-- Filters --}}
  <form method="GET" action="{{ route('resident.schedule.index') }}"
        id="scheduleFilters"
        class="bg-white p-4 rounded shadow flex flex-wrap gap-3 items-end">

    <div>
      <label class="block text-sm text-gray-600">From</label>
      <input type="date"
             name="from"
             value="{{ optional($range[0] ?? null)->toDateString() }}"
             class="border rounded px-2 py-1">
    </div>

    <div>
      <label class="block text-sm text-gray-600">To</label>
      <input type="date"
             name="to"
             value="{{ optional($range[1] ?? null)->toDateString() }}"
             class="border rounded px-2 py-1">
    </div>

    <div>
      <label class="block text-sm text-gray-600">District</label>
      <select name="area_id"
              class="border rounded px-2 py-1"
              onchange="document.getElementById('scheduleFilters').submit()">
        <option value="">Select district…</option>
        @foreach($areas as $a)
          <option value="{{ $a->id }}" @selected($selectedAreaId == $a->id)>{{ $a->name }}</option>
        @endforeach
      </select>
    </div>

    <button class="px-3 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
      Apply
    </button>

    <div class="ml-auto text-sm text-gray-500">
      Area: <span class="font-medium">{{ $area?->name ?? '—' }}</span>
    </div>
  </form>

  {{-- Messages / results --}}
  @if(!$selectedAreaId)
    <div class="mt-1 text-gray-500">Select your district to view the schedule.</div>
  @elseif(!empty($message))
    <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded">
      {{ $message }}
    </div>
  @else
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="text-left px-4 py-2">Date</th>
            <th class="text-left px-4 py-2">Day</th>
            <th class="text-left px-4 py-2">Time</th>
            <th class="text-left px-4 py-2">Waste Type</th>
            <th class="text-left px-4 py-2">Notes</th>
          </tr>
        </thead>
        <tbody class="divide-y">
          @foreach($schedules as $row)
            @php
              $d = $row['date'] instanceof \Carbon\Carbon
                    ? $row['date']
                    : \Carbon\Carbon::parse($row['date']);
              $from = \Illuminate\Support\Str::substr($row['start_time'] ?? '', 0, 5);
              $to   = \Illuminate\Support\Str::substr($row['end_time']   ?? '', 0, 5);
            @endphp
            <tr>
              <td class="px-4 py-2">{{ $d->format('Y-m-d') }}</td>
              <td class="px-4 py-2">{{ $d->format('D') }}</td>
              <td class="px-4 py-2">
                {{ $from }}@if($to) – {{ $to }}@endif
              </td>
              <td class="px-4 py-2">{{ $row['waste_type'] ?? '—' }}</td>
              <td class="px-4 py-2 text-gray-600">{{ $row['notes'] ?? '' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>
@endsection