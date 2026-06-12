<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        $user = Auth::user();

        // Check if the user's role is in the permitted roles
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        // Redirect user based on their actual role to prevent routing loops or dead ends
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('error', 'Access denied to that section.');
        } elseif ($user->isResearcher()) {
            return redirect()->route('researcher.dashboard')->with('error', 'Access denied to that section.');
        } else {
            return redirect()->route('public.dashboard')->with('error', 'Access denied to that section.');
        }
    }
}
