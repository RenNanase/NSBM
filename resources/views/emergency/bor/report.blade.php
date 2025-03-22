@extends('layout')

@section('title', 'Emergency Room BOR Monthly Report - NSBM')
@section('header', 'Emergency Room BOR Monthly Report')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="mb-6">
        <a href="{{ route('emergency.bor.index') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>Back to BOR Dashboard
        </a>
    </div>

    <div class="bg-gray-50 rounded-lg p-4 mb-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Select Month to View</h2>

        <form action="{{ route('emergency.bor.report') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-4">
            <div class="w-full sm:w-auto">
                <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-calendar-alt text-gray-400"></i>
                    </div>
                    <input type="month" name="month" id="month" value="{{ $selectedMonth }}"
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 w-full">
                </div>
            </div>
            <div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md w-full sm:w-auto">
                    <i class="fas fa-search mr-2"></i>View Report
                </button>
            </div>
        </form>

        <!-- Quick Month Navigation -->
        <div class="flex flex-wrap gap-2 mt-4">
            <a href="{{ route('emergency.bor.report', ['month' => now()->format('Y-m')]) }}"
                class="px-3 py-1 text-sm {{ $selectedMonth == now()->format('Y-m') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} rounded-md transition">
                Current Month
            </a>
            <a href="{{ route('emergency.bor.report', ['month' => now()->subMonth()->format('Y-m')]) }}"
                class="px-3 py-1 text-sm {{ $selectedMonth == now()->subMonth()->format('Y-m') ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }} rounded-md transition">
                Previous Month
            </a>
            <a href="{{ route('emergency.bor.report', ['month' => Carbon\Carbon::parse($selectedMonth.'-01')->subMonth()->format('Y-m')]) }}"
                class="px-3 py-1 text-sm bg-gray-200 text-gray-700 hover:bg-gray-300 rounded-md transition">
                <i class="fas fa-chevron-left"></i> Earlier Month
            </a>
            <a href="{{ route('emergency.bor.report', ['month' => Carbon\Carbon::parse($selectedMonth.'-01')->addMonth()->format('Y-m')]) }}"
                class="px-3 py-1 text-sm bg-gray-200 text-gray-700 hover:bg-gray-300 rounded-md transition">
                Later Month <i class="fas fa-chevron-right"></i>
            </a>
        </div>
    </div>

    <h2 class="text-2xl font-semibold text-gray-800 mb-4">
        Monthly Report: {{ $startDate->format('F Y') }}
        @if($startDate->format('Y-m') == now()->format('Y-m'))
            <span class="text-sm bg-green-100 text-green-800 px-2 py-1 rounded-full ml-2">Current Month</span>
        @endif
    </h2>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <p class="text-sm text-green-800 font-medium mb-1">Green (Non-Urgent)</p>
            <p class="text-3xl font-bold text-green-600">{{ $monthlyTotals['green'] }}</p>
        </div>
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <p class="text-sm text-yellow-800 font-medium mb-1">Yellow (Urgent)</p>
            <p class="text-3xl font-bold text-yellow-600">{{ $monthlyTotals['yellow'] }}</p>
        </div>
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <p class="text-sm text-red-800 font-medium mb-1">Red (Emergency)</p>
            <p class="text-3xl font-bold text-red-600">{{ $monthlyTotals['red'] }}</p>
        </div>
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="text-sm text-blue-800 font-medium mb-1">Total Patients</p>
            <p class="text-3xl font-bold text-blue-600">{{ $monthlyTotals['grand_total'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
            <p class="text-sm text-gray-800 font-medium mb-1">Ambulance Calls</p>
            <p class="text-2xl font-bold text-gray-700">{{ $monthlyTotals['ambulance_call'] }}</p>
        </div>
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
            <p class="text-sm text-gray-800 font-medium mb-1">Admissions</p>
            <p class="text-2xl font-bold text-gray-700">{{ $monthlyTotals['admission'] }}</p>
        </div>
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
            <p class="text-sm text-gray-800 font-medium mb-1">Transfers</p>
            <p class="text-2xl font-bold text-gray-700">{{ $monthlyTotals['transfer'] }}</p>
        </div>
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 text-center">
            <p class="text-sm text-gray-800 font-medium mb-1">Deaths</p>
            <p class="text-2xl font-bold text-gray-700">{{ $monthlyTotals['death'] }}</p>
        </div>
    </div>

    @if($entriesByDate->isEmpty())
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        No Emergency Room BOR entries recorded for this month.
                    </p>
                </div>
            </div>
        </div>
    @else
        <h3 class="text-lg font-medium text-gray-800 mb-3">Daily Breakdown</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-700">Date</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold text-gray-700">Green</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold text-gray-700">Yellow</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold text-gray-700">Red</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold text-gray-700">Total</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold text-gray-700">Ambulance</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold text-gray-700">Admission</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold text-gray-700">Transfer</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold text-gray-700">Death</th>
                        <th class="py-3 px-4 border-b text-center text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entriesByDate as $date => $entries)
                        <tr class="hover:bg-gray-50">
                            <td class="py-3 px-4 border-b font-medium">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</td>
                            <td class="py-3 px-4 border-b text-center bg-green-50">{{ $entries->sum('green') }}</td>
                            <td class="py-3 px-4 border-b text-center bg-yellow-50">{{ $entries->sum('yellow') }}</td>
                            <td class="py-3 px-4 border-b text-center bg-red-50">{{ $entries->sum('red') }}</td>
                            <td class="py-3 px-4 border-b text-center font-medium">{{ $entries->sum('grand_total') }}</td>
                            <td class="py-3 px-4 border-b text-center">{{ $entries->sum('ambulance_call') }}</td>
                            <td class="py-3 px-4 border-b text-center">{{ $entries->sum('admission') }}</td>
                            <td class="py-3 px-4 border-b text-center">{{ $entries->sum('transfer') }}</td>
                            <td class="py-3 px-4 border-b text-center">{{ $entries->sum('death') }}</td>
                            <td class="py-3 px-4 border-b text-center">
                                <a href="{{ route('emergency.bor.history', ['date' => $date]) }}" class="text-blue-600 hover:text-blue-900" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-100 font-medium">
                    <tr>
                        <td class="py-3 px-4 border-b">Monthly Total</td>
                        <td class="py-3 px-4 border-b text-center bg-green-100">{{ $monthlyTotals['green'] }}</td>
                        <td class="py-3 px-4 border-b text-center bg-yellow-100">{{ $monthlyTotals['yellow'] }}</td>
                        <td class="py-3 px-4 border-b text-center bg-red-100">{{ $monthlyTotals['red'] }}</td>
                        <td class="py-3 px-4 border-b text-center font-bold">{{ $monthlyTotals['grand_total'] }}</td>
                        <td class="py-3 px-4 border-b text-center">{{ $monthlyTotals['ambulance_call'] }}</td>
                        <td class="py-3 px-4 border-b text-center">{{ $monthlyTotals['admission'] }}</td>
                        <td class="py-3 px-4 border-b text-center">{{ $monthlyTotals['transfer'] }}</td>
                        <td class="py-3 px-4 border-b text-center">{{ $monthlyTotals['death'] }}</td>
                        <td class="py-3 px-4 border-b"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @endif

    <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-between">
        <a href="{{ route('emergency.bor.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-center">
            <i class="fas fa-arrow-left mr-2"></i>Back to BOR Dashboard
        </a>

        <!-- Add PDF Export Button if needed -->
        <button onclick="window.print()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-center">
            <i class="fas fa-print mr-2"></i>Print Report
        </button>
    </div>
</div>
@endsection
