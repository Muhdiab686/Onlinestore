<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Check_Admin
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
        $AdminRole = auth()->user()->role;
        if ($AdminRole === "admin") {
            return $next($request);
        }

    return response()->json(['msg'=> 'Access Denied']);
    }
}
