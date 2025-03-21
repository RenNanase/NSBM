<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeliveryController extends Controller
{
    /**
     * Constructor to apply middleware
     */
    public function __construct()
    {
        // Maternity ward access middleware will be registered in the routes
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the selected ward
        $wardId = session('selected_ward_id');

        if (!$wardId) {
            return redirect()->route('ward.select')
                ->with('error', 'Please select a ward first.');
        }

        $ward = Ward::findOrFail($wardId);

        // Get deliveries for this ward, newest first
        $deliveries = Delivery::where('ward_id', $wardId)
            ->orderBy('report_date', 'desc')
            ->paginate(10);

        return view('deliveries.index', compact('deliveries', 'ward'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get the selected ward
        $wardId = session('selected_ward_id');

        if (!$wardId) {
            return redirect()->route('ward.select')
                ->with('error', 'Please select a ward first.');
        }

        $ward = Ward::findOrFail($wardId);

        // Check if entry already exists for today
        $today = now()->format('Y-m-d');
        $existingEntry = Delivery::where('ward_id', $wardId)
            ->where('report_date', $today)
            ->first();

        if ($existingEntry) {
            return redirect()->route('delivery.edit', $existingEntry)
                ->with('info', 'A delivery record already exists for today. You can edit it instead.');
        }

        return view('deliveries.create', compact('ward'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Get the selected ward
        $wardId = session('selected_ward_id');

        if (!$wardId) {
            return redirect()->route('ward.select')
                ->with('error', 'Please select a ward first.');
        }

        $ward = Ward::findOrFail($wardId);

        $validatedData = $request->validate([
            'report_date' => 'required|date',
            'svd' => 'required|integer|min:0',
            'lscs' => 'required|integer|min:0',
            'vacuum' => 'required|integer|min:0',
            'forceps' => 'required|integer|min:0',
            'breech' => 'required|integer|min:0',
            'eclampsia' => 'required|integer|min:0',
            'twin' => 'required|integer|min:0',
            'mrp' => 'required|integer|min:0',
            'fsb_mbs' => 'required|integer|min:0',
            'bba' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check for duplicate entry
        $existingEntry = Delivery::where('ward_id', $wardId)
            ->where('report_date', $validatedData['report_date'])
            ->first();

        if ($existingEntry) {
            return redirect()->route('delivery.edit', $existingEntry)
                ->with('error', 'A delivery record already exists for this date. You can edit it instead.');
        }

        // Add ward_id and user_id to data
        $validatedData['ward_id'] = $wardId;
        $validatedData['user_id'] = Auth::id();

        // Calculate total (though it will also be calculated by the model)
        $validatedData['total'] =
            $validatedData['svd'] +
            $validatedData['lscs'] +
            $validatedData['vacuum'] +
            $validatedData['forceps'] +
            $validatedData['breech'] +
            $validatedData['eclampsia'] +
            $validatedData['twin'] +
            $validatedData['mrp'] +
            $validatedData['fsb_mbs'] +
            $validatedData['bba'];

        // Create the delivery record
        Delivery::create($validatedData);

        return redirect()->route('delivery.index')
            ->with('success', 'Delivery data has been recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Delivery $delivery)
    {
        $ward = $delivery->ward;
        return view('deliveries.show', compact('delivery', 'ward'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Delivery $delivery)
    {
        // Get the selected ward
        $wardId = session('selected_ward_id');

        if (!$wardId || $wardId !== $delivery->ward_id) {
            return redirect()->route('ward.select')
                ->with('error', 'Please select the correct ward first.');
        }

        $ward = Ward::findOrFail($wardId);

        return view('deliveries.edit', compact('delivery', 'ward'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Delivery $delivery)
    {
        // Ensure user can only update their selected ward's data
        $wardId = session('selected_ward_id');

        if (!$wardId || $wardId !== $delivery->ward_id) {
            return redirect()->route('ward.select')
                ->with('error', 'Please select the correct ward first.');
        }

        $ward = Ward::findOrFail($wardId);

        $validatedData = $request->validate([
            'report_date' => 'required|date',
            'svd' => 'required|integer|min:0',
            'lscs' => 'required|integer|min:0',
            'vacuum' => 'required|integer|min:0',
            'forceps' => 'required|integer|min:0',
            'breech' => 'required|integer|min:0',
            'eclampsia' => 'required|integer|min:0',
            'twin' => 'required|integer|min:0',
            'mrp' => 'required|integer|min:0',
            'fsb_mbs' => 'required|integer|min:0',
            'bba' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check if date changed and if new date already has an entry
        if ($validatedData['report_date'] != $delivery->report_date) {
            $existingEntry = Delivery::where('ward_id', $wardId)
                ->where('report_date', $validatedData['report_date'])
                ->where('id', '!=', $delivery->id)
                ->first();

            if ($existingEntry) {
                return back()->withInput()
                    ->with('error', 'A delivery record already exists for the selected date.');
            }
        }

        // Update the delivery record
        $delivery->update($validatedData);

        return redirect()->route('delivery.index')
            ->with('success', 'Delivery data has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Delivery $delivery)
    {
        // Ensure user can only delete their selected ward's data
        $wardId = session('selected_ward_id');

        if (!$wardId || $wardId !== $delivery->ward_id) {
            return redirect()->route('ward.select')
                ->with('error', 'Please select the correct ward first.');
        }

        // Only allow admins to delete records
        if (Auth::user()->username !== 'admin') {
            return back()->with('error', 'You do not have permission to delete delivery records.');
        }

        $delivery->delete();

        return redirect()->route('delivery.index')
            ->with('success', 'Delivery record has been deleted successfully.');
    }
}
