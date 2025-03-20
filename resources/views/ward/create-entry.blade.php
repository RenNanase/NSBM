@extends('layout')

@section('title', 'New Ward Entry - NSBM')
@section('header', 'New Ward Entry')

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
            </div>
        </div>
    </div>

    <form action="{{ route('ward.entry.store') }}" method="POST">
        @csrf

        <!-- Shift Selection -->
        <div class="mb-6">
            <label for="shift_id" class="block text-gray-700 mb-2 font-medium text-lg">Shift Selection</label>
            <select name="shift_id" id="shift_id" class="w-full px-4 py-2 bg-gray-100 border border-gray-200 rounded-md focus:outline-none focus:border-gray-400" required>
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
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror

            @if(session('error'))
                <p class="text-red-500 text-sm mt-1">{{ session('error') }}</p>
            @endif

            <p class="text-gray-600 text-sm mt-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Shifts that have already been filled today are disabled. The next logical shift is selected automatically.
            </p>
        </div>

        <!-- Section 1: Patient Carry Forward -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <h3 class="text-lg font-medium mb-4 text-gray-800">Patient Carry Forward</h3>
            <div class="mb-4">
                <label for="cf_patient" class="block text-gray-700 mb-2">CF Patients</label>
                <input type="number" name="cf_patient" id="cf_patient" min="0"
                class="w-full px-4 py-2 {{ isset($cfPatient) && $cfPatient > 0 ? 'bg-green-50 border-green-200' : 'bg-gray-100 border-gray-200' }} border rounded-md focus:outline-none focus:border-gray-400"
                value="{{ old('cf_patient', $cfPatient ?? 0) }}" required>
                @if(isset($cfPatient) && $cfPatient > 0)
                    <p class="text-green-600 text-sm mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Auto-filled from previous shift's data ({{ $cfPatient }})
                    </p>
                @endif
                @error('cf_patient')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Section 2: Patient Movement -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <h3 class="text-lg font-medium mb-4 text-gray-800">Patient Movement</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <div class="mb-4">
                        <label for="total_admission" class="block text-gray-700 mb-2">Total Admissions</label>
                        <input type="number" name="total_admission" id="total_admission" min="0" class="w-full px-4 py-2 bg-gray-100 border border-gray-200 rounded-md focus:outline-none focus:border-gray-400" value="{{ old('total_admission', 0) }}" required>
                        @error('total_admission')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="total_transfer_in" class="block text-gray-700 mb-2">Total Transfer In</label>
                        <input type="number" name="total_transfer_in" id="total_transfer_in" min="0" class="w-full px-4 py-2 bg-gray-100 border border-gray-200 rounded-md focus:outline-none focus:border-gray-400" value="{{ old('total_transfer_in', 0) }}" required>
                        @error('total_transfer_in')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <div class="mb-4">
                        <label for="total_transfer_out" class="block text-gray-700 mb-2">Total Transfer Out</label>
                        <input type="number" name="total_transfer_out" id="total_transfer_out" min="0" class="w-full px-4 py-2 bg-gray-100 border border-gray-200 rounded-md focus:outline-none focus:border-gray-400" value="{{ old('total_transfer_out', 0) }}" required>
                        @error('total_transfer_out')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="total_discharge" class="block text-gray-700 mb-2">Total Discharges</label>
                        <input type="number" name="total_discharge" id="total_discharge" min="0" class="w-full px-4 py-2 bg-gray-100 border border-gray-200 rounded-md focus:outline-none focus:border-gray-400" value="{{ old('total_discharge', 0) }}" required>
                        @error('total_discharge')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="aor" class="block text-gray-700 mb-2">At Own Risk Discharges</label>
                        <input type="number" name="aor" id="aor" min="0" class="w-full px-4 py-2 bg-gray-100 border border-gray-200 rounded-md focus:outline-none focus:border-gray-400" value="{{ old('aor', 0) }}" required>
                        <p class="text-sm text-gray-500 mt-1">Number of patients discharged at their own risk</p>
                        @error('aor')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Staff Information -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <h3 class="text-lg font-medium mb-4 text-gray-800">Staff Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label for="total_staff_on_duty" class="block text-gray-700 mb-2">Total Staff on Duty</label>
                    <input type="number" name="total_staff_on_duty" id="total_staff_on_duty" min="0" class="w-full px-4 py-2 bg-gray-100 border border-gray-200 rounded-md focus:outline-none focus:border-gray-400" value="{{ old('total_staff_on_duty', 0) }}" required>
                    @error('total_staff_on_duty')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="overtime" class="block text-gray-700 mb-2">Overtime</label>
                    <input type="number" name="overtime" id="overtime" min="0" class="w-full px-4 py-2 bg-gray-100 border border-gray-200 rounded-md focus:outline-none focus:border-gray-400" value="{{ old('overtime', 0) }}" required>
                    @error('overtime')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section 4: ND Shift Daily Totals (Only visible for ND Shift) -->
        <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200 nd-shift-fields" style="display: none;">
            <h3 class="text-lg font-medium mb-4 text-gray-800">Daily Patient Totals</h3>
            <div class="mb-4">
                <label for="total_daily_patients" class="block text-gray-700 mb-2">Total Daily Patients</label>
                <input type="number" name="total_daily_patients" id="total_daily_patients" min="0" class="w-full px-4 py-2 bg-gray-100 border border-gray-200 rounded-md focus:outline-none focus:border-gray-400" value="{{ old('total_daily_patients', 0) }}" required>
                <p class="text-sm text-gray-500 mt-1">Total daily patient count (for 24-hour reporting)</p>
                @error('total_daily_patients')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Section 5: Shift-Specific Totals -->
        <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <h3 class="text-lg font-medium mb-4 text-blue-800">Shift-Specific Patient Totals</h3>
            <p class="text-sm text-gray-600 mb-4">These values are automatically calculated based on your inputs above.</p>

            <!-- AM Shift specific fields -->
            <div class="shift-fields am-shift-fields mb-4 p-3 bg-white rounded border border-gray-200" style="display: none;">
                <h4 class="font-medium text-gray-700 mb-3">AM Shift Data (07:00 - 14:00)</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="total_patient_am" class="block text-gray-700 mb-2">Total Patients</label>
                        <input type="number" name="total_patient_am" id="total_patient_am" min="0"
                            class="w-full px-4 py-2 bg-gray-100 border border-gray-200 rounded-md focus:outline-none focus:border-gray-400"
                            value="{{ old('total_patient_am', 0) }}" readonly>
                        <p class="text-xs text-gray-500 mt-1">Auto-calculated</p>
                        @error('total_patient_am')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="licensed_bed_bor_am" class="block text-gray-700 mb-2">Licensed Bed BOR (%)</label>
                        <input type="number" name="licensed_bed_bor_am" id="licensed_bed_bor_am" min="0" step="0.01"
                            class="w-full px-4 py-2 bg-gray-100 border border-gray-200 rounded-md focus:outline-none focus:border-gray-400"
                            value="{{ old('licensed_bed_bor_am', 0) }}" readonly>
                        <p class="text-xs text-gray-500 mt-1">Based on licensed beds</p>
                        @error('licensed_bed_bor_am')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="total_bed_bor_am" class="block text-gray-700 mb-2">Total Bed BOR (%)</label>
                        <input type="number" name="total_bed_bor_am" id="total_bed_bor_am" min="0" step="0.01"
                            class="w-full px-4 py-2 bg-gray-100 border border-gray-200 rounded-md focus:outline-none focus:border-gray-400"
                            value="{{ old('total_bed_bor_am', 0) }}" readonly>
                        <p class="text-xs text-gray-500 mt-1">Based on total beds</p>
                        @error('total_bed_bor_am')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- PM Shift specific fields -->
            <div class="shift-fields pm-shift-fields mb-4 p-3 bg-white rounded border border-gray-200" style="display: none;">
                <h4 class="font-medium text-gray-700 mb-3">PM Shift Data (14:00 - 21:00)</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="total_patient_pm" class="block text-gray-700 mb-2">Total Patients</label>
                        <input type="number" name="total_patient_pm" id="total_patient_pm" min="0"
                            class="w-full px-4 py-2 bg-gray-100 border border-gray-200 rounded-md focus:outline-none focus:border-gray-400"
                            value="{{ old('total_patient_pm', 0) }}" readonly>
                        <p class="text-xs text-gray-500 mt-1">Auto-calculated</p>
                        @error('total_patient_pm')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="licensed_bed_bor_pm" class="block text-gray-700 mb-2">Licensed Bed BOR (%)</label>
                        <input type="number" name="licensed_bed_bor_pm" id="licensed_bed_bor_pm" min="0" step="0.01"
                            class="w-full px-4 py-2 bg-gray-100 border border-gray-200 rounded-md focus:outline-none focus:border-gray-400"
                            value="{{ old('licensed_bed_bor_pm', 0) }}" readonly>
                        <p class="text-xs text-gray-500 mt-1">Based on licensed beds</p>
                        @error('licensed_bed_bor_pm')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="total_bed_bor_pm" class="block text-gray-700 mb-2">Total Bed BOR (%)</label>
                        <input type="number" name="total_bed_bor_pm" id="total_bed_bor_pm" min="0" step="0.01"
                            class="w-full px-4 py-2 bg-gray-100 border border-gray-200 rounded-md focus:outline-none focus:border-gray-400"
                            value="{{ old('total_bed_bor_pm', 0) }}" readonly>
                        <p class="text-xs text-gray-500 mt-1">Based on total beds</p>
                        @error('total_bed_bor_pm')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- ND Shift specific fields -->
            <div class="shift-fields nd-shift-fields mb-4 p-3 bg-white rounded border border-gray-200" style="display: none;">
                <h4 class="font-medium text-gray-700 mb-3">ND Shift Data (21:00 - 07:00)</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="total_patient_nd" class="block text-gray-700 mb-2">Total Patients</label>
                        <input type="number" name="total_patient_nd" id="total_patient_nd" min="0"
                            class="w-full px-4 py-2 bg-gray-100 border border-gray-200 rounded-md focus:outline-none focus:border-gray-400"
                            value="{{ old('total_patient_nd', 0) }}" readonly>
                        <p class="text-xs text-gray-500 mt-1">Auto-calculated</p>
                        @error('total_patient_nd')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="licensed_bed_bor_nd" class="block text-gray-700 mb-2">Licensed Bed BOR (%)</label>
                        <input type="number" name="licensed_bed_bor_nd" id="licensed_bed_bor_nd" min="0" step="0.01"
                            class="w-full px-4 py-2 bg-gray-100 border border-gray-200 rounded-md focus:outline-none focus:border-gray-400"
                            value="{{ old('licensed_bed_bor_nd', 0) }}" readonly>
                        <p class="text-xs text-gray-500 mt-1">Based on licensed beds</p>
                        @error('licensed_bed_bor_nd')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="total_bed_bor_nd" class="block text-gray-700 mb-2">Total Bed BOR (%)</label>
                        <input type="number" name="total_bed_bor_nd" id="total_bed_bor_nd" min="0" step="0.01"
                            class="w-full px-4 py-2 bg-gray-100 border border-gray-200 rounded-md focus:outline-none focus:border-gray-400"
                            value="{{ old('total_bed_bor_nd', 0) }}" readonly>
                        <p class="text-xs text-gray-500 mt-1">Based on total beds</p>
                        @error('total_bed_bor_nd')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md mr-2 hover:bg-gray-300 transition duration-200">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                Save Entry
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
