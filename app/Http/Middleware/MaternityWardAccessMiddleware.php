<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Ward;

class MaternityWardAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Query to check if the user has access to maternity wards
        $userId = Auth::id();

        $hasMaternityAccess = DB::table('user_ward')
            ->join('wards', 'user_ward.ward_id', '=', 'wards.id')
            ->where('user_ward.user_id', $userId)
            ->where(function($query) {
                $query->where('wards.name', 'like', '%MATERNITY%')
                    ->orWhere('wards.name', 'like', '%LABOUR%')
                    ->orWhere('wards.name', 'like', '%DELIVERY%')
                    ->orWhere('wards.name', 'like', '%OB-GYN%');
            })
            ->exists();

        if (!$hasMaternityAccess) {
            return redirect()->route('delivery.access.denied');
        }

        return $next($request);
    }
}
