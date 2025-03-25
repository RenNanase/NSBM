@extends('layout')

@section('title', 'Edit Ward Entry - NSBM')
@section('header', 'Edit Ward Entry')

@section('content')
<div class="dashboard-card p-6 mb-6">
    <div class="border-b pb-4 mb-6" style="border-color: var(--color-border);">
        <div class="flex items-center">
            <div class="rounded-full p-2 mr-3" style="background-color: var(--color-primary-light);">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: var(--color-primary);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-medium" style="color: var(--color-text-primary);">{{ $ward->name }}</h3>
                <p class="text-sm" style="color: var(--color-text-secondary);">Total Beds: {{ $ward->total_bed }} | Licensed Beds: {{ $ward->total_licensed_op_beds }}</p>
                <p class="text-sm" style="color: var(--color-text-secondary);">Shift: {{ $entry->shift->name }} | Date: {{ $entry->created_at->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="mb-4 p-3 rounded-lg" style="background-color: var(--color-secondary-light); border: 1px solid var(--color-border);">
        <p class="font-medium flex items-center" style="color: var(--color-secondary-dark);">
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
        <div class="border-b pb-6 mb-6" style="border-color: var(--color-border);">
            <h3 class="text-lg font-medium mb-4" style="color: var(--color-text-primary);">Patient Information</h3>

            <div class="mb-4 p-3 rounded-lg" style="background-color: var(--color-primary-light); border: 1px solid var(--color-border);">
                <p class="text-sm" style="color: var(--color-text-primary);">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Total Patients will be automatically calculated using the formula: CF Patients + Admissions + Transfer In - Discharges - Transfer Out - AOR
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="cf_patient" class="block mb-2" style="color: var(--color-text-primary);">Carried Forward Patients</label>
                    <input type="number" name="cf_patient" id="cf_patient"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none"
                           style="{{ isset($cfPatient) && $cfPatient > 0 ? 'background-color: var(--color-success-light); border-color: var(--color-success);' : 'background-color: var(--color-input-bg); border-color: var(--color-border);' }} color: var(--color-text-primary);"
                           value="{{ old('cf_patient', $entry->cf_patient) }}" required min="0"
                           {{ Auth::user()->isAdmin() ? '' : 'readonly' }}>
                    @if(!Auth::user()->isAdmin())
                        <p class="text-sm mt-1" style="color: var(--color-text-secondary);">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Only administrators can modify this value.
                        </p>
                    @endif
                    @error('cf_patient')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="total_admission" class="block mb-2" style="color: var(--color-text-primary);">Total Admissions</label>
                    <input type="number" name="total_admission" id="total_admission"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none"
                           style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                           value="{{ old('total_admission', $entry->total_admission) }}" required min="0">
                    @error('total_admission')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="total_transfer_in" class="block mb-2" style="color: var(--color-text-primary);">Total Transfer In</label>
                    <input type="number" name="total_transfer_in" id="total_transfer_in"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none"
                           style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                           value="{{ old('total_transfer_in', $entry->total_transfer_in) }}" required min="0">
                    @error('total_transfer_in')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="total_transfer_out" class="block mb-2" style="color: var(--color-text-primary);">Total Transfer Out</label>
                    <input type="number" name="total_transfer_out" id="total_transfer_out"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none"
                           style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                           value="{{ old('total_transfer_out', $entry->total_transfer_out) }}" required min="0">
                    @error('total_transfer_out')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="total_discharge" class="block mb-2" style="color: var(--color-text-primary);">Total Discharges</label>
                    <input type="number" name="total_discharge" id="total_discharge"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none"
                           style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                           value="{{ old('total_discharge', $entry->total_discharge) }}" required min="0">
                    @error('total_discharge')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="aor" class="block mb-2" style="color: var(--color-text-primary);">At Own Risk Discharges (AOR)</label>
                    <input type="number" name="aor" id="aor"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none"
                           style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                           value="{{ old('aor', $entry->aor) }}" required min="0">
                    @error('aor')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @if($entry->shift->name == 'ND SHIFT')
                <div>
                    <label for="total_daily_patients" class="block mb-2" style="color: var(--color-text-primary);">Total Daily Patients</label>
                    <input type="number" name="total_daily_patients" id="total_daily_patients"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none"
                           style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                           value="{{ old('total_daily_patients', $entry->total_daily_patients) }}" min="0">
                    @error('total_daily_patients')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                <!-- Current Patient Count (Read-only) -->
                <div>
                    <label class="block mb-2" style="color: var(--color-text-primary);">Current Total Patients</label>
                    <div class="w-full px-4 py-2 rounded-md" style="background-color: var(--color-bg-alt); border: 1px solid var(--color-border); color: var(--color-text-primary);">
                        {{ $entry->total_patient }}
                    </div>
                    <p class="text-xs mt-1" style="color: var(--color-text-secondary);">This value will be recalculated when you update</p>
                </div>
            </div>
        </div>

        <!-- Staff Information Section -->
        <div class="border-b pb-6 mb-6" style="border-color: var(--color-border);">
            <h3 class="text-lg font-medium mb-4" style="color: var(--color-text-primary);">Staff Information</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="total_staff_on_duty" class="block mb-2" style="color: var(--color-text-primary);">Total Staff on Duty</label>
                    <input type="number" name="total_staff_on_duty" id="total_staff_on_duty"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none"
                           style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                           value="{{ old('total_staff_on_duty', $entry->total_staff_on_duty) }}" required min="0">
                    @error('total_staff_on_duty')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="overtime" class="block mb-2" style="color: var(--color-text-primary);">Overtime Hours</label>
                    <input type="number" name="overtime" id="overtime"
                           class="w-full px-4 py-2 border rounded-md focus:outline-none"
                           style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                           value="{{ old('overtime', $entry->overtime) }}" required min="0">
                    @error('overtime')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Current BOR Values (Read-only) -->
        <div class="mb-6">
            <h3 class="text-lg font-medium mb-4" style="color: var(--color-text-primary);">Current Bed Occupancy Rates</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block mb-2" style="color: var(--color-text-primary);">Licensed Bed BOR (%)</label>
                    <div class="w-full px-4 py-2 rounded-md" style="background-color: var(--color-bg-alt); border: 1px solid var(--color-border); color: var(--color-text-primary);">
                        {{ number_format($entry->licensed_bed_bor, 2) }}%
                    </div>
                </div>

                <div>
                    <label class="block mb-2" style="color: var(--color-text-primary);">Total Bed BOR (%)</label>
                    <div class="w-full px-4 py-2 rounded-md" style="background-color: var(--color-bg-alt); border: 1px solid var(--color-border); color: var(--color-text-primary);">
                        {{ number_format($entry->total_bed_bor, 2) }}%
                    </div>
                </div>
            </div>
            <div class="mt-3 p-3 rounded-lg" style="background-color: var(--color-secondary-light); border: 1px solid var(--color-border);">
                <p class="text-sm flex items-start" style="color: var(--color-text-primary);">
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
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-2"></i> Update Entry
            </button>
        </div>
    </form>
</div>
@endsection
