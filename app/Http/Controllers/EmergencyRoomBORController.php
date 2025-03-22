<?php

namespace App\Http\Controllers;

use App\Models\EmergencyRoomBOR;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EmergencyRoomBORController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // If a date filter is provided, use it; otherwise default to today
        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        $borEntries = EmergencyRoomBOR::where('date', $selectedDate)
            ->orderBy('shift')
            ->get();

        // Calculate 24 Hours Census (sum of all patients for the day)
        $dailyTotals = [
            'green' => $borEntries->sum('green'),
            'yellow' => $borEntries->sum('yellow'),
            'red' => $borEntries->sum('red'),
            'grand_total' => $borEntries->sum('grand_total'),
            'ambulance_call' => $borEntries->sum('ambulance_call'),
            'admission' => $borEntries->sum('admission'),
            'transfer' => $borEntries->sum('transfer'),
            'death' => $borEntries->sum('death'),
        ];

        return view('emergency.bor.index', [
            'borEntries' => $borEntries,
            'dailyTotals' => $dailyTotals,
            'selectedDate' => $selectedDate,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $shifts = ['0700-1400', '1400-2100', '2100-0700'];
        $today = Carbon::today();

        // Check if any entries already exist for today
        $existingShifts = EmergencyRoomBOR::where('date', $today)
            ->pluck('shift')
            ->toArray();

        // Get only shifts that haven't been entered yet
        $availableShifts = array_diff($shifts, $existingShifts);

        return view('emergency.bor.create', [
            'availableShifts' => $availableShifts,
            'today' => $today,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'shift' => 'required|in:0700-1400,1400-2100,2100-0700',
            'green' => 'required|integer|min:0',
            'yellow' => 'required|integer|min:0',
            'red' => 'required|integer|min:0',
            'ambulance_call' => 'required|integer|min:0',
            'admission' => 'required|integer|min:0',
            'transfer' => 'required|integer|min:0',
            'death' => 'required|integer|min:0',
            'remarks' => 'nullable|string',
        ]);

        // Check if entry for this shift and date already exists
        $existingEntry = EmergencyRoomBOR::where('date', $validated['date'])
            ->where('shift', $validated['shift'])
            ->first();

        if ($existingEntry) {
            return redirect()->back()->with('error', 'An entry for this shift and date already exists.');
        }

        // Calculate grand total
        $grandTotal = $validated['green'] + $validated['yellow'] + $validated['red'];

        // Create the BOR entry
        $borEntry = new EmergencyRoomBOR();
        $borEntry->date = $validated['date'];
        $borEntry->shift = $validated['shift'];
        $borEntry->green = $validated['green'];
        $borEntry->yellow = $validated['yellow'];
        $borEntry->red = $validated['red'];
        $borEntry->grand_total = $grandTotal;
        $borEntry->ambulance_call = $validated['ambulance_call'];
        $borEntry->admission = $validated['admission'];
        $borEntry->transfer = $validated['transfer'];
        $borEntry->death = $validated['death'];
        $borEntry->remarks = $validated['remarks'];
        $borEntry->user_id = Auth::id();

        // Update 24 hours census
        // Get all entries for this date
        $dateEntries = EmergencyRoomBOR::where('date', $validated['date'])->get();
        $hours24Census = $dateEntries->sum('grand_total') + $grandTotal;
        $borEntry->hours_24_census = $hours24Census;

        $borEntry->save();

        // Update the 24 hours census for all entries of this date
        foreach ($dateEntries as $entry) {
            $entry->hours_24_census = $hours24Census;
            $entry->save();
        }

        return redirect()->route('emergency.bor.index')
            ->with('success', 'Emergency Room BOR data successfully recorded.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $borEntry = EmergencyRoomBOR::findOrFail($id);
        return view('emergency.bor.show', ['borEntry' => $borEntry]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $borEntry = EmergencyRoomBOR::findOrFail($id);
        return view('emergency.bor.edit', ['borEntry' => $borEntry]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'green' => 'required|integer|min:0',
            'yellow' => 'required|integer|min:0',
            'red' => 'required|integer|min:0',
            'ambulance_call' => 'required|integer|min:0',
            'admission' => 'required|integer|min:0',
            'transfer' => 'required|integer|min:0',
            'death' => 'required|integer|min:0',
            'remarks' => 'nullable|string',
        ]);

        $borEntry = EmergencyRoomBOR::findOrFail($id);

        // Calculate grand total
        $grandTotal = $validated['green'] + $validated['yellow'] + $validated['red'];

        // Update the BOR entry
        $borEntry->green = $validated['green'];
        $borEntry->yellow = $validated['yellow'];
        $borEntry->red = $validated['red'];
        $borEntry->grand_total = $grandTotal;
        $borEntry->ambulance_call = $validated['ambulance_call'];
        $borEntry->admission = $validated['admission'];
        $borEntry->transfer = $validated['transfer'];
        $borEntry->death = $validated['death'];
        $borEntry->remarks = $validated['remarks'];
        $borEntry->save();

        // Update 24 hours census for all entries of this date
        $dateEntries = EmergencyRoomBOR::where('date', $borEntry->date)->get();
        $hours24Census = $dateEntries->sum('grand_total');

        foreach ($dateEntries as $entry) {
            $entry->hours_24_census = $hours24Census;
            $entry->save();
        }

        return redirect()->route('emergency.bor.index')
            ->with('success', 'Emergency Room BOR data successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $borEntry = EmergencyRoomBOR::findOrFail($id);
        $date = $borEntry->date;
        $borEntry->delete();

        // Update 24 hours census for all entries of this date
        $dateEntries = EmergencyRoomBOR::where('date', $date)->get();
        $hours24Census = $dateEntries->sum('grand_total');

        foreach ($dateEntries as $entry) {
            $entry->hours_24_census = $hours24Census;
            $entry->save();
        }

        return redirect()->route('emergency.bor.index')
            ->with('success', 'Emergency Room BOR entry successfully deleted.');
    }

    /**
     * Display historical BOR data.
     */
    public function history(Request $request)
    {
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));

        $borEntries = EmergencyRoomBOR::where('date', $date)
            ->orderBy('shift')
            ->get();

        // Calculate 24 Hours Census (sum of all patients for the day)
        $dailyTotals = [
            'green' => $borEntries->sum('green'),
            'yellow' => $borEntries->sum('yellow'),
            'red' => $borEntries->sum('red'),
            'grand_total' => $borEntries->sum('grand_total'),
            'ambulance_call' => $borEntries->sum('ambulance_call'),
            'admission' => $borEntries->sum('admission'),
            'transfer' => $borEntries->sum('transfer'),
            'death' => $borEntries->sum('death'),
        ];

        return view('emergency.bor.history', [
            'borEntries' => $borEntries,
            'dailyTotals' => $dailyTotals,
            'selectedDate' => $date,
        ]);
    }

    /**
     * Display monthly report of BOR data.
     */
    public function report(Request $request)
    {
        $month = $request->input('month', Carbon::now()->format('Y-m'));
        list($year, $monthNum) = explode('-', $month);

        $startDate = Carbon::createFromDate($year, $monthNum, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $monthNum, 1)->endOfMonth();

        // Get all entries for the selected month
        $entries = EmergencyRoomBOR::whereBetween('date', [$startDate, $endDate])
            ->orderBy('date')
            ->orderBy('shift')
            ->get();

        // Group entries by date
        $entriesByDate = $entries->groupBy('date');

        // Calculate monthly totals
        $monthlyTotals = [
            'green' => $entries->sum('green'),
            'yellow' => $entries->sum('yellow'),
            'red' => $entries->sum('red'),
            'grand_total' => $entries->sum('grand_total'),
            'ambulance_call' => $entries->sum('ambulance_call'),
            'admission' => $entries->sum('admission'),
            'transfer' => $entries->sum('transfer'),
            'death' => $entries->sum('death'),
        ];

        return view('emergency.bor.report', [
            'entriesByDate' => $entriesByDate,
            'monthlyTotals' => $monthlyTotals,
            'selectedMonth' => $month,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }
}
