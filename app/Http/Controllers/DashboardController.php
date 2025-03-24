<?php

namespace App\Http\Controllers;

use App\Models\Ward;
use App\Models\Shift;
use App\Models\WardEntry;
use App\Models\CensusEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dashboard for a selected ward
     */
    public function index()
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

        // Get recent entries for this ward
        $recentEntries = WardEntry::where('ward_id', $ward->id)
            ->with(['shift', 'user'])
            ->orderBy('created_at', 'desc')
            ->take(10) // Increased to make sure we get all today's entries
            ->get();

        // Get latest census data
        $censusEntry = CensusEntry::where('ward_id', $ward->id)
            ->orderBy('created_at', 'desc')
            ->first();

        // Get today's date
        $today = now()->format('Y-m-d');

        // Find shifts that have been filled today
        $filledShifts = WardEntry::where('ward_id', $ward->id)
            ->whereDate('created_at', $today)
            ->pluck('shift_id')
            ->toArray();

        // Get shift information for morning, evening, and night
        $morningShift = WardEntry::where('ward_id', $ward->id)
            ->whereHas('shift', function($query) {
                $query->where('name', 'AM SHIFT');
            })
            ->whereDate('created_at', $today)
            ->first();

        $eveningShift = WardEntry::where('ward_id', $ward->id)
            ->whereHas('shift', function($query) {
                $query->where('name', 'PM SHIFT');
            })
            ->whereDate('created_at', $today)
            ->first();

        $nightShift = WardEntry::where('ward_id', $ward->id)
            ->whereHas('shift', function($query) {
                $query->where('name', 'ND SHIFT');
            })
            ->whereDate('created_at', $today)
            ->first();

        // Set patients to an empty collection as the Patient model may not exist
        $patients = collect();

        return view('dashboard', compact(
            'ward',
            'shifts',
            'recentEntries',
            'censusEntry',
            'filledShifts',
            'today',
            'morningShift',
            'eveningShift',
            'nightShift',
            'patients'
        ));
    }
}
