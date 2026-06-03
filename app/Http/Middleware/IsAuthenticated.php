<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class IsAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        // If no user in session, redirect to login
        if (!Session::has('user_id')) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }
        return $next($request);
    }
}