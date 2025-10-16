<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Models\BarangayResident;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Validation\Rules;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'address' => ['required', 'string', 'max:255'],
            'birthday' => ['required', 'date'],
            'gender' => ['required', 'in:Male,Female,Other'],
            'years_of_residency' => ['required', 'integer', 'min:0'],
            'valid_id' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ])->validate();

        // Handle file upload
        // $validIdPath = $input['valid_id']->store('valid_ids', 'public');

        // Create User
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'role' => 'resident',
            'address' => $input['address'],
            'birthday' => $input['birthday'],
            'gender' => $input['gender'],
            'years_of_residency' => $input['years_of_residency'],
            // 'valid_id_path' => $validIdPath,
        ]);

        // Create BarangayResident
        BarangayResident::create([
            'resident_id' => $user->id,
            'first_name' => $input['name'],
            'middle_name' => null,
            'last_name' => '',
            'email' => $input['email'],
            'barangay_name' => 'Default Barangay',
            'contact_number' => '',
            'date_of_birth' => $input['birthday'],
            'gender' => $input['gender'],
            'civil_status' => 'Single',
            'occupation' => 'N/A',
            'address' => $input['address'],
        ]);

        return $user;
    }
}