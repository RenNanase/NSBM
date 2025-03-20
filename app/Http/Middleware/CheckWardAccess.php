<?php

namespace App\Http\Middleware;

use App\Models\Ward;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckWardAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if a ward is selected
        $wardId = session('selected_ward_id');
        if (!$wardId) {
            return redirect()->route('ward.select')
                ->with('error', 'Please select a ward first.');
        }

        // Check if the ward exists
        $ward = Ward::find($wardId);
        if (!$ward) {
            session()->forget(['selected_ward_id', 'selected_ward_name']);
            return redirect()->route('ward.select')
                ->with('error', 'The selected ward does not exist.');
        }

        // Check if user has access to this ward
        if (!Auth::user()->wards->contains($ward->id)) {
            session()->forget(['selected_ward_id', 'selected_ward_name']);
            return redirect()->route('ward.select')
                ->with('error', 'You do not have access to this ward.');
        }

        return $next($request);
    }
}
