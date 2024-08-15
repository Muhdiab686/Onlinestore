<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Check_Pharmacist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $PharmacistRole = auth()->user()->role;
        if ($PharmacistRole === "pharmacist") {
            return $next($request);
        }

    return response()->json(['msg'=> 'Access Denied']);
    }
}
