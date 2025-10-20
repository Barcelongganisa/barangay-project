<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user is trying to access dashboard
            if ($request->is('dashboard')) {
                if ($user->role === 'admin') {
                    return redirect()->route('admin.dashboard');
                }
                // Residents stay on /dashboard
            }
            
            // Prevent residents from accessing admin routes
            if ($request->is('admin/*') && $user->role !== 'admin') {
                return redirect()->route('dashboard')->with('error', 'Access denied.');
            }
        }

        return $next($request);
    }
}