<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // Set the primary key
    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'resident_id',
        'request_id',
        'amount',
        'fee',
        'status',
        'payment_method',
        'notes'
    ];

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class, 'request_id');
    }

    public function resident()
    {
        return $this->belongsTo(BarangayResident::class, 'resident_id');
    }
}