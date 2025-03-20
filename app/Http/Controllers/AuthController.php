<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('ward.select');
        }

        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        Auth::login($user);

        return redirect()->route('ward.select');
    }

    /**
     * Show ward selection form
     */
    public function showWardSelection()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $userWards = $user->wards->pluck('id')->toArray(); // Get array of IDs of wards assigned to user
        $wards = Ward::orderBy('name')->get(); // Get all wards

        return view('auth.select-ward', compact('wards', 'userWards'));
    }

    /**
     * Set selected ward in session
     */
    public function selectWard(Request $request)
    {
        $request->validate([
            'ward_id' => 'required|exists:wards,id',
        ]);

        $user = Auth::user();
        $ward = Ward::findOrFail($request->ward_id);

        // Check if user has access to this ward
        if (!$user->wards->contains($ward->id)) {
            return back()->with('error', 'You do not have access to this ward. Please contact the administrator if you need access.');
        }

        // Store selected ward in session
        session(['selected_ward_id' => $ward->id]);
        session(['selected_ward_name' => $ward->name]);

        return redirect()->route('dashboard');
    }

    /**
     * Logout the user
     */
    public function logout()
    {
        Auth::logout();
        session()->forget(['selected_ward_id', 'selected_ward_name']);
        session()->invalidate();
        session()->regenerateToken();

        return redirect()->route('login');
    }
}
