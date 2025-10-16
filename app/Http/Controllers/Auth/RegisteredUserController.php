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
            'address' => ['required', 'string', 'max:255'],
            'birthday' => ['required', 'date'],
            'gender' => ['required', 'in:Male,Female,Other'],
            'years_of_residency' => ['required', 'integer', 'min:0'],
            'valid_id' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ]);

        // Handle file upload - MAKE SURE THIS LINE EXISTS
        $validIdPath = $request->file('valid_id')->store('valid_ids', 'public');

        // Create User
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
        ]);

        // Create corresponding BarangayResident --> turning this on will cause error on registration as this duplicates the creation in User model
        // BarangayResident::create([
        //     'resident_id' => $user->id, // crucial!
        //     'first_name' => $request->name,
        //     'middle_name' => null,
        //     'last_name' => '',
        //     'email' => $request->email,
        //     'barangay_name' => 'Default Barangay',
        //     'contact_number' => '',
        //     'date_of_birth' => $request->birthday,
        //     'gender' => $request->gender,
        //     'civil_status' => 'Single',
        //     'occupation' => 'N/A',
        //     'address' => $request->address,
        //     'valid_id_path' => $validIdPath, 
        // ]);


        event(new Registered($user));

        Auth::login($user);

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('dashboard');
        }
    }
}
