<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureLoggedIn
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() || $request->session()->get('logged_in')) {
            return $next($request);
        }
        return redirect('/login')->with('error', 'Please login first!');
    }
}
