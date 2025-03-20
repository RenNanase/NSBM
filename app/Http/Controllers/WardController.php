<?php

namespace App\Http\Controllers;

use App\Models\Ward;
use App\Models\Shift;
use App\Models\WardEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WardController extends Controller
{
    /**
     * Show the form for creating a new ward entry
     */
    public function createEntry()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if a ward is selected
        $wardId = session('selected_ward_id');
        if (!$wardId) {
            return redirect()->route('ward.select');
        }

        $ward = Ward::findOrFail($wardId);
        $shifts = Shift::all();

        // Check if user has access to this ward
        if (!Auth::user()->wards->contains($ward->id)) {
            return redirect()->route('ward.select')
                ->with('error', 'You do not have access to this ward.');
        }

        // Get today's date (Y-m-d format)
        $today = now()->format('Y-m-d');

        // Find shifts that have already been filled today for this ward
        $filledShifts = WardEntry::where('ward_id', $wardId)
            ->whereDate('created_at', $today)
            ->pluck('shift_id')
            ->toArray();

        // Get the CF patient value from the previous shift if it exists
        $cfPatient = null;

        // Define the shift sequence: AM -> PM -> ND
        $shiftSequence = [
            'AM SHIFT' => 1,
            'PM SHIFT' => 2,
            'ND SHIFT' => 3
        ];

        // Get shifts that are not filled yet
        $availableShifts = $shifts->filter(function($shift) use ($filledShifts) {
            return !in_array($shift->id, $filledShifts);
        });

        // If there are no available shifts, return all shifts (for viewing purposes)
        if ($availableShifts->isEmpty()) {
            $availableShifts = $shifts;
        }

        // Determine the next shift to be filled based on the shifts already filled
        $nextShiftId = null;
        if (!empty($filledShifts)) {
            // Get the names of the filled shifts
            $filledShiftNames = Shift::whereIn('id', $filledShifts)->pluck('name')->toArray();

            // Determine which shift should be next based on sequence
            $maxSequence = 0;
            foreach ($filledShiftNames as $name) {
                if (isset($shiftSequence[$name]) && $shiftSequence[$name] > $maxSequence) {
                    $maxSequence = $shiftSequence[$name];
                }
            }

            // Next shift in sequence
            $nextSequence = $maxSequence + 1;

            // Find the shift id for the next sequence
            foreach ($shifts as $shift) {
                if (isset($shiftSequence[$shift->name]) && $shiftSequence[$shift->name] == $nextSequence) {
                    $nextShiftId = $shift->id;
                    break;
                }
            }
        } else {
            // If no shifts filled, start with AM SHIFT
            $nextShiftId = $shifts->where('name', 'AM SHIFT')->first()->id ?? null;
        }

        // Get the last filled shift data
        $lastEntry = null;
        if (!empty($filledShifts)) {
            // Find the most recent entry for this ward today
            $lastEntry = WardEntry::where('ward_id', $wardId)
                ->whereDate('created_at', $today)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($lastEntry) {
                // Calculate next CF patient value based on the formula:
                // CF patient for next shift = CF patient + total_admission + total_transfer_in - total_transfer_out - total_discharge - AOR (At Own Risk discharges)
                $cfPatient = $lastEntry->cf_patient + $lastEntry->total_admission +
                             $lastEntry->total_transfer_in - $lastEntry->total_transfer_out -
                             $lastEntry->total_discharge - $lastEntry->aor;

                // Round to nearest integer and ensure it's not negative
                $cfPatient = max(0, round($cfPatient));
            }
        }

        // If we have a session value, use it (it's more up-to-date than the database)
        if (session('next_shift_cf_patient_ward_id') == $wardId &&
            session('next_shift_cf_patient_date') == $today) {
            $cfPatient = session('next_shift_cf_patient');
        }

        return view('ward.create-entry', compact('ward', 'shifts', 'filledShifts', 'cfPatient', 'nextShiftId'));
    }

    /**
     * Store a new ward entry
     */
    public function storeEntry(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if a ward is selected
        $wardId = session('selected_ward_id');
        if (!$wardId) {
            return redirect()->route('ward.select');
        }

        $ward = Ward::findOrFail($wardId);

        // Check if user has access to this ward
        if (!Auth::user()->wards->contains($ward->id)) {
            return redirect()->route('ward.select')
                ->with('error', 'You do not have access to this ward.');
        }

        // Get today's date (Y-m-d format)
        $today = now()->format('Y-m-d');

        // Check if this shift has already been filled today
        $shiftAlreadyFilled = WardEntry::where('ward_id', $wardId)
            ->where('shift_id', $request->shift_id)
            ->whereDate('created_at', $today)
            ->exists();

        if ($shiftAlreadyFilled) {
            return redirect()->route('ward.entry.create')
                ->with('error', 'This shift has already been filled for today.');
        }

        // Get the shift details to determine which validation rules to apply
        $shift = Shift::findOrFail($request->shift_id);
        $shiftName = $shift->name;

        // Basic validation rules that apply to all shifts
        $validationRules = [
            'shift_id' => 'required|exists:shifts,id',
            'cf_patient' => 'required|integer|min:0',
            'total_admission' => 'required|integer|min:0',
            'total_transfer_in' => 'required|integer|min:0',
            'total_transfer_out' => 'required|integer|min:0',
            'total_discharge' => 'required|integer|min:0',
            'aor' => 'required|integer|min:0', // At Own Risk discharges
            'total_staff_on_duty' => 'required|integer|min:0',
            'overtime' => 'required|integer|min:0',
        ];

        // Add shift-specific validation rules
        if (strpos($shiftName, 'AM') !== false) {
            $validationRules['total_patient_am'] = 'required|integer|min:0';
            $validationRules['licensed_bed_bor_am'] = 'required|numeric|min:0';
            $validationRules['total_bed_bor_am'] = 'required|numeric|min:0';
        } else if (strpos($shiftName, 'PM') !== false) {
            $validationRules['total_patient_pm'] = 'required|integer|min:0';
            $validationRules['licensed_bed_bor_pm'] = 'required|numeric|min:0';
            $validationRules['total_bed_bor_pm'] = 'required|numeric|min:0';
        } else if (strpos($shiftName, 'ND') !== false) {
            $validationRules['total_patient_nd'] = 'required|integer|min:0';
            $validationRules['licensed_bed_bor_nd'] = 'required|numeric|min:0';
            $validationRules['total_bed_bor_nd'] = 'required|numeric|min:0';
            $validationRules['total_daily_patients'] = 'required|integer|min:0';
        }

        // Validate input
        $validatedData = $request->validate($validationRules);

        // Recalculate the values on the server side to ensure data integrity
        $cfPatient = $validatedData['cf_patient'];
        $totalAdmission = $validatedData['total_admission'];
        $totalTransferIn = $validatedData['total_transfer_in'];
        $totalTransferOut = $validatedData['total_transfer_out'];
        $totalDischarge = $validatedData['total_discharge'];
        $aor = $validatedData['aor'];

        // Calculate total patients using the formula:
        // Total Patients = CF Patients + Total Admissions + Total Transfer In - Total Discharges - Total Transfer Out - AOR (At Own Risk Discharges)
        $totalPatients = $cfPatient + $totalAdmission + $totalTransferIn - $totalDischarge - $totalTransferOut - $aor;

        // Calculate BOR based on Licensed Beds (Licensed Bed BOR)
        // BOR (%) = (Total Patients / Total Licensed Beds Available) * 100
        $licensedBedBor = 0;
        if ($ward->total_licensed_op_beds > 0) {
            $licensedBedBor = ($totalPatients / $ward->total_licensed_op_beds) * 100;
            // Round to exactly 2 decimal places
            $licensedBedBor = round($licensedBedBor, 2);
        }

        // Calculate BOR based on Total Beds (Total Bed BOR)
        // BOR (%) = (Total Patients / Total Beds) * 100
        $totalBedBor = 0;
        if ($ward->total_bed > 0) {
            $totalBedBor = ($totalPatients / $ward->total_bed) * 100;
            // Round to exactly 2 decimal places
            $totalBedBor = round($totalBedBor, 2);
        }

        // Determine which total_patient to use based on the shift
        $total_patient = $totalPatients; // Use our calculated value

        // For validation purposes, check if the submitted values match our calculations
        // Allow a small floating-point difference for BOR values
        if (strpos($shiftName, 'AM') !== false) {
            // Verify the calculated total matches what's in the request
            if (abs($validatedData['total_patient_am'] - $totalPatients) > 0.01) {
                // Log warning about mismatch, but use our calculated value
                error_log("AM total_patient mismatch. Submitted: {$validatedData['total_patient_am']}, Calculated: {$totalPatients}");
            }
            if (abs($validatedData['licensed_bed_bor_am'] - $licensedBedBor) > 0.1) {
                // Log warning about mismatch, but use our calculated value
                error_log("AM licensed_bed_bor mismatch. Submitted: {$validatedData['licensed_bed_bor_am']}, Calculated: {$licensedBedBor}");
            }
        } else if (strpos($shiftName, 'PM') !== false) {
            if (abs($validatedData['total_patient_pm'] - $totalPatients) > 0.01) {
                error_log("PM total_patient mismatch. Submitted: {$validatedData['total_patient_pm']}, Calculated: {$totalPatients}");
            }
            if (abs($validatedData['licensed_bed_bor_pm'] - $licensedBedBor) > 0.1) {
                error_log("PM licensed_bed_bor mismatch. Submitted: {$validatedData['licensed_bed_bor_pm']}, Calculated: {$licensedBedBor}");
            }
        } else if (strpos($shiftName, 'ND') !== false) {
            if (abs($validatedData['total_patient_nd'] - $totalPatients) > 0.01) {
                error_log("ND total_patient mismatch. Submitted: {$validatedData['total_patient_nd']}, Calculated: {$totalPatients}");
            }
            if (abs($validatedData['licensed_bed_bor_nd'] - $licensedBedBor) > 0.1) {
                error_log("ND licensed_bed_bor mismatch. Submitted: {$validatedData['licensed_bed_bor_nd']}, Calculated: {$licensedBedBor}");
            }
        }

        // Create ward entry
        $entry = new WardEntry([
            'user_id' => Auth::id(),
            'ward_id' => $ward->id,
            'shift_id' => $validatedData['shift_id'],
            'cf_patient' => $validatedData['cf_patient'],
            'total_patient' => $total_patient,
            'licensed_bed_bor' => $licensedBedBor,
            'total_bed_bor' => $totalBedBor,
            'total_admission' => $validatedData['total_admission'],
            'total_transfer_in' => $validatedData['total_transfer_in'],
            'total_transfer_out' => $validatedData['total_transfer_out'],
            'total_discharge' => $validatedData['total_discharge'],
            'aor' => $validatedData['aor'],
            'total_staff_on_duty' => $validatedData['total_staff_on_duty'],
            'overtime' => $validatedData['overtime'],
            'total_daily_patients' => isset($validatedData['total_daily_patients']) ? $validatedData['total_daily_patients'] : null,
        ]);

        $entry->save();

        // Calculate CF patient for the next shift
        // CF patient next shift = CF patient + total_admission + total_transfer_in - total_transfer_out - total_discharge - AOR (At Own Risk Discharges)
        $nextShiftCfPatient = $cfPatient + $totalAdmission + $totalTransferIn - $totalTransferOut - $totalDischarge - $aor;

        // Round to nearest integer and ensure it's not negative
        $nextShiftCfPatient = max(0, round($nextShiftCfPatient));

        // Store the calculated CF patient value in the session for the next shift
        session(['next_shift_cf_patient' => $nextShiftCfPatient]);
        session(['next_shift_cf_patient_ward_id' => $ward->id]);
        session(['next_shift_cf_patient_date' => $today]);

        return redirect()->route('dashboard')
            ->with('success', 'Ward entry recorded successfully.');
    }

    /**
     * Show the form for editing a ward entry
     *
     * @param WardEntry $entry
     * @return \Illuminate\View\View
     */
    public function editEntry(WardEntry $entry)
    {
        // Get the ward that belongs to this entry
        $ward = $entry->ward;
        $shifts = Shift::all();

        return view('ward.edit-entry', [
            'entry' => $entry,
            'ward' => $ward,
            'shifts' => $shifts
        ]);
    }

    /**
     * Update the specified ward entry
     *
     * @param Request $request
     * @param WardEntry $entry
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateEntry(Request $request, WardEntry $entry)
    {
        // Get the ward that belongs to this entry
        $ward = $entry->ward;

        // Validate the form input
        $validatedData = $request->validate([
            'cf_patient' => 'required|integer|min:0',
            'total_admission' => 'required|integer|min:0',
            'total_transfer_in' => 'required|integer|min:0',
            'total_transfer_out' => 'required|integer|min:0',
            'total_discharge' => 'required|integer|min:0',
            'aor' => 'required|integer|min:0',
            'total_staff_on_duty' => 'required|integer|min:0',
            'overtime' => 'required|integer|min:0',
            'total_daily_patients' => 'nullable|integer|min:0',
        ]);

        // Extract values for calculation
        $cfPatient = $validatedData['cf_patient'];
        $totalAdmission = $validatedData['total_admission'];
        $totalTransferIn = $validatedData['total_transfer_in'];
        $totalTransferOut = $validatedData['total_transfer_out'];
        $totalDischarge = $validatedData['total_discharge'];
        $aor = $validatedData['aor'];

        // Calculate total patients using the formula:
        // Total Patients = CF Patients + Total Admissions + Total Transfer In - Total Discharges - Total Transfer Out - AOR (At Own Risk Discharges)
        $totalPatients = $cfPatient + $totalAdmission + $totalTransferIn - $totalDischarge - $totalTransferOut - $aor;

        // Ensure total patients is not negative
        $totalPatients = max(0, $totalPatients);

        // Calculate BOR based on Licensed Beds (Licensed Bed BOR)
        // BOR (%) = (Total Patients / Total Licensed Beds Available) * 100
        $licensedBedBor = 0;
        if ($ward->total_licensed_op_beds > 0) {
            $licensedBedBor = ($totalPatients / $ward->total_licensed_op_beds) * 100;
            // Round to exactly 2 decimal places
            $licensedBedBor = round($licensedBedBor, 2);
        }

        // Calculate BOR based on Total Beds (Total Bed BOR)
        // BOR (%) = (Total Patients / Total Beds) * 100
        $totalBedBor = 0;
        if ($ward->total_bed > 0) {
            $totalBedBor = ($totalPatients / $ward->total_bed) * 100;
            // Round to exactly 2 decimal places
            $totalBedBor = round($totalBedBor, 2);
        }

        // Update the entry
        $entry->update([
            'cf_patient' => $validatedData['cf_patient'],
            'total_patient' => $totalPatients,
            'licensed_bed_bor' => $licensedBedBor,
            'total_bed_bor' => $totalBedBor,
            'total_admission' => $validatedData['total_admission'],
            'total_transfer_in' => $validatedData['total_transfer_in'],
            'total_transfer_out' => $validatedData['total_transfer_out'],
            'total_discharge' => $validatedData['total_discharge'],
            'aor' => $validatedData['aor'],
            'total_staff_on_duty' => $validatedData['total_staff_on_duty'],
            'overtime' => $validatedData['overtime'],
            'total_daily_patients' => isset($validatedData['total_daily_patients']) ? $validatedData['total_daily_patients'] : null,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Ward entry updated successfully.');
    }
}
