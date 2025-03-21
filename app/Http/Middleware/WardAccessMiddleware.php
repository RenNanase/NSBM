<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WardAccessMiddleware
{
    /**
     * Handle an incoming request.
     * Check if the user has access to the selected ward stored in the session.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Check if a ward is selected in the session
        if (!session()->has('selected_ward_id')) {
            return redirect()->route('ward.select')->with('error', 'Please select a ward first.');
        }

        $wardId = session('selected_ward_id');
        $user = Auth::user();

        // Check if the user has access to this ward using the user_ward pivot table
        $hasAccess = DB::table('user_ward')
            ->where('user_id', $user->id)
            ->where('ward_id', $wardId)
            ->exists();

        // Admin has access to all wards
        if (!$hasAccess && $user->username !== 'admin') {
            return response()->view('errors.ward-access-denied');
        }

        return $next($request);
    }
}
