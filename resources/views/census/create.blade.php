@extends('layout')

@section('title', '24 Hours Census (NSM) - NSBM')
@section('header', '24 Hours Census (NSM)')

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
            </div>
        </div>
    </div>

    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
        <h4 class="font-medium text-blue-800 mb-2">About 24 Hours Census</h4>
        <p class="text-sm text-blue-700">
            The 24 Hours Census is performed once per day (typically at midnight) and provides a snapshot of the ward's occupancy.
            It is used for statistical analysis and administrative reporting. The Bed Occupancy Rate (BOR) is automatically calculated
            based on the number of patients and the ward's licensed beds.
        </p>
    </div>

    <form action="{{ route('census.store') }}" method="POST">
        @csrf

        <!-- Census Information -->
        <div class="border-b border-gray-200 pb-6 mb-6">
            <h3 class="text-lg font-medium mb-4">Census Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="hours24_census" class="block text-gray-700 mb-2">24 Hours Census</label>
                    <input type="number" name="hours24_census" id="hours24_census"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                           value="{{ old('hours24_census', $existingEntry->hours24_census ?? '') }}" required min="0">
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
                           value="{{ old('cf_patient_2400', $existingEntry->cf_patient_2400 ?? '') }}" required min="0">
                    <p class="text-sm text-gray-500 mt-1">
                        Number of patients carried forward at midnight.
                    </p>
                    @error('cf_patient_2400')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            @if($existingEntry)
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center justify-between">
                    <span class="text-gray-700 font-medium">Current Bed Occupancy Rate:</span>
                    <span class="text-blue-600 font-bold text-lg">{{ number_format($existingEntry->bed_occupancy_rate, 2) }}%</span>
                </div>
                <p class="text-sm text-gray-500 mt-1">
                    This value will be recalculated when you save this form.
                </p>
            </div>
            @endif
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
                {{ $existingEntry ? 'Update Census' : 'Save Census' }}
            </button>
        </div>
    </form>
</div>
@endsection
