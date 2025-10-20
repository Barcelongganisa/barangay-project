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
        'address', 
        'birthday', 
        'gender', 
        'years_of_residency', 
        'valid_id_path',
        'approval_status', // ADD THIS LINE
        'approved_at', // ADD THIS LINE
        'declined_at', // ADD THIS LINE
        'decline_reason', // ADD THIS LINE
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
            'approved_at' => 'datetime', // ADD THIS LINE
            'declined_at' => 'datetime', // ADD THIS LINE
        ];
    }

    // ADD THESE METHODS FOR APPROVAL SYSTEM:
    
    /**
     * Scope for approved users
     */
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    /**
     * Scope for pending users
     */
    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    /**
     * Scope for declined users
     */
    public function scopeDeclined($query)
    {
        return $query->where('approval_status', 'declined');
    }

    /**
     * Check if user is approved
     */
    public function isApproved()
    {
        return $this->approval_status === 'approved';
    }

    /**
     * Check if user is pending
     */
    public function isPending()
    {
        return $this->approval_status === 'pending';
    }

    /**
     * Check if user is declined
     */
    public function isDeclined()
    {
        return $this->approval_status === 'declined';
    }

    /**
     * Automatically create a barangay_residents record when a new user registers.
     */
    protected static function booted()
    {
        static::created(function ($user) {
            // Only create if not already existing AND when user is approved
            if (!\App\Models\BarangayResident::where('resident_id', $user->id)->exists()) {
                \App\Models\BarangayResident::create([
                    'resident_id' => $user->id,
                    'first_name' => $user->name ?? 'N/A',
                    'last_name' => 'N/A',
                    'middle_name' => null,
                    'contact_number' => 'N/A',
                    'date_of_birth' => $user->birthday ?? now(), // Use user's birthday if available
                    'gender' => $user->gender ?? 'Other', // Use user's gender if available
                    'civil_status' => 'Single',
                    'occupation' => 'N/A',
                    'barangay_name' => 'Unknown Barangay',
                    'address' => $user->address ?? 'N/A', // Use user's address if available
                    'email' => $user->email,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}