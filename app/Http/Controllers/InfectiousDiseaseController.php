<?php

namespace App\Http\Controllers;

use App\Models\InfectiousDisease;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InfectiousDiseaseController extends Controller
{
    /**
     * Display a listing of infectious diseases.
     */
    public function index()
    {
        // Get current user
        $user = Auth::user();

        // Check if user is from Emergency Department
        $isEmergencyStaff = session('selected_ward_name') === 'Emergency Department';

        if (!$isEmergencyStaff) {
            return redirect()->route('dashboard')
                ->with('error', 'Only Emergency Department staff can access this feature.');
        }

        // Get all records ordered by most recent
        $infectiousDiseases = InfectiousDisease::with(['ward', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Get all wards for the filter, excluding Emergency Department
        $wards = Ward::where('name', '!=', 'Emergency Department')->get();

        // Get statistics for dashboard
        $statistics = [
            'total_cases' => InfectiousDisease::sum('total'),
            'cases_by_disease' => InfectiousDisease::select('disease', DB::raw('SUM(total) as count'))
                ->groupBy('disease')
                ->orderBy('count', 'desc')
                ->get(),
            'cases_by_patient_type' => InfectiousDisease::select('patient_type', DB::raw('SUM(total) as count'))
                ->groupBy('patient_type')
                ->get()
                ->pluck('count', 'patient_type')
                ->toArray(),
            'cases_by_ward' => InfectiousDisease::select('ward_id', DB::raw('SUM(total) as count'))
                ->groupBy('ward_id')
                ->with('ward')
                ->get()
        ];

        return view('infectious-diseases.index', compact('infectiousDiseases', 'statistics', 'wards'));
    }

    /**
     * Show the form for creating a new infectious disease record.
     */
    public function create()
    {
        // Check if user is from Emergency Department
        $isEmergencyStaff = session('selected_ward_name') === 'Emergency Department';

        if (!$isEmergencyStaff) {
            return redirect()->route('dashboard')
                ->with('error', 'Only Emergency Department staff can access this feature.');
        }

        // Get all disease types
        $diseases = InfectiousDisease::diseaseTypes();

        // Get all patient types
        $patientTypes = InfectiousDisease::patientTypes();

        // Get all wards excluding Emergency Department
        $wards = Ward::where('name', '!=', 'Emergency Department')->get();

        return view('infectious-diseases.create', compact('diseases', 'patientTypes', 'wards'));
    }

    /**
     * Store a newly created infectious disease record in storage.
     */
    public function store(Request $request)
    {
        // Check if user is from Emergency Department
        $isEmergencyStaff = session('selected_ward_name') === 'Emergency Department';

        if (!$isEmergencyStaff) {
            return redirect()->route('dashboard')
                ->with('error', 'Only Emergency Department staff can access this feature.');
        }

        // Validate the request
        $validated = $request->validate([
            'disease' => 'required|string',
            'patient_type' => 'required|in:adult,paed,neonate',
            'ward_id' => 'required|exists:wards,id',
            'total' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        // Add user_id to the validated data
        $validated['user_id'] = Auth::id();

        // Create the record
        InfectiousDisease::create($validated);

        return redirect()->route('infectious-diseases.index')
            ->with('success', 'Infectious disease record created successfully.');
    }

    /**
     * Display the specified infectious disease record.
     */
    public function show(InfectiousDisease $infectiousDisease)
    {
        // Check if user is from Emergency Department
        $isEmergencyStaff = session('selected_ward_name') === 'Emergency Department';

        if (!$isEmergencyStaff) {
            return redirect()->route('dashboard')
                ->with('error', 'Only Emergency Department staff can access this feature.');
        }

        return view('infectious-diseases.show', compact('infectiousDisease'));
    }

    /**
     * Show the form for editing the specified infectious disease record.
     */
    public function edit(InfectiousDisease $infectiousDisease)
    {
        // Check if user is from Emergency Department
        $isEmergencyStaff = session('selected_ward_name') === 'Emergency Department';

        if (!$isEmergencyStaff) {
            return redirect()->route('dashboard')
                ->with('error', 'Only Emergency Department staff can access this feature.');
        }

        // Get all disease types
        $diseases = InfectiousDisease::diseaseTypes();

        // Get all patient types
        $patientTypes = InfectiousDisease::patientTypes();

        // Get all wards excluding Emergency Department
        $wards = Ward::where('name', '!=', 'Emergency Department')->get();

        return view('infectious-diseases.edit', compact('infectiousDisease', 'diseases', 'patientTypes', 'wards'));
    }

    /**
     * Update the specified infectious disease record in storage.
     */
    public function update(Request $request, InfectiousDisease $infectiousDisease)
    {
        // Check if user is from Emergency Department
        $isEmergencyStaff = session('selected_ward_name') === 'Emergency Department';

        if (!$isEmergencyStaff) {
            return redirect()->route('dashboard')
                ->with('error', 'Only Emergency Department staff can access this feature.');
        }

        // Validate the request
        $validated = $request->validate([
            'disease' => 'required|string',
            'patient_type' => 'required|in:adult,paed,neonate',
            'ward_id' => 'required|exists:wards,id',
            'total' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        // Update the record
        $infectiousDisease->update($validated);

        return redirect()->route('infectious-diseases.index')
            ->with('success', 'Infectious disease record updated successfully.');
    }

    /**
     * Remove the specified infectious disease record from storage.
     */
    public function destroy(InfectiousDisease $infectiousDisease)
    {
        // Check if user is admin
        if (Auth::user()->username !== 'admin') {
            return redirect()->route('infectious-diseases.index')
                ->with('error', 'Only administrators can delete records.');
        }

        // Delete the record
        $infectiousDisease->delete();

        return redirect()->route('infectious-diseases.index')
            ->with('success', 'Infectious disease record deleted successfully.');
    }

    /**
     * Display reports for infectious diseases.
     */
    public function report(Request $request)
    {
        // Check if user is from Emergency Department
        $isEmergencyStaff = session('selected_ward_name') === 'Emergency Department';

        if (!$isEmergencyStaff) {
            return redirect()->route('dashboard')
                ->with('error', 'Only Emergency Department staff can access this feature.');
        }

        // Get all wards for the filter
        $wards = Ward::where('name', '!=', 'Emergency Department')->get();

        // Get date range from request or default to last 30 days
        $dateFrom = $request->input('date_from')
            ? date('Y-m-d', strtotime($request->input('date_from')))
            : now()->subDays(30)->format('Y-m-d');

        $dateTo = $request->input('date_to')
            ? date('Y-m-d', strtotime($request->input('date_to')))
            : now()->format('Y-m-d');

        // Build the base query with date filters
        $query = InfectiousDisease::query()
            ->whereBetween(DB::raw('DATE(created_at)'), [$dateFrom, $dateTo]);

        // Get detailed report (individual records)
        $detailedReport = (clone $query)
            ->with(['ward', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Generate disease report summary
        $diseaseReport = [];
        $patientTypes = InfectiousDisease::patientTypes();

        foreach (InfectiousDisease::diseaseTypes() as $disease) {
            $diseaseData = (clone $query)
                ->where('disease', $disease)
                ->select('patient_type', DB::raw('SUM(total) as total'))
                ->groupBy('patient_type')
                ->get()
                ->pluck('total', 'patient_type')
                ->toArray();

            // Skip if no data for this disease in the date range
            if (empty($diseaseData)) {
                continue;
            }

            $totalForDisease = array_sum($diseaseData);

            if ($totalForDisease > 0) {
                $diseaseReport[$disease] = array_merge($diseaseData, [
                    'total' => $totalForDisease
                ]);
            }
        }

        // Generate ward report summary
        $wardReport = [];

        foreach ($wards as $ward) {
            $wardData = (clone $query)
                ->where('ward_id', $ward->id)
                ->select('patient_type', DB::raw('SUM(total) as total'))
                ->groupBy('patient_type')
                ->get()
                ->pluck('total', 'patient_type')
                ->toArray();

            // Skip if no data for this ward in the date range
            if (empty($wardData)) {
                continue;
            }

            $totalForWard = array_sum($wardData);

            if ($totalForWard > 0) {
                $wardReport[$ward->name] = array_merge($wardData, [
                    'total' => $totalForWard
                ]);
            }
        }

        // Calculate totals for each patient type and overall total
        $totals = [
            'adult' => (clone $query)->where('patient_type', 'adult')->sum('total'),
            'paed' => (clone $query)->where('patient_type', 'paed')->sum('total'),
            'neonate' => (clone $query)->where('patient_type', 'neonate')->sum('total'),
        ];
        $totals['total'] = $totals['adult'] + $totals['paed'] + $totals['neonate'];

        return view('infectious-diseases.report', compact(
            'diseaseReport',
            'wardReport',
            'detailedReport',
            'totals',
            'wards',
            'dateFrom',
            'dateTo'
        ));
    }
}
