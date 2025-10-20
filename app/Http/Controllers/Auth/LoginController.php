<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Handle a login request to the application.
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            $user = Auth::user();
            
            // ✅ APPROVAL CHECK - THIS IS WHAT MATTERS
            if ($user->role !== 'admin' && $user->approval_status !== 'approved') {
                Auth::logout(); // Log them out immediately
                
                if ($user->approval_status === 'pending') {
                    return redirect()->route('login')
                        ->with('error', 'Your account is pending approval. Please wait for administrator approval.');
                } else {
                    return redirect()->route('login')
                        ->with('error', 'Your account has been declined. Please contact administrator.');
                }
            }

            return $this->sendLoginResponse($request);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Where to redirect users after login.
     */
    protected function redirectTo()
    {
        $user = Auth::user();
        
        if ($user->role === 'admin') {
            return '/admin/dashboard';
        }
        
        return '/dashboard';
    }
}