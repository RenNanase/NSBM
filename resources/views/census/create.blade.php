@extends('layout')

@section('title', '24 Hours Census (NSM) - NSBM')
@section('header', '24 Hours Census (NSM)')

@section('content')
<div class="dashboard-card p-6 mb-6">
    <div class="border-b pb-4 mb-6" style="border-color: var(--color-border);">
        <div class="flex items-center">
            <div class="rounded-full p-2 mr-3" style="background-color: var(--color-primary-light);">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: var(--color-primary);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-medium" style="color: var(--color-text-primary);">{{ $ward->name }}</h3>
                <p class="text-sm" style="color: var(--color-text-secondary);">Total Beds: {{ $ward->total_bed }} | Licensed Beds: {{ $ward->total_licensed_op_beds }}</p>
            </div>
        </div>
    </div>

    <div class="mb-6 p-4 rounded-lg border" style="background-color: var(--color-secondary-light); border-color: var(--color-secondary-light);">
        <h4 class="font-medium mb-2" style="color: var(--color-primary-dark);">About 24 Hours Census</h4>
        <p class="text-sm" style="color: var(--color-text-primary);">
            The 24 Hours Census is performed once per day (typically at midnight) and provides a snapshot of the ward's occupancy.
            It is used for statistical analysis and administrative reporting. The Bed Occupancy Rate (BOR) is automatically calculated
            based on the number of patients and the ward's licensed beds.
        </p>
    </div>

    <form action="{{ route('census.store') }}" method="POST">
        @csrf

        <!-- Census Information -->
        <div class="border-b pb-6 mb-6" style="border-color: var(--color-border);">
            <h3 class="text-lg font-medium mb-4" style="color: var(--color-text-primary);">Census Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="hours24_census" class="block mb-2" style="color: var(--color-text-primary);">24 Hours Census</label>
                    <input type="number" name="hours24_census" id="hours24_census"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none"
                           style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                           value="{{ old('hours24_census', $existingEntry->hours24_census ?? '') }}" required min="0">
                    <p class="text-sm mt-1" style="color: var(--color-text-secondary);">
                        Total number of patients in the ward during the 24-hour period.
                    </p>
                    @error('hours24_census')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="cf_patient_2400" class="block mb-2" style="color: var(--color-text-primary);">C/F Patients at 24:00</label>
                    <input type="number" name="cf_patient_2400" id="cf_patient_2400"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none"
                           style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                           value="{{ old('cf_patient_2400', $existingEntry->cf_patient_2400 ?? '') }}" required min="0">
                    <p class="text-sm mt-1" style="color: var(--color-text-secondary);">
                        Number of patients carried forward at midnight.
                    </p>
                    @error('cf_patient_2400')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            @if($existingEntry)
            <div class="mt-6 p-4 rounded-lg" style="background-color: var(--color-primary-light); border-color: var(--color-border);">
                <div class="flex items-center justify-between">
                    <span style="color: var(--color-text-primary);" class="font-medium">Current Bed Occupancy Rate:</span>
                    <span style="color: var(--color-primary);" class="font-bold text-lg">{{ number_format($existingEntry->bed_occupancy_rate, 2) }}%</span>
                </div>
                <p class="text-sm mt-1" style="color: var(--color-text-secondary);">
                    This value will be recalculated when you save this form.
                </p>
            </div>
            @endif
        </div>

        <!-- Formula Information -->
        <div class="mb-6 p-4 rounded-lg border" style="background-color: var(--color-primary-light); border-color: var(--color-border);">
            <h4 class="font-medium mb-2" style="color: var(--color-secondary);">Calculations</h4>
            <p class="text-sm" style="color: var(--color-text-secondary);">
                Bed Occupancy Rate (BOR) = (24 Hours Census / Licensed Beds) × 100%
            </p>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-between items-center">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-2"></i>{{ $existingEntry ? 'Update Census' : 'Save Census' }}
            </button>
        </div>
    </form>
</div>
@endsection
