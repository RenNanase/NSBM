@extends('layout')

@section('title', 'Emergency Room BOR - NSBM')
@section('header', 'Emergency Room BOR')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h2 class="text-2xl font-semibold text-gray-800">
            Emergency Room BOR (Patient/Day) Data: {{ $selectedDate->format('d M Y') }}
            @if($selectedDate->isToday())
                <span class="text-sm bg-green-100 text-green-800 px-2 py-1 rounded-full ml-2">Today</span>
            @endif
        </h2>

        <div class="flex flex-col sm:flex-row gap-3">
            <form action="{{ route('emergency.bor.index') }}" method="GET" class="flex items-center space-x-2">
                <div class="relative">
                    <input type="date" id="date" name="date" value="{{ $selectedDate->format('Y-m-d') }}"
                        class="pr-10 pl-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <button type="submit" class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-500 hover:text-blue-600">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <a href="{{ route('emergency.bor.create') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md inline-flex items-center">
                <i class="fas fa-plus-circle mr-2"></i>Add New Entry
            </a>
        </div>
    </div>

    <!-- Quick Date Navigation -->
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('emergency.bor.index', ['date' => now()->format('Y-m-d')]) }}"
            class="px-3 py-1 text-sm {{ $selectedDate->isToday() ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-md transition">
            Today
        </a>
        <a href="{{ route('emergency.bor.index', ['date' => now()->subDay()->format('Y-m-d')]) }}"
            class="px-3 py-1 text-sm {{ $selectedDate->isYesterday() ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} rounded-md transition">
            Yesterday
        </a>
        <a href="{{ route('emergency.bor.index', ['date' => $selectedDate->copy()->subDay()->format('Y-m-d')]) }}"
            class="px-3 py-1 text-sm bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-md transition">
            <i class="fas fa-chevron-left"></i> Previous Day
        </a>
        <a href="{{ route('emergency.bor.index', ['date' => $selectedDate->copy()->addDay()->format('Y-m-d')]) }}"
            class="px-3 py-1 text-sm bg-gray-100 text-gray-700 hover:bg-gray-200 rounded-md transition">
            Next Day <i class="fas fa-chevron-right"></i>
        </a>
    </div>

    @if($borEntries->isEmpty())
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        No Emergency Room BOR entries recorded for {{ $selectedDate->format('d M Y') }}.
                        @if($selectedDate->isToday())
                        <a href="{{ route('emergency.bor.create') }}" class="font-medium underline text-yellow-700 hover:text-yellow-600">
                            Add your first entry
                        </a>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-700">Shift</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold text-gray-700">Green</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold text-gray-700">Yellow</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold text-gray-700">Red</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold text-gray-700">Shift Total</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold text-gray-700">Ambulance</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold text-gray-700">Admission</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold text-gray-700">Transfer</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold text-gray-700">Death</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($borEntries as $entry)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 border-b font-medium">{{ $entry->shift }}</td>
                            <td class="py-3 px-4 border-b text-center bg-green-50">{{ $entry->green }}</td>
                            <td class="py-3 px-4 border-b text-center bg-yellow-50">{{ $entry->yellow }}</td>
                            <td class="py-3 px-4 border-b text-center bg-red-50">{{ $entry->red }}</td>
                            <td class="py-3 px-4 border-b text-center font-medium">{{ $entry->grand_total }}</td>
                            <td class="py-3 px-4 border-b text-center">{{ $entry->ambulance_call }}</td>
                            <td class="py-3 px-4 border-b text-center">{{ $entry->admission }}</td>
                            <td class="py-3 px-4 border-b text-center">{{ $entry->transfer }}</td>
                            <td class="py-3 px-4 border-b text-center">{{ $entry->death }}</td>
                            <td class="py-3 px-4 border-b text-center">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('emergency.bor.show', $entry->id) }}" class="text-blue-600 hover:text-blue-900" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('emergency.bor.edit', $entry->id) }}" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('emergency.bor.destroy', $entry->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this entry?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-100 font-medium">
                    <tr>
                        <td class="py-3 px-4 border-b">24 Hours Census</td>
                        <td class="py-3 px-4 border-b text-center bg-green-100">{{ $dailyTotals['green'] }}</td>
                        <td class="py-3 px-4 border-b text-center bg-yellow-100">{{ $dailyTotals['yellow'] }}</td>
                        <td class="py-3 px-4 border-b text-center bg-red-100">{{ $dailyTotals['red'] }}</td>
                        <td class="py-3 px-4 border-b text-center font-bold">{{ $dailyTotals['grand_total'] }}</td>
                        <td class="py-3 px-4 border-b text-center">{{ $dailyTotals['ambulance_call'] }}</td>
                        <td class="py-3 px-4 border-b text-center">{{ $dailyTotals['admission'] }}</td>
                        <td class="py-3 px-4 border-b text-center">{{ $dailyTotals['transfer'] }}</td>
                        <td class="py-3 px-4 border-b text-center">{{ $dailyTotals['death'] }}</td>
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
