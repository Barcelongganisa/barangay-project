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
        return view('auth.register');
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
    ]);

    // Create User
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'resident',
    ]);

    // Create corresponding BarangayResident
    BarangayResident::create([
        'first_name' => $request->name, // if you only have 'name', store it here
        'last_name' => '', // you can separate later if you want
        'middle_name' => null,
        'email' => $request->email,
        'barangay_name' => 'Default Barangay', // change as needed
        'household_no' => '',
        'date_of_birth' => now(), // you may prompt user to update later
        'gender' => 'Male', // default, can update later
        'contact_number' => '',
        'address' => '',
        'civil_status' => 'Single',
        'occupation' => 'N/A',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    event(new Registered($user));

    Auth::login($user);

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->route('resident.dashboard');
    }
}
}
