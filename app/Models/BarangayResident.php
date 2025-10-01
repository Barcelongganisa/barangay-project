<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangayResident extends Model
{
    use HasFactory;

    protected $table = 'barangay_residents';
    protected $primaryKey = 'resident_id';
    public $timestamps = false; // because your table uses date columns, not timestamp()

    protected $fillable = [
        'barangay_name',
        'household_no',
        'first_name',
        'middle_name',
        'last_name',
        'date_of_birth',
        'gender',
        'contact_number',
        'email',
        'address',
        'civil_status',
        'occupation',
        'created_at',
        'updated_at',
    ];

    public function serviceRequests()
    {
        return $this->hasMany(ServiceRequest::class, 'resident_id', 'resident_id');
    }
}
