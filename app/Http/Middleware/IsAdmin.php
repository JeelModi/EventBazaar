<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Only allow admins
        if (Session::get('user_role') !== 'admin') {
            return redirect()->route('events.index')->with('error', 'Access denied.');
        }
        return $next($request);
    }
}