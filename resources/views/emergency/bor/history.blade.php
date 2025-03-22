@extends('layout')

@section('title', 'Emergency Room BOR History - NSBM')
@section('header', 'Emergency Room BOR History')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="mb-6">
        <a href="{{ route('emergency.bor.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Back to Today's BOR
        </a>
    </div>

    <div class="bg-gray-50 rounded-lg p-4 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Select Date to View</h2>

        <form action="{{ route('emergency.bor.history') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-4">
            <div class="w-full sm:w-auto">
                <label for="date" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-calendar-alt text-gray-400"></i>
                    </div>
                    <input type="date" name="date" id="date" value="{{ $selectedDate }}"
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
                </div>
            </div>
            <div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md w-full sm:w-auto">
                    <i class="fas fa-search mr-2"></i>View Records
                </button>
            </div>
        </form>

        <!-- Quick Date Navigation -->
        <div class="flex flex-wrap gap-2 mt-4">
            <a href="{{ route('emergency.bor.history', ['date' => now()->format('Y-m-d')]) }}"
                class="px-3 py-1 text-sm {{ Carbon\Carbon::parse($selectedDate)->isToday() ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} rounded-md transition">
                Today
            </a>
            <a href="{{ route('emergency.bor.history', ['date' => now()->subDay()->format('Y-m-d')]) }}"
                class="px-3 py-1 text-sm {{ Carbon\Carbon::parse($selectedDate)->isYesterday() ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} rounded-md transition">
                Yesterday
            </a>
            <a href="{{ route('emergency.bor.history', ['date' => Carbon\Carbon::parse($selectedDate)->subDay()->format('Y-m-d')]) }}"
                class="px-3 py-1 text-sm bg-gray-200 text-gray-700 hover:bg-gray-300 rounded-md transition">
                <i class="fas fa-chevron-left"></i> Previous Day
            </a>
            <a href="{{ route('emergency.bor.history', ['date' => Carbon\Carbon::parse($selectedDate)->addDay()->format('Y-m-d')]) }}"
                class="px-3 py-1 text-sm bg-gray-200 text-gray-700 hover:bg-gray-300 rounded-md transition">
                Next Day <i class="fas fa-chevron-right"></i>
            </a>
        </div>
    </div>

    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        Emergency Room BOR for {{ \Carbon\Carbon::parse($selectedDate)->format('d M Y') }}
        @if(\Carbon\Carbon::parse($selectedDate)->isToday())
            <span class="text-sm bg-green-100 text-green-800 px-2 py-1 rounded-full ml-2">Today</span>
        @endif
    </h2>

    @if($borEntries->isEmpty())
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        No Emergency Room BOR entries recorded for this date.
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
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold text-gray-700">Recorded By</th>
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
                            <td class="py-3 px-4 border-b text-center text-sm">{{ $entry->user->username }}</td>
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

        <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-800 mb-3">Additional Remarks</h3>
            @php $hasRemarks = false; @endphp

            @foreach($borEntries as $entry)
                @if(!empty($entry->remarks))
                    @php $hasRemarks = true; @endphp
                    <div class="bg-gray-50 p-4 mb-2 rounded-md">
                        <p class="text-sm text-gray-700 mb-1"><span class="font-medium">{{ $entry->shift }}</span> Shift:</p>
                        <p class="text-gray-700">{{ $entry->remarks }}</p>
                    </div>
                @endif
            @endforeach

            @if(!$hasRemarks)
                <p class="text-gray-500 italic">No remarks recorded for this date.</p>
            @endif
        </div>
    @endif

    <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('emergency.bor.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white text-center py-3 px-4 rounded-md transition duration-300">
            <i class="fas fa-arrow-left mr-2"></i> Back to Today's Data
        </a>
        <a href="{{ route('emergency.bor.report') }}" class="bg-purple-500 hover:bg-purple-600 text-white text-center py-3 px-4 rounded-md transition duration-300">
            <i class="fas fa-chart-bar mr-2"></i> Monthly Reports
        </a>
        <a href="{{ route('emergency.dashboard') }}" class="bg-blue-500 hover:bg-blue-600 text-white text-center py-3 px-4 rounded-md transition duration-300">
            <i class="fas fa-hospital mr-2"></i> ER Dashboard
        </a>
    </div>
</div>
@endsection
