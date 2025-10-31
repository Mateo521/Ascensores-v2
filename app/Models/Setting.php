<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'cuit',
        'start_of_activity',
        'emergency_phone',
        'logo_path'
    ];

    protected $casts = [
        'start_of_activity' => 'date',
    ];
}
