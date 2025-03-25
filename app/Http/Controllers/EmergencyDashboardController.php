<?php

namespace App\Http\Controllers;

use App\Models\Ward;
use App\Models\Shift;
use App\Models\WardEntry;
use App\Models\InfectiousDisease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmergencyDashboardController extends Controller
{
    /**
     * Display the dashboard for the Emergency Department
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

        // Confirm this is the Emergency Department
        if ($ward->name !== 'Emergency Department') {
            return redirect()->route('dashboard');
        }

        // Get today's date
        $today = now()->format('Y-m-d');

        // Get recent entries for this ward (only for today)
        $recentEntries = WardEntry::where('ward_id', $ward->id)
            ->whereDate('created_at', $today)
            ->with(['shift', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get current BOR data
        $currentBOR = $recentEntries->isEmpty() ? null : $recentEntries->first()->total_bed_bor;

        // Get real infectious disease data
        $infectiousDiseaseData = $this->getInfectiousDiseaseData();

        return view('emergency.dashboard', compact(
            'ward',
            'recentEntries',
            'today',
            'currentBOR',
            'infectiousDiseaseData'
        ));
    }

    /**
     * Get infectious disease statistics for the dashboard
     *
     * @return array
     */
    private function getInfectiousDiseaseData()
    {
        // Get total count of diseases by category
        $respiratoryDiseases = ['Covid 19', 'Influenza A', 'Influenza B', 'TB', 'RSV'];
        $contactDiseases = ['Chicken Pox', 'Rota Virus'];
        $dropletDiseases = ['Measles'];
        $airborneDiseases = ['Typhoid', 'Cholera', 'Dengue'];

        // Calculate totals by category (consider data from the last 30 days)
        $thirtyDaysAgo = now()->subDays(30)->format('Y-m-d');

        $respiratory = InfectiousDisease::whereIn('disease', $respiratoryDiseases)
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('total');

        $contact = InfectiousDisease::whereIn('disease', $contactDiseases)
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('total');

        $droplet = InfectiousDisease::whereIn('disease', $dropletDiseases)
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('total');

        $airborne = InfectiousDisease::whereIn('disease', $airborneDiseases)
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->sum('total');

        return [
            'respiratory' => $respiratory,
            'contact' => $contact,
            'droplet' => $droplet,
            'airborne' => $airborne
        ];
    }
}
