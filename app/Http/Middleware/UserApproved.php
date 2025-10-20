<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->role === 'admin') {
                return $next($request);
            }
            
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