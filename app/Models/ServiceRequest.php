<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $table = 'service_requests';
    protected $primaryKey = 'request_id';
    public $timestamps = false; // since you’re using manual date fields (request_date, updated_at)

    protected $fillable = [
        'resident_id',
        'request_type',
        'request_date',
        'status',
        'remarks',
        'updated_at',
    ];

    /**
     * Relationships
     */

    public function resident()
    {
        return $this->belongsTo(BarangayResident::class, 'resident_id', 'resident_id');
    }

    public function requiredDocuments()
    {
        return $this->hasMany(RequiredDocument::class, 'request_id', 'request_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class, 'request_id', 'request_id');
    }
}
