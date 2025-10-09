<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Automatically create a barangay_residents record when a new user registers.
     */
    protected static function booted()
    {
        static::created(function ($user) {
            // Only create if not already existing
            if (!\App\Models\BarangayResident::where('resident_id', $user->id)->exists()) {
                \App\Models\BarangayResident::create([
                    'resident_id' => $user->id,
                    'first_name' => $user->name ?? 'N/A',
                    'last_name' => 'N/A',
                    'middle_name' => null,
                    'contact_number' => 'N/A',
                    'date_of_birth' => now(),
                    'gender' => 'Other',
                    'civil_status' => 'Single',
                    'occupation' => 'N/A',
                    'barangay_name' => 'Unknown Barangay',
                    'address' => 'N/A',
                    'email' => $user->email,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
