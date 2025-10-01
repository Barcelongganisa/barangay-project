<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequiredDocument extends Model
{
    use HasFactory;

    protected $table = 'required_documents';
    protected $primaryKey = 'document_id';

    protected $fillable = [
        'request_id',
        'document_type',
        'file_path',
    ];

    public function serviceRequest()
    {
        return $this->belongsTo(ServiceRequest::class, 'request_id', 'request_id');
    }
}
