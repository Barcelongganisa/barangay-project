<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Allow admin to access everything
            if ($user->role === 'admin') {
                return $next($request);
            }
            
            // Check if user is approved
            if (!$user->isApproved()) {
                Auth::logout();
                
                $message = $user->isPending() 
                    ? 'Your account is pending approval. Please wait for administrator approval.'
                    : 'Your account has been declined. Please contact administrator.';
                
                return redirect()->route('login')->with('error', $message);
            }
        }

        return $next($request);
    }
}