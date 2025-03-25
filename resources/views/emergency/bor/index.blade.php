@extends('layout')

@section('title', 'Emergency Room BOR - NSBM')
@section('header', 'Emergency Room BOR')

@section('content')
<div class="dashboard-card p-6 mb-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h2 class="text-2xl font-semibold" style="color: var(--color-text-primary);">
            Emergency Room BOR (Patient/Day) Data: {{ $selectedDate->format('d M Y') }}
            @if($selectedDate->isToday())
                <span class="text-sm px-2 py-1 rounded-full ml-2" style="background-color: var(--color-primary-light); color: var(--color-primary);">Today</span>
            @endif
        </h2>

        <div class="flex flex-col sm:flex-row gap-3">
            <form action="{{ route('emergency.bor.index') }}" method="GET" class="flex items-center space-x-2">
                <div class="relative">
                    <input type="date" id="date" name="date" value="{{ $selectedDate->format('Y-m-d') }}"
                        class="pr-10 pl-4 py-2 border rounded-md focus:outline-none"
                        style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);">
                    <button type="submit" class="absolute inset-y-0 right-0 flex items-center px-2" style="color: var(--color-text-light);">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <a href="{{ route('emergency.bor.create') }}" class="btn btn-primary px-4 py-2 rounded-md inline-flex items-center">
                <i class="fas fa-plus-circle mr-2"></i>Add New Entry
            </a>
        </div>
    </div>

    <!-- Quick Date Navigation -->
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('emergency.bor.index', ['date' => now()->format('Y-m-d')]) }}"
            class="px-3 py-1 text-sm rounded-md transition {{ $selectedDate->isToday() ? 'bg-secondary text-white' : '' }}" style="{{ !$selectedDate->isToday() ? 'background-color: var(--color-bg-alt); color: var(--color-text-primary);' : '' }}">
            Today
        </a>
        <a href="{{ route('emergency.bor.index', ['date' => now()->subDay()->format('Y-m-d')]) }}"
            class="px-3 py-1 text-sm rounded-md transition {{ $selectedDate->isYesterday() ? 'bg-secondary text-white' : '' }}" style="{{ !$selectedDate->isYesterday() ? 'background-color: var(--color-bg-alt); color: var(--color-text-primary);' : '' }}">
            Yesterday
        </a>
        <a href="{{ route('emergency.bor.index', ['date' => $selectedDate->copy()->subDay()->format('Y-m-d')]) }}"
            class="px-3 py-1 text-sm rounded-md transition" style="background-color: var(--color-bg-alt); color: var(--color-text-primary);">
            <i class="fas fa-chevron-left"></i> Previous Day
        </a>
        <a href="{{ route('emergency.bor.index', ['date' => $selectedDate->copy()->addDay()->format('Y-m-d')]) }}"
            class="px-3 py-1 text-sm rounded-md transition" style="background-color: var(--color-bg-alt); color: var(--color-text-primary);">
            Next Day <i class="fas fa-chevron-right"></i>
        </a>
    </div>

    @if($borEntries->isEmpty())
        <div class="p-4 mb-6 rounded-md" style="background-color: rgba(245, 158, 11, 0.2); border-left: 4px solid #f59e0b; color: #b45309;">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm">
                        No Emergency Room BOR entries recorded for {{ $selectedDate->format('d M Y') }}.
                        @if($selectedDate->isToday())
                        <a href="{{ route('emergency.bor.create') }}" class="font-medium underline hover:text-opacity-80">
                            Add your first entry
                        </a>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full border" style="border-color: var(--color-border);">
                <thead>
                    <tr style="background-color: var(--color-secondary-dark); color: var(--color-text-inverse);">
                        <th class="py-3 px-4 border-b text-left text-sm font-semibold">Shift</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold">Green</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold">Yellow</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold">Red</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold">Shift Total</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold">Ambulance</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold">Admission</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold">Transfer</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold">Death</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color: var(--color-border);">
                    @foreach($borEntries as $entry)
                        <tr class="hover:bg-opacity-20 transition duration-150" style="background-color: var(--color-table-stripe); --tw-bg-opacity: 0.5;">
                            <td class="py-3 px-4 border-b font-medium" style="color: var(--color-text-primary);">{{ $entry->shift }}</td>
                            <td class="py-3 px-4 border-b text-center" style="background-color: rgba(16, 185, 129, 0.1); color: var(--color-text-primary);">{{ $entry->green }}</td>
                            <td class="py-3 px-4 border-b text-center" style="background-color: rgba(245, 158, 11, 0.1); color: var(--color-text-primary);">{{ $entry->yellow }}</td>
                            <td class="py-3 px-4 border-b text-center" style="background-color: rgba(239, 68, 68, 0.1); color: var(--color-text-primary);">{{ $entry->red }}</td>
                            <td class="py-3 px-4 border-b text-center font-medium" style="color: var(--color-text-primary);">{{ $entry->grand_total }}</td>
                            <td class="py-3 px-4 border-b text-center" style="color: var(--color-text-primary);">{{ $entry->ambulance_call }}</td>
                            <td class="py-3 px-4 border-b text-center" style="color: var(--color-text-primary);">{{ $entry->admission }}</td>
                            <td class="py-3 px-4 border-b text-center" style="color: var(--color-text-primary);">{{ $entry->transfer }}</td>
                            <td class="py-3 px-4 border-b text-center" style="color: var(--color-text-primary);">{{ $entry->death }}</td>
                            <td class="py-3 px-4 border-b text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('emergency.bor.show', $entry->id) }}" style="color: var(--color-secondary);" class="hover:text-opacity-80" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('emergency.bor.edit', $entry->id) }}" style="color: var(--color-primary);" class="hover:text-opacity-80" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('emergency.bor.destroy', $entry->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this entry?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="color: var(--color-accent);" class="hover:text-opacity-80" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot style="background-color: var(--color-bg-alt); font-medium;" class="border-t" style="border-color: var(--color-border);">
                    <tr>
                        <td class="py-3 px-4 border-b" style="color: var(--color-text-primary);">24 Hours Census</td>
                        <td class="py-3 px-4 border-b text-center" style="background-color: rgba(16, 185, 129, 0.2); color: var(--color-text-primary);">{{ $dailyTotals['green'] }}</td>
                        <td class="py-3 px-4 border-b text-center" style="background-color: rgba(245, 158, 11, 0.2); color: var(--color-text-primary);">{{ $dailyTotals['yellow'] }}</td>
                        <td class="py-3 px-4 border-b text-center" style="background-color: rgba(239, 68, 68, 0.2); color: var(--color-text-primary);">{{ $dailyTotals['red'] }}</td>
                        <td class="py-3 px-4 border-b text-center font-bold" style="color: var(--color-text-primary);">{{ $dailyTotals['grand_total'] }}</td>
                        <td class="py-3 px-4 border-b text-center" style="color: var(--color-text-primary);">{{ $dailyTotals['ambulance_call'] }}</td>
                        <td class="py-3 px-4 border-b text-center" style="color: var(--color-text-primary);">{{ $dailyTotals['admission'] }}</td>
                        <td class="py-3 px-4 border-b text-center" style="color: var(--color-text-primary);">{{ $dailyTotals['transfer'] }}</td>
                        <td class="py-3 px-4 border-b text-center" style="color: var(--color-text-primary);">{{ $dailyTotals['death'] }}</td>
                        <td class="py-3 px-4 border-b"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif

    {{-- <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('emergency.bor.history') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white text-center py-3 px-4 rounded-md transition duration-300">
            <i class="fas fa-history mr-2"></i> View Historical Data
        </a>
        <a href="{{ route('emergency.bor.report') }}" class="bg-purple-500 hover:bg-purple-600 text-white text-center py-3 px-4 rounded-md transition duration-300">
            <i class="fas fa-chart-bar mr-2"></i> Monthly Reports
        </a>
        <a href="{{ route('emergency.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white text-center py-3 px-4 rounded-md transition duration-300">
            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
        </a>
    </div> --}}
</div>
@endsection
