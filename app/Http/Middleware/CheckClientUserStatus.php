<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckClientUserStatus
{
    /**
     * Handle an incoming Client side request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->user()->status == '0') {
            return redirect()->route('dashboard')->with('error','Already logged in with Admin Account!');
        }

        return $next($request);
    }
}
