<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NonEmergencyDepartmentMiddleware
{
    /**
     * Handle an incoming request.
     * Checks if the user is NOT from Emergency Department
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session('selected_ward_name') === 'Emergency Department') {
            return redirect()->route('emergency.dashboard')
                ->with('error', 'This feature is not available for Emergency Department.');
        }

        return $next($request);
    }
}
