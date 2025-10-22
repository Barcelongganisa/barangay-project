<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\BarangayResident;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('welcome');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'address' => ['required', 'string', 'max:255'],
            'birthday' => ['required', 'date'],
            'gender' => ['required', 'in:Male,Female,Other'],
            'years_of_residency' => ['required', 'integer', 'min:0'],
            'valid_id' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        // Handle file upload - MAKE SURE THIS LINE EXISTS
        $validIdPath = $request->file('valid_id')->store('valid_ids', 'public');

        // Create User - ADD APPROVAL STATUS FIELDS
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'resident',
            'address' => $request->address,
            'birthday' => $request->birthday,
            'gender' => $request->gender,
            'years_of_residency' => $request->years_of_residency,
            'valid_id_path' => $validIdPath,
            'approval_status' => 'pending', // ADD THIS LINE
            'approved_at' => null, // ADD THIS LINE
            'declined_at' => null, // ADD THIS LINE
            'decline_reason' => null, // ADD THIS LINE
        ]);

        event(new Registered($user));

        // DON'T LOGIN AUTOMATICALLY - REMOVE THESE LINES:
        // Auth::login($user);
        //
        // if ($user->role === 'admin') {
        //     return redirect()->route('admin.dashboard');
        // } else {
        //     return redirect()->route('dashboard');
        // }

        // REPLACE WITH THIS:
        return redirect()->route('register')->with('status', 'Registration submitted! Please wait for admin approval. You will receive an email once your account is approved.');
    }
}