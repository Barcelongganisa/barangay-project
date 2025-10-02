<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ResidentProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Fetch barangay resident data
        $residentData = DB::table('barangay_residents')
            ->where('resident_id', $user->id)
            ->first();

        return view('resident.resident-profile', [
            'user' => $user,
            'residentData' => $residentData,
        ]);
    }

    /**
     * Update the user's profile information.
     */
public function update(Request $request)
{
    $user = Auth::user();

    // Validate incoming data
    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'middle_name' => 'nullable|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email',
        'phone' => 'nullable|string|max:20',
        'birthdate' => 'required|date',
        'gender' => 'required|string',
        'civil_status' => 'required|string',
        'occupation' => 'required|string',
        'barangay' => 'required|string',
        'address' => 'required|string',
    ]);

    // ✅ Update users table
   DB::table('users')
    ->where('id', $user->id)
    ->update([
        'name' => $validated['first_name'] . ' ' . ($validated['middle_name'] ?? '') . ' ' . $validated['last_name'],
        'email' => $validated['email'],
        'updated_at' => now(),
    ]);


    // ✅ Check if resident exists in barangay_residents
    $barangayResident = DB::table('barangay_residents')
        ->where('resident_id', $user->id)
        ->first();

    if ($barangayResident) {
        // Update existing record
        DB::table('barangay_residents')
            ->where('resident_id', $user->id)
            ->update([
                'contact_number' => $validated['phone'],
                'date_of_birth' => $validated['birthdate'],
                'gender' => $validated['gender'],
                'civil_status' => $validated['civil_status'],
                'occupation' => $validated['occupation'],
                'barangay_name' => $validated['barangay'],
                'address' => $validated['address'],
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'last_name' => $validated['last_name'],
                'updated_at' => now(),
            ]);
    } else {
        // Insert new record if not exists
        DB::table('barangay_residents')->insert([
            'resident_id' => $user->id,
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'contact_number' => $validated['phone'],
            'date_of_birth' => $validated['birthdate'],
            'gender' => $validated['gender'],
            'civil_status' => $validated['civil_status'],
            'occupation' => $validated['occupation'],
            'barangay_name' => $validated['barangay'],
            'address' => $validated['address'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    return redirect()->route('resident.resident-profile')
        ->with('status', 'profile-updated');
}


    /**
     * Update the user's password.
     */
    public function passwordUpdate(Request $request): RedirectResponse
    {
        // Add password update logic here or use the existing password route
        // For now, redirect back to resident profile
        return Redirect::route('resident.resident-profile')->with('status', 'password-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}