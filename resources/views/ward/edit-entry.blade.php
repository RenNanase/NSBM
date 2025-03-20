@extends('layout')

@section('title', 'Edit Ward Entry - NSBM')
@section('header', 'Edit Ward Entry')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="border-b border-gray-200 pb-4 mb-6">
        <div class="flex items-center">
            <div class="bg-blue-100 rounded-full p-2 mr-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-medium">{{ $ward->name }}</h3>
                <p class="text-sm text-gray-600">Total Beds: {{ $ward->total_bed }} | Licensed Beds: {{ $ward->total_licensed_op_beds }}</p>
                <p class="text-sm text-gray-600">Shift: {{ $entry->shift->name }} | Date: {{ $entry->created_at->format('M d, Y H:i') }}</p>
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

    <form action="{{ route('ward.entry.update', $entry) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Hidden fields for shift and ward -->
        <input type="hidden" name="shift_id" value="{{ $entry->shift_id }}">

        <!-- Patient Information Section -->
        <div class="border-b border-gray-200 pb-6 mb-6">
            <h3 class="text-lg font-medium mb-4">Patient Information</h3>

            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <p class="text-blue-800 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Total Patients will be automatically calculated using the formula: CF Patients + Admissions + Transfer In - Discharges - Transfer Out - AOR
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="cf_patient" class="block text-gray-700 mb-2">Carried Forward Patients</label>
                    <input type="number" name="cf_patient" id="cf_patient"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                           value="{{ old('cf_patient', $entry->cf_patient) }}" required min="0">
                    @error('cf_patient')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="total_admission" class="block text-gray-700 mb-2">Total Admissions</label>
                    <input type="number" name="total_admission" id="total_admission"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                           value="{{ old('total_admission', $entry->total_admission) }}" required min="0">
                    @error('total_admission')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="total_transfer_in" class="block text-gray-700 mb-2">Total Transfer In</label>
                    <input type="number" name="total_transfer_in" id="total_transfer_in"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                           value="{{ old('total_transfer_in', $entry->total_transfer_in) }}" required min="0">
                    @error('total_transfer_in')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="total_transfer_out" class="block text-gray-700 mb-2">Total Transfer Out</label>
                    <input type="number" name="total_transfer_out" id="total_transfer_out"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                           value="{{ old('total_transfer_out', $entry->total_transfer_out) }}" required min="0">
                    @error('total_transfer_out')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="total_discharge" class="block text-gray-700 mb-2">Total Discharges</label>
                    <input type="number" name="total_discharge" id="total_discharge"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                           value="{{ old('total_discharge', $entry->total_discharge) }}" required min="0">
                    @error('total_discharge')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="aor" class="block text-gray-700 mb-2">At Own Risk Discharges (AOR)</label>
                    <input type="number" name="aor" id="aor"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                           value="{{ old('aor', $entry->aor) }}" required min="0">
                    @error('aor')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @if($entry->shift->name == 'ND SHIFT')
                <div>
                    <label for="total_daily_patients" class="block text-gray-700 mb-2">Total Daily Patients</label>
                    <input type="number" name="total_daily_patients" id="total_daily_patients"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                           value="{{ old('total_daily_patients', $entry->total_daily_patients) }}" min="0">
                    @error('total_daily_patients')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                <!-- Current Patient Count (Read-only) -->
                <div>
                    <label class="block text-gray-700 mb-2">Current Total Patients</label>
                    <div class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-md">
                        {{ $entry->total_patient }}
                    </div>
                    <p class="text-xs text-gray-500 mt-1">This value will be recalculated when you update</p>
                </div>
            </div>
        </div>

        <!-- Staff Information Section -->
        <div class="border-b border-gray-200 pb-6 mb-6">
            <h3 class="text-lg font-medium mb-4">Staff Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="total_staff_on_duty" class="block text-gray-700 mb-2">Total Staff on Duty</label>
                    <input type="number" name="total_staff_on_duty" id="total_staff_on_duty"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                           value="{{ old('total_staff_on_duty', $entry->total_staff_on_duty) }}" required min="0">
                    @error('total_staff_on_duty')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="overtime" class="block text-gray-700 mb-2">Overtime Hours</label>
                    <input type="number" name="overtime" id="overtime"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500"
                           value="{{ old('overtime', $entry->overtime) }}" required min="0">
                    @error('overtime')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Current BOR Values (Read-only) -->
        <div class="mb-6">
            <h3 class="text-lg font-medium mb-4">Current Bed Occupancy Rates</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 mb-2">Licensed Bed BOR (%)</label>
                    <div class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-md">
                        {{ number_format($entry->licensed_bed_bor, 2) }}%
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">Total Bed BOR (%)</label>
                    <div class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-md">
                        {{ number_format($entry->total_bed_bor, 2) }}%
                    </div>
                </div>
            </div>
            <div class="mt-3 p-3 bg-amber-50 border border-amber-200 rounded-lg">
                <p class="text-amber-800 text-sm flex items-start">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1 mt-1 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>
                        BOR values will be automatically recalculated when you update the form, using these formulas:<br>
                        - Total Patients = CF Patients + Admissions + Transfer In - Discharges - Transfer Out - AOR<br>
                        - Licensed Bed BOR (%) = (Total Patients / Licensed Beds) × 100<br>
                        - Total Bed BOR (%) = (Total Patients / Total Beds) × 100
                    </span>
                </p>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-between items-center">
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 rounded-md transition text-gray-700">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition">
                Update Entry
            </button>
        </div>
    </form>
</div>
@endsection
