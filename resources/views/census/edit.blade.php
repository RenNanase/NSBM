@extends('layout')

@section('title', 'Edit 24 Hours Census (NSM) - NSBM')
@section('header', 'Edit 24 Hours Census (NSM)')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="border-b border-gray-200 pb-4 mb-6">
        <div class="flex items-center">
            <div class="bg-blue-100 rounded-full p-2 mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-medium">{{ $ward->name }}</h3>
                <p class="text-sm text-gray-600">Total Beds: {{ $ward->total_bed }} | Licensed Beds: {{ $ward->total_licensed_op_beds }}</p>
                <p class="text-sm text-gray-600">Date: {{ $entry->created_at->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-lg">
        <p class="text-amber-800 font-medium flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            Admin Edit Mode - Changes will automatically recalculate BOR values
        </p>
    </div>

    <form action="{{ route('census.update', $entry->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Census Information -->
        <div class="border-b border-gray-200 pb-6 mb-6">
            <h3 class="text-lg font-medium mb-4">Census Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="hours24_census" class="block text-gray-700 mb-2">24 Hours Census</label>
                    <input type="number" name="hours24_census" id="hours24_census"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                           value="{{ old('hours24_census', $entry->hours24_census) }}" required min="0">
                    <p class="text-sm text-gray-500 mt-1">
                        Total number of patients in the ward during the 24-hour period.
                    </p>
                    @error('hours24_census')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="cf_patient_2400" class="block text-gray-700 mb-2">C/F Patients at 24:00</label>
                    <input type="number" name="cf_patient_2400" id="cf_patient_2400"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                           value="{{ old('cf_patient_2400', $entry->cf_patient_2400) }}" required min="0">
                    <p class="text-sm text-gray-500 mt-1">
                        Number of patients carried forward at midnight.
                    </p>
                    @error('cf_patient_2400')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center justify-between">
                    <span class="text-gray-700 font-medium">Current Bed Occupancy Rate:</span>
                    <span class="text-blue-600 font-bold text-lg">{{ number_format($entry->bed_occupancy_rate, 2) }}%</span>
                </div>
                <p class="text-sm text-gray-500 mt-1">
                    This value will be recalculated when you save this form.
                </p>
            </div>
        </div>

        <!-- Formula Information -->
        <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
            <h4 class="font-medium text-gray-700 mb-2">Calculations</h4>
            <p class="text-sm text-gray-600">
                Bed Occupancy Rate (BOR) = (24 Hours Census / Licensed Beds) × 100%
            </p>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-between items-center">
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md transition text-gray-700">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition">
                Update Census
            </button>
        </div>
    </form>
</div>
@endsection
