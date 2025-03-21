<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EmergencyDepartmentMiddleware
{
    /**
     * Handle an incoming request.
     * Checks if the selected ward is Emergency Department and redirects to the Emergency dashboard
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

        $wardName = session('selected_ward_name');

        // If the request is already for the emergency dashboard, let it proceed
        if ($request->routeIs('emergency.dashboard')) {
            return $next($request);
        }

        // If the selected ward is the Emergency Department and the user is trying to access the regular dashboard
        if ($wardName === 'Emergency Department' && $request->routeIs('dashboard')) {
            return redirect()->route('emergency.dashboard');
        }

        return $next($request);
    }
}
