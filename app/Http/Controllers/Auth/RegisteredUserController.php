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
        'resident_id' => $user->id, // crucial!
        'first_name' => $request->name,
        'middle_name' => null,
        'last_name' => '',
        'email' => $request->email,
        'barangay_name' => 'Default Barangay',
        'contact_number' => '',
        'date_of_birth' => now(),
        'gender' => 'Male',
        'civil_status' => 'Single',
        'occupation' => 'N/A',
        'address' => '',
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
