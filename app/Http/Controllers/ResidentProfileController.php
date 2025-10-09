<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\BarangayResident;


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
    $user = $request->user();

    // Validate inputs
    $validated = $request->validate([
        'first_name' => ['required', 'string', 'max:255'],
        'middle_name' => ['nullable', 'string', 'max:255'],
        'last_name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        'phone' => ['nullable', 'string', 'max:20'],
        'birthdate' => ['required', 'date'],
        'gender' => ['required', 'in:Male,Female,Others'],
        'civil_status' => ['required', 'string', 'max:50'],
        'occupation' => ['required', 'string', 'max:255'],
        'barangay' => ['required', 'string', 'max:255'],
        'address' => ['required', 'string', 'max:500'],
    ]);

    try {
        // 1️⃣ Update users table
        $user->update([
            'name' => $validated['first_name'] . ' ' . ($validated['middle_name'] ?? '') . ' ' . $validated['last_name'],
            'email' => $validated['email'],
        ]);

        // 2️⃣ Update barangay_residents table
        $resident = BarangayResident::where('resident_id', $user->id)->first();

        if ($resident) {
            $resident->update([
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'] ?? null,
                'last_name' => $validated['last_name'],
                'contact_number' => $validated['phone'] ?? '',
                'date_of_birth' => $validated['birthdate'],
                'gender' => $validated['gender'],
                'civil_status' => $validated['civil_status'],
                'occupation' => $validated['occupation'],
                'barangay_name' => $validated['barangay'],
                'address' => $validated['address'],
                'email' => $validated['email'],
                'updated_at' => now(),
            ]);
        } else {
            return redirect()->back()->withErrors(['error' => 'Resident profile not found.']);
        }

        return redirect()->route('resident.resident-profile')
                         ->with('status', 'profile-updated');

    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['error' => 'Update failed: ' . $e->getMessage()]);
    }
}


    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048', // 2MB max
        ]);

        $user = $request->user();

        // Store the image in public storage
        $path = $request->file('profile_photo')->store('profile-photos', 'public');

        // Optionally, delete old photo
        if ($user->profile_photo_path) {
            \Storage::disk('public')->delete($user->profile_photo_path);
        }

        // Save new path in database
        $user->profile_photo_path = $path;
        $user->save();

        return Redirect::route('resident.resident-profile')
            ->with('status', 'photo-updated');
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