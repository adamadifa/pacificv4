<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckForceLogout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->force_logout) {
            Auth::logout();

            // Redirect ke halaman login atau halaman lain dengan pesan
            return redirect()->route('login')->with('message', 'Anda telah dikeluarkan oleh admin.');
        }

        return $next($request);
    }
}
