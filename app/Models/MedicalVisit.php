<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalVisit extends Model
{
    use HasFactory;

    protected $table = 'medical_visits';

    protected $fillable = [
        // 'column1', 'column2', ...
    ];
}
