<?php

namespace App\Http\Controllers;

use App\Models\DailyData;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DailyDataController extends Controller
{
    /**
     * Display a listing of the daily data entries.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $ward = session('selected_ward');

        // Simple debugging information - just return for troubleshooting if needed
        if ($request->has('debug') && $user->isAdmin()) {
            $debug = [
                'user_id' => $user->id,
                'username' => $user->username,
                'is_admin' => $user->isAdmin(),
                'ward' => $ward ? $ward->toArray() : null,
                'session_ward_id' => session('selected_ward_id'),
                'route_middleware' => app()->make('router')->getRoutes()->match($request)->middleware()
            ];
            return response()->json($debug);
        }

        // If the user is an admin, they can view all wards data
        if ($user->isAdmin()) {
            // Get data for all wards if no specific ward is selected
            if (!$ward && $request->has('ward_id')) {
                $ward = Ward::find($request->ward_id);
            }

            // If no ward is specified or selected, show data from all wards
            if (!$ward) {
                $dailyData = DailyData::with(['user', 'ward'])
                    ->latest('date')
                    ->paginate(10);

                $wards = Ward::orderBy('name')->get();

                // Get all date entries for the date filter dropdown
                $availableDates = DailyData::orderBy('date', 'desc')
                    ->pluck('date')
                    ->map(function ($date) {
                        return Carbon::parse($date)->format('Y-m-d');
                    })
                    ->unique()
                    ->values()
                    ->toArray();

                return view('daily-data.index', compact('dailyData', 'ward', 'wards', 'availableDates'));
            }
        } else {
            // Regular users must have a ward selected
            if (!$ward) {
                // Get the user's assigned wards
                $userWards = $user->wards;

                // If user has only one ward, use it
                if ($userWards->count() == 1) {
                    $ward = $userWards->first();
                    // Store the ward in session
                    session(['selected_ward' => $ward]);
                    session(['selected_ward_id' => $ward->id]);
                    session(['selected_ward_name' => $ward->name]);
                } else {
                    // User has multiple wards but none selected, redirect to ward selection
                    return redirect()->route('ward.select')->with('error', 'Please select a ward first');
                }
            }
        }

        $selectedDate = $request->date ? Carbon::parse($request->date) : Carbon::today();

        // If we have a specific ward (selected or from request), get data for that ward
        $dailyData = DailyData::with('user')
            ->where('ward_id', $ward->id)
            ->latest('date')
            ->paginate(10);

        // Get all date entries for this ward for the date filter dropdown
        $availableDates = DailyData::where('ward_id', $ward->id)
            ->orderBy('date', 'desc')
            ->pluck('date')
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })
            ->unique()
            ->values()
            ->toArray();

        return view('daily-data.index', compact('dailyData', 'ward', 'selectedDate', 'availableDates'));
    }

    /**
     * Show the form for creating a new daily data entry.
     */
    public function create()
    {
        $user = Auth::user();
        $ward = session('selected_ward');
        $today = Carbon::today();
        $wards = collect(); // Initialize the variable for all users

        if ($user->isAdmin()) {
            // Admin users can create entries for any ward
            if (!$ward) {
                $wards = Ward::orderBy('name')->get();
                return view('daily-data.admin-create', compact('wards', 'today'));
            }
        } else {
            // Regular users must have a ward selected
            if (!$ward) {
                // Get the user's assigned wards
                $userWards = $user->wards;

                // If user has only one ward, use it
                if ($userWards->count() == 1) {
                    $ward = $userWards->first();
                    // Store the ward in session
                    session(['selected_ward' => $ward]);
                    session(['selected_ward_id' => $ward->id]);
                    session(['selected_ward_name' => $ward->name]);
                } else {
                    return redirect()->route('ward.select')->with('error', 'Please select a ward first');
                }
            }
        }

        // Check if entry already exists for today
        $existingEntry = DailyData::where('ward_id', $ward->id)
            ->whereDate('date', $today)
            ->first();

        if ($existingEntry) {
            return redirect()->route('daily-data.edit', $existingEntry->id)
                ->with('info', 'An entry for today already exists. You can edit it here.');
        }

        return view('daily-data.create', compact('ward', 'today', 'wards'));
    }

    /**
     * Store a newly created daily data entry.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $ward = session('selected_ward');

        // Prepare validation rules
        $validationRules = [
            'date' => 'required|date',
            'death' => 'required|integer|min:0',
            'neonatal_jaundice' => 'required|integer|min:0',
            'bedridden_case' => 'required|integer|min:0',
            'incident_report' => 'required|integer|min:0',
            'remarks' => 'nullable|string',
        ];

        // Admin users must provide a ward_id when not having a selected_ward
        if (($user->isAdmin() && !$ward) || !$ward) {
            $validationRules['ward_id'] = 'required|exists:wards,id';
        }

        $validatedData = $request->validate($validationRules);

        // Determine the ward_id to use
        if (isset($validatedData['ward_id'])) {
            $wardId = $validatedData['ward_id'];
        } elseif ($ward) {
            $wardId = $ward->id;
        } else {
            return redirect()->back()->with('error', 'Please select a ward');
        }

        // Check if an entry already exists for this date and ward
        $existingEntry = DailyData::where('ward_id', $wardId)
            ->whereDate('date', $validatedData['date'])
            ->first();

        if ($existingEntry) {
            return redirect()->route('daily-data.edit', $existingEntry->id)
                ->with('error', 'An entry for this date already exists. You can edit it here.');
        }

        // Add ward_id and user_id to the validated data
        $validatedData['ward_id'] = $wardId;
        $validatedData['user_id'] = Auth::id();

        // Create a new daily data entry
        $dailyData = DailyData::create($validatedData);

        return redirect()->route('daily-data.index')
            ->with('success', 'Daily data entry created successfully');
    }

    /**
     * Display the specified daily data entry.
     */
    public function show(string $id)
    {
        $dailyData = DailyData::with(['ward', 'user'])->findOrFail($id);
        $user = Auth::user();
        $ward = $dailyData->ward;  // Explicitly set the ward variable from the daily data entry

        // Check if the user has access to this ward's data
        if (!$user->isAdmin() &&
            (!session()->has('selected_ward') || $dailyData->ward_id != session('selected_ward')->id)) {

            // If there's no selected ward in session, store this ward
            if (!session()->has('selected_ward') && $user->wards->contains('id', $dailyData->ward_id)) {
                session(['selected_ward' => $ward]);
                session(['selected_ward_id' => $ward->id]);
                session(['selected_ward_name' => $ward->name]);
            } else {
                return redirect()->route('ward.access.denied');
            }
        }

        return view('daily-data.show', compact('dailyData', 'ward'));
    }

    /**
     * Show the form for editing the specified daily data entry.
     */
    public function edit(string $id)
    {
        $dailyData = DailyData::findOrFail($id);
        $user = Auth::user();

        // Check if the user has access to this ward's data
        if (!$user->isAdmin() &&
            (!session()->has('selected_ward') || $dailyData->ward_id != session('selected_ward')->id)) {

            // If there's no selected ward in session, store this ward
            if (!session()->has('selected_ward') && $user->wards->contains('id', $dailyData->ward_id)) {
                $ward = Ward::find($dailyData->ward_id);
                session(['selected_ward' => $ward]);
                session(['selected_ward_id' => $ward->id]);
                session(['selected_ward_name' => $ward->name]);
            } else {
                return redirect()->route('ward.access.denied');
            }
        }

        $ward = $dailyData->ward;

        return view('daily-data.edit', compact('dailyData', 'ward'));
    }

    /**
     * Update the specified daily data entry.
     */
    public function update(Request $request, string $id)
    {
        $dailyData = DailyData::findOrFail($id);
        $user = Auth::user();

        // Check if the user has access to this ward's data
        if (!$user->isAdmin() &&
            (!session()->has('selected_ward') || $dailyData->ward_id != session('selected_ward')->id)) {

            // If there's no selected ward in session, store this ward
            if (!session()->has('selected_ward') && $user->wards->contains('id', $dailyData->ward_id)) {
                $ward = Ward::find($dailyData->ward_id);
                session(['selected_ward' => $ward]);
                session(['selected_ward_id' => $ward->id]);
                session(['selected_ward_name' => $ward->name]);
            } else {
                return redirect()->route('ward.access.denied');
            }
        }

        $validatedData = $request->validate([
            'death' => 'required|integer|min:0',
            'neonatal_jaundice' => 'required|integer|min:0',
            'bedridden_case' => 'required|integer|min:0',
            'incident_report' => 'required|integer|min:0',
            'remarks' => 'nullable|string',
        ]);

        // Update the daily data entry
        $dailyData->update($validatedData);

        return redirect()->route('daily-data.index')
            ->with('success', 'Daily data entry updated successfully');
    }

    /**
     * Remove the specified daily data entry from storage.
     */
    public function destroy(string $id)
    {
        $dailyData = DailyData::findOrFail($id);
        $user = Auth::user();

        // Check if the user is admin
        if (!$user->isAdmin()) {
            return redirect()->route('admin.access.denied');
        }

        // Delete the daily data entry
        $dailyData->delete();

        return redirect()->route('daily-data.index')
            ->with('success', 'Daily data entry deleted successfully');
    }
}
