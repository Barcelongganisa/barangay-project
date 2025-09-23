<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangayResident extends Model
{
    use HasFactory;

    protected $table = 'barangay_residents';

    protected $fillable = [
        // 'column1', 'column2', ...
    ];
}
