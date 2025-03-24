@extends('layout')

@section('title', 'New Ward Entry - NSBM')
@section('header', 'New Ward Entry')

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
            </div>
        </div>
    </div>

    <form action="{{ route('ward.entry.store') }}" method="POST">
        @csrf

        <!-- Shift Selection -->
        <div class="mb-6">
            <label for="shift_id" class="block mb-2 font-medium text-lg" style="color: var(--color-text-primary);">Shift Selection</label>
            <select name="shift_id" id="shift_id" class="w-full px-4 py-2 border rounded-md focus:outline-none" style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);" required>
                <option value="">-- Select Shift --</option>
                @foreach($shifts as $shift)
                    <option value="{{ $shift->id }}"
                        {{ old('shift_id', $nextShiftId) == $shift->id ? 'selected' : '' }}
                        {{ in_array($shift->id, $filledShifts ?? []) ? 'disabled' : '' }}>
                        {{ $shift->name }} {{ in_array($shift->id, $filledShifts ?? []) ? '(Already filled today)' : '' }}
                    </option>
                @endforeach
            </select>
            @error('shift_id')
                <p class="text-accent text-sm mt-1">{{ $message }}</p>
            @enderror

            @if(session('error'))
                <p class="text-accent text-sm mt-1">{{ session('error') }}</p>
            @endif

            <p class="text-sm mt-2" style="color: var(--color-text-secondary);">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color: var(--color-primary);">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Shifts that have already been filled today are disabled. The next logical shift is selected automatically.
            </p>
        </div>

        <!-- Section 1: Patient Carry Forward -->
        <div class="mb-6 p-4 rounded-lg border" style="background-color: var(--color-primary-light); border-color: var(--color-border);">
            <h3 class="text-lg font-medium mb-4" style="color: var(--color-secondary);">Patient Carry Forward</h3>
            <div class="mb-4">
                <label for="cf_patient" class="block mb-2" style="color: var(--color-text-primary);">CF Patients</label>
                <input type="number" name="cf_patient" id="cf_patient" min="0"
                class="w-full px-4 py-2 border rounded-md focus:outline-none"
                style="{{ isset($cfPatient) && $cfPatient > 0 ? 'background-color: var(--color-success-light); border-color: var(--color-success);' : 'background-color: var(--color-input-bg); border-color: var(--color-border);' }} color: var(--color-text-primary);"
                value="{{ old('cf_patient', $cfPatient ?? 0) }}" required>
                @if(isset($cfPatient) && $cfPatient > 0)
                    <p class="text-sm mt-1" style="color: var(--color-success);">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Auto-filled from previous shift's data ({{ $cfPatient }})
                    </p>
                @endif
                @error('cf_patient')
                    <p class="text-accent text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Section 2: Patient Movement -->
        <div class="mb-6 p-4 rounded-lg border" style="background-color: var(--color-primary-light); border-color: var(--color-border);">
            <h3 class="text-lg font-medium mb-4" style="color: var(--color-secondary);">Patient Movement</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="mb-4">
                        <label for="total_admission" class="block mb-2" style="color: var(--color-text-primary);">Total Admissions</label>
                        <input type="number" name="total_admission" id="total_admission" min="0"
                        class="w-full px-4 py-2 border rounded-md focus:outline-none"
                        style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                        value="{{ old('total_admission', 0) }}" required>
                        @error('total_admission')
                            <p class="text-accent text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="total_transfer_in" class="block mb-2" style="color: var(--color-text-primary);">Total Transfer In</label>
                        <input type="number" name="total_transfer_in" id="total_transfer_in" min="0"
                        class="w-full px-4 py-2 border rounded-md focus:outline-none"
                        style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                        value="{{ old('total_transfer_in', 0) }}" required>
                        @error('total_transfer_in')
                            <p class="text-accent text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <div class="mb-4">
                        <label for="total_transfer_out" class="block mb-2" style="color: var(--color-text-primary);">Total Transfer Out</label>
                        <input type="number" name="total_transfer_out" id="total_transfer_out" min="0"
                        class="w-full px-4 py-2 border rounded-md focus:outline-none"
                        style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                        value="{{ old('total_transfer_out', 0) }}" required>
                        @error('total_transfer_out')
                            <p class="text-accent text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="total_discharge" class="block mb-2" style="color: var(--color-text-primary);">Total Discharges</label>
                        <input type="number" name="total_discharge" id="total_discharge" min="0"
                        class="w-full px-4 py-2 border rounded-md focus:outline-none"
                        style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                        value="{{ old('total_discharge', 0) }}" required>
                        @error('total_discharge')
                            <p class="text-accent text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="aor" class="block mb-2" style="color: var(--color-text-primary);">At Own Risk Discharges</label>
                        <input type="number" name="aor" id="aor" min="0"
                        class="w-full px-4 py-2 border rounded-md focus:outline-none"
                        style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                        value="{{ old('aor', 0) }}" required>
                        <p class="text-sm mt-1" style="color: var(--color-text-secondary);">Number of patients discharged at their own risk</p>
                        @error('aor')
                            <p class="text-accent text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Staff Information -->
        <div class="mb-6 p-4 rounded-lg border" style="background-color: var(--color-primary-light); border-color: var(--color-border);">
            <h3 class="text-lg font-medium mb-4" style="color: var(--color-secondary);">Staff Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label for="total_staff_on_duty" class="block mb-2" style="color: var(--color-text-primary);">Total Staff on Duty</label>
                    <input type="number" name="total_staff_on_duty" id="total_staff_on_duty" min="0"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none"
                    style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                    value="{{ old('total_staff_on_duty', 0) }}" required>
                    @error('total_staff_on_duty')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="overtime" class="block mb-2" style="color: var(--color-text-primary);">Overtime</label>
                    <input type="number" name="overtime" id="overtime" min="0"
                    class="w-full px-4 py-2 border rounded-md focus:outline-none"
                    style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                    value="{{ old('overtime', 0) }}" required>
                    @error('overtime')
                        <p class="text-accent text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section 4: ND Shift Daily Totals (Only visible for ND Shift) -->
        <div class="mb-6 p-4 rounded-lg border nd-shift-fields" style="display: none; background-color: var(--color-primary-light); border-color: var(--color-border);">
            <h3 class="text-lg font-medium mb-4" style="color: var(--color-secondary);">Daily Patient Totals</h3>
            <div class="mb-4">
                <label for="total_daily_patients" class="block mb-2" style="color: var(--color-text-primary);">Total Daily Patients</label>
                <input type="number" name="total_daily_patients" id="total_daily_patients" min="0"
                class="w-full px-4 py-2 border rounded-md focus:outline-none"
                style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                value="{{ old('total_daily_patients', 0) }}" required>
                <p class="text-sm mt-1" style="color: var(--color-text-secondary);">Total daily patient count (for 24-hour reporting)</p>
                @error('total_daily_patients')
                    <p class="text-accent text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Section 5: Shift-Specific Totals -->
        <div class="mb-6 p-4 rounded-lg border" style="background-color: var(--color-secondary-light); border-color: var(--color-secondary-light);">
            <h3 class="text-lg font-medium mb-4" style="color: var(--color-secondary);">Shift-Specific Patient Totals</h3>
            <p class="text-sm mb-4" style="color: var(--color-text-secondary);">These values are automatically calculated based on your inputs above.</p>

            <!-- AM Shift specific fields -->
            <div class="shift-fields am-shift-fields mb-4 p-3 rounded border" style="display: none; background-color: var(--color-card); border-color: var(--color-border);">
                <h4 class="font-medium mb-3" style="color: var(--color-text-primary);">AM Shift Data (07:00 - 14:00)</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="total_patient_am" class="block mb-2" style="color: var(--color-text-primary);">Total Patients</label>
                        <input type="number" name="total_patient_am" id="total_patient_am" min="0"
                            class="w-full px-4 py-2 border rounded-md focus:outline-none"
                            style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                            value="{{ old('total_patient_am', 0) }}" readonly>
                        <p class="text-xs mt-1" style="color: var(--color-text-secondary);">Auto-calculated</p>
                        @error('total_patient_am')
                            <p class="text-accent text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="licensed_bed_bor_am" class="block mb-2" style="color: var(--color-text-primary);">Licensed Bed BOR (%)</label>
                        <input type="number" name="licensed_bed_bor_am" id="licensed_bed_bor_am" min="0" step="0.01"
                            class="w-full px-4 py-2 border rounded-md focus:outline-none"
                            style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                            value="{{ old('licensed_bed_bor_am', 0) }}" readonly>
                        <p class="text-xs mt-1" style="color: var(--color-text-secondary);">Based on licensed beds</p>
                        @error('licensed_bed_bor_am')
                            <p class="text-accent text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="total_bed_bor_am" class="block mb-2" style="color: var(--color-text-primary);">Total Bed BOR (%)</label>
                        <input type="number" name="total_bed_bor_am" id="total_bed_bor_am" min="0" step="0.01"
                            class="w-full px-4 py-2 border rounded-md focus:outline-none"
                            style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                            value="{{ old('total_bed_bor_am', 0) }}" readonly>
                        <p class="text-xs mt-1" style="color: var(--color-text-secondary);">Based on total beds</p>
                        @error('total_bed_bor_am')
                            <p class="text-accent text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- PM Shift specific fields -->
            <div class="shift-fields pm-shift-fields mb-4 p-3 rounded border" style="display: none; background-color: var(--color-card); border-color: var(--color-border);">
                <h4 class="font-medium mb-3" style="color: var(--color-text-primary);">PM Shift Data (14:00 - 21:00)</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="total_patient_pm" class="block mb-2" style="color: var(--color-text-primary);">Total Patients</label>
                        <input type="number" name="total_patient_pm" id="total_patient_pm" min="0"
                            class="w-full px-4 py-2 border rounded-md focus:outline-none"
                            style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                            value="{{ old('total_patient_pm', 0) }}" readonly>
                        <p class="text-xs mt-1" style="color: var(--color-text-secondary);">Auto-calculated</p>
                        @error('total_patient_pm')
                            <p class="text-accent text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="licensed_bed_bor_pm" class="block mb-2" style="color: var(--color-text-primary);">Licensed Bed BOR (%)</label>
                        <input type="number" name="licensed_bed_bor_pm" id="licensed_bed_bor_pm" min="0" step="0.01"
                            class="w-full px-4 py-2 border rounded-md focus:outline-none"
                            style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                            value="{{ old('licensed_bed_bor_pm', 0) }}" readonly>
                        <p class="text-xs mt-1" style="color: var(--color-text-secondary);">Based on licensed beds</p>
                        @error('licensed_bed_bor_pm')
                            <p class="text-accent text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="total_bed_bor_pm" class="block mb-2" style="color: var(--color-text-primary);">Total Bed BOR (%)</label>
                        <input type="number" name="total_bed_bor_pm" id="total_bed_bor_pm" min="0" step="0.01"
                            class="w-full px-4 py-2 border rounded-md focus:outline-none"
                            style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                            value="{{ old('total_bed_bor_pm', 0) }}" readonly>
                        <p class="text-xs mt-1" style="color: var(--color-text-secondary);">Based on total beds</p>
                        @error('total_bed_bor_pm')
                            <p class="text-accent text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- ND Shift specific fields -->
            <div class="shift-fields nd-shift-fields mb-4 p-3 rounded border" style="display: none; background-color: var(--color-card); border-color: var(--color-border);">
                <h4 class="font-medium mb-3" style="color: var(--color-text-primary);">ND Shift Data (21:00 - 07:00)</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="total_patient_nd" class="block mb-2" style="color: var(--color-text-primary);">Total Patients</label>
                        <input type="number" name="total_patient_nd" id="total_patient_nd" min="0"
                            class="w-full px-4 py-2 border rounded-md focus:outline-none"
                            style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                            value="{{ old('total_patient_nd', 0) }}" readonly>
                        <p class="text-xs mt-1" style="color: var(--color-text-secondary);">Auto-calculated</p>
                        @error('total_patient_nd')
                            <p class="text-accent text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="licensed_bed_bor_nd" class="block mb-2" style="color: var(--color-text-primary);">Licensed Bed BOR (%)</label>
                        <input type="number" name="licensed_bed_bor_nd" id="licensed_bed_bor_nd" min="0" step="0.01"
                            class="w-full px-4 py-2 border rounded-md focus:outline-none"
                            style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                            value="{{ old('licensed_bed_bor_nd', 0) }}" readonly>
                        <p class="text-xs mt-1" style="color: var(--color-text-secondary);">Based on licensed beds</p>
                        @error('licensed_bed_bor_nd')
                            <p class="text-accent text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="total_bed_bor_nd" class="block mb-2" style="color: var(--color-text-primary);">Total Bed BOR (%)</label>
                        <input type="number" name="total_bed_bor_nd" id="total_bed_bor_nd" min="0" step="0.01"
                            class="w-full px-4 py-2 border rounded-md focus:outline-none"
                            style="background-color: var(--color-input-bg); border-color: var(--color-border); color: var(--color-text-primary);"
                            value="{{ old('total_bed_bor_nd', 0) }}" readonly>
                        <p class="text-xs mt-1" style="color: var(--color-text-secondary);">Based on total beds</p>
                        @error('total_bed_bor_nd')
                            <p class="text-accent text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-2"></i>Save Entry
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const shiftSelect = document.getElementById('shift_id');
    const amShiftFields = document.querySelectorAll('.am-shift-fields');
    const pmShiftFields = document.querySelectorAll('.pm-shift-fields');
    const ndShiftFields = document.querySelectorAll('.nd-shift-fields');

    // All input fields we need for calculations
    const cfPatientInput = document.getElementById('cf_patient');
    const totalAdmissionInput = document.getElementById('total_admission');
    const totalTransferInInput = document.getElementById('total_transfer_in');
    const totalTransferOutInput = document.getElementById('total_transfer_out');
    const totalDischargeInput = document.getElementById('total_discharge');
    const aorInput = document.getElementById('aor');

    // Shift-specific patient and BOR fields
    const totalPatientAmInput = document.getElementById('total_patient_am');
    const totalPatientPmInput = document.getElementById('total_patient_pm');
    const totalPatientNdInput = document.getElementById('total_patient_nd');

    // Constants for calculations
    const totalBeds = {{ $ward->total_bed }};
    const totalLicensedBeds = {{ $ward->total_licensed_op_beds }};

    // Store the original CF patient value to detect changes
    const originalCfPatient = cfPatientInput.value;

    // Function to toggle visibility of shift-specific fields
    function toggleShiftFields() {
        const selectedOption = shiftSelect.options[shiftSelect.selectedIndex];

        // Check if the selected option is disabled (already filled)
        if (selectedOption.disabled) {
            alert('This shift has already been filled for today. Please select a different shift.');
            // Reset to no selection
            shiftSelect.selectedIndex = 0;
            // Hide all shift-specific fields
            amShiftFields.forEach(field => field.style.display = 'none');
            pmShiftFields.forEach(field => field.style.display = 'none');
            ndShiftFields.forEach(field => field.style.display = 'none');
            return;
        }

        const shiftName = selectedOption.text;

        // Hide all shift-specific fields first
        amShiftFields.forEach(field => field.style.display = 'none');
        pmShiftFields.forEach(field => field.style.display = 'none');
        ndShiftFields.forEach(field => field.style.display = 'none');

        // Show fields based on selected shift
        if (shiftName.includes('AM')) {
            amShiftFields.forEach(field => field.style.display = 'block');
        } else if (shiftName.includes('PM')) {
            pmShiftFields.forEach(field => field.style.display = 'block');
        } else if (shiftName.includes('ND')) {
            ndShiftFields.forEach(field => field.style.display = 'block');
        }

        // Calculate values for the selected shift
        calculateValues();
    }

    // Function to calculate Total Patients, BOR and AOR
    function calculateValues() {
        // Get the input values (default to 0 if empty or NaN)
        const cfPatient = parseInt(cfPatientInput.value) || 0;
        const totalAdmission = parseInt(totalAdmissionInput.value) || 0;
        const totalTransferIn = parseInt(totalTransferInInput.value) || 0;
        const totalTransferOut = parseInt(totalTransferOutInput.value) || 0;
        const totalDischarge = parseInt(totalDischargeInput.value) || 0;
        const aor = parseInt(aorInput.value) || 0; // Get AOR as entered by the user (at own risk discharges)

        // Calculate total patients using the formula:
        // Total Patients = CF Patients + Total Admissions + Total Transfer In - Total Discharges - Total Transfer Out - At Own Risk Discharges
        const totalPatients = cfPatient + totalAdmission + totalTransferIn - totalDischarge - totalTransferOut - aor;

        // Calculate BOR (Bed Occupancy Rate)
        // Two types of BOR calculations:

        // 1. Licensed Bed BOR (%) = (Total Patients / Total Licensed Beds Available) * 100
        let licensedBedBorPercentage = 0;
        if (totalLicensedBeds > 0) {
            licensedBedBorPercentage = (totalPatients / totalLicensedBeds) * 100;
        }

        // 2. Total Bed BOR (%) = (Total Patients / Total Beds) * 100
        let totalBedBorPercentage = 0;
        if (totalBeds > 0) {
            totalBedBorPercentage = (totalPatients / totalBeds) * 100;
        }

        // Update shift-specific fields based on selected shift
        const selectedOption = shiftSelect.options[shiftSelect.selectedIndex];
        const shiftName = selectedOption.text;

        if (shiftName.includes('AM')) {
            totalPatientAmInput.value = totalPatients;
            document.getElementById('licensed_bed_bor_am').value = licensedBedBorPercentage.toFixed(2);
            document.getElementById('total_bed_bor_am').value = totalBedBorPercentage.toFixed(2);
        } else if (shiftName.includes('PM')) {
            totalPatientPmInput.value = totalPatients;
            document.getElementById('licensed_bed_bor_pm').value = licensedBedBorPercentage.toFixed(2);
            document.getElementById('total_bed_bor_pm').value = totalBedBorPercentage.toFixed(2);
        } else if (shiftName.includes('ND')) {
            totalPatientNdInput.value = totalPatients;
            document.getElementById('licensed_bed_bor_nd').value = licensedBedBorPercentage.toFixed(2);
            document.getElementById('total_bed_bor_nd').value = totalBedBorPercentage.toFixed(2);
        }
    }

    // Set up SweetAlert2 warning for CF Patient field
    if (originalCfPatient > 0) {
        // Create a unique key for this particular ward entry session
        const cfWarningKey = 'cf_warning_shown_' + {{ $ward->id }} + '_' + new Date().toDateString();

        cfPatientInput.addEventListener('click', function(e) {
            // Check if we've already shown the warning today
            if (!localStorage.getItem(cfWarningKey)) {
                Swal.fire({
                    title: 'Carry Forward Patient Value',
                    text: 'This value is automatically calculated based on the previous shift. Modifying it may affect data consistency.',
                    icon: 'warning',
                    confirmButtonText: 'I understand',
                    confirmButtonColor: '#3085d6',
                    allowOutsideClick: false,  // Force user to click the button
                    showCancelButton: false,
                    showCloseButton: false
                }).then((result) => {
                    // Mark that we've shown the warning - both in localStorage and in memory
                    if (result.isConfirmed) {
                        localStorage.setItem(cfWarningKey, 'true');
                    }
                });
            }
        });
    }

    // Set initial state
    toggleShiftFields();

    // Add event listeners for changes in input fields
    shiftSelect.addEventListener('change', toggleShiftFields);

    // Add event listeners to inputs that affect calculations
    cfPatientInput.addEventListener('input', calculateValues);
    totalAdmissionInput.addEventListener('input', calculateValues);
    totalTransferInInput.addEventListener('input', calculateValues);
    totalTransferOutInput.addEventListener('input', calculateValues);
    totalDischargeInput.addEventListener('input', calculateValues);
    aorInput.addEventListener('input', calculateValues);

    // Trigger calculations immediately
    calculateValues();
});
</script>

<!-- Add SweetAlert2 library -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection
