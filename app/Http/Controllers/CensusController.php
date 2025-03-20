<?php

namespace App\Http\Controllers;

use App\Models\Ward;
use App\Models\CensusEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CensusController extends Controller
{
    /**
     * Show the form for creating a new census entry
     */
    public function create()
    {
        // Check if a ward is selected
        $wardId = session('selected_ward_id');
        if (!$wardId) {
            return redirect()->route('ward.select')
                ->with('error', 'Please select a ward first.');
        }

        $ward = Ward::findOrFail($wardId);

        // Check if user has access to this ward
        if (!Auth::user()->wards->contains($ward->id)) {
            return redirect()->route('ward.select')
                ->with('error', 'You do not have access to this ward.');
        }

        // Check if there's an existing census entry for today
        $today = now()->format('Y-m-d');
        $existingEntry = CensusEntry::where('ward_id', $wardId)
            ->whereDate('created_at', $today)
            ->first();

        // If there's an existing entry, use it to pre-populate the form
        return view('census.create', [
            'ward' => $ward,
            'existingEntry' => $existingEntry
        ]);
    }

    /**
     * Store a new census entry
     */
    public function store(Request $request)
    {
        // Check if a ward is selected
        $wardId = session('selected_ward_id');
        if (!$wardId) {
            return redirect()->route('ward.select')
                ->with('error', 'Please select a ward first.');
        }

        $ward = Ward::findOrFail($wardId);

        // Check if user has access to this ward
        if (!Auth::user()->wards->contains($ward->id)) {
            return redirect()->route('ward.select')
                ->with('error', 'You do not have access to this ward.');
        }

        // Validate the input
        $validatedData = $request->validate([
            'hours24_census' => 'required|integer|min:0',
            'cf_patient_2400' => 'required|integer|min:0',
        ]);

        // Calculate bed occupancy rate based on licensed beds
        // BOR (%) = (24h Census / Total Licensed Beds) * 100
        $bedOccupancyRate = 0;
        if ($ward->total_licensed_op_beds > 0) {
            $bedOccupancyRate = ($validatedData['hours24_census'] / $ward->total_licensed_op_beds) * 100;
            // Cap BOR at 999.99 to prevent numeric overflow in database
            $bedOccupancyRate = min($bedOccupancyRate, 999.99);
            // Round to exactly 2 decimal places
            $bedOccupancyRate = round($bedOccupancyRate, 2);
        }

        // Check if there's an existing entry for today
        $today = now()->format('Y-m-d');
        $existingEntry = CensusEntry::where('ward_id', $wardId)
            ->whereDate('created_at', $today)
            ->first();

        if ($existingEntry) {
            // Update existing entry
            $existingEntry->update([
                'hours24_census' => $validatedData['hours24_census'],
                'cf_patient_2400' => $validatedData['cf_patient_2400'],
                'bed_occupancy_rate' => $bedOccupancyRate
            ]);

            $message = 'Census entry updated successfully.';
        } else {
            // Create new entry
            CensusEntry::create([
                'ward_id' => $wardId,
                'hours24_census' => $validatedData['hours24_census'],
                'cf_patient_2400' => $validatedData['cf_patient_2400'],
                'bed_occupancy_rate' => $bedOccupancyRate
            ]);

            $message = 'Census entry created successfully.';
        }

        return redirect()->route('dashboard')
            ->with('success', $message);
    }

    /**
     * Show the form for editing a census entry - admin only function
     */
    public function edit($id)
    {
        $entry = CensusEntry::findOrFail($id);
        $ward = $entry->ward;

        return view('census.edit', [
            'entry' => $entry,
            'ward' => $ward
        ]);
    }

    /**
     * Update the specified census entry - admin only function
     */
    public function update(Request $request, $id)
    {
        $entry = CensusEntry::findOrFail($id);
        $ward = $entry->ward;

        // Validate the input
        $validatedData = $request->validate([
            'hours24_census' => 'required|integer|min:0',
            'cf_patient_2400' => 'required|integer|min:0',
        ]);

        // Calculate bed occupancy rate
        $bedOccupancyRate = 0;
        if ($ward->total_licensed_op_beds > 0) {
            $bedOccupancyRate = ($validatedData['hours24_census'] / $ward->total_licensed_op_beds) * 100;
            // Cap BOR at 999.99 to prevent numeric overflow in database
            $bedOccupancyRate = min($bedOccupancyRate, 999.99);
            // Round to exactly 2 decimal places
            $bedOccupancyRate = round($bedOccupancyRate, 2);
        }

        // Update the entry
        $entry->update([
            'hours24_census' => $validatedData['hours24_census'],
            'cf_patient_2400' => $validatedData['cf_patient_2400'],
            'bed_occupancy_rate' => $bedOccupancyRate
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Census entry updated successfully.');
    }
}
