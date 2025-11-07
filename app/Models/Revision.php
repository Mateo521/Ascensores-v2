<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    protected $fillable = [
        'elevator_id',
        'year',
        'month',
        'checked',
        'checked_at',
        'notes'
    ];

    protected $casts = [
        'checked' => 'boolean',
        'checked_at' => 'datetime', // ← IMPORTANTE: esto convierte el string a Carbon
    ];

    public function elevator()
    {
        return $this->belongsTo(Elevator::class);
    }
}
