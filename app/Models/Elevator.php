<?php


// app/Models/Elevator.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Elevator extends Model
{
    // app/Models/Elevator.php
    protected $fillable = [
        'designation',
        'address',
        'max_capacity_kg',
        'comments',
        'public_token',
        'created_by',
        // 'updated_by', // si lo usas
    ];


    protected $casts = [
        'fire_system' => 'boolean',
    ];


    protected static function booted()
    {
        static::creating(function ($model) {
            $model->public_token = $model->public_token ?: Str::random(48);
        });
    }

    public function revisions()
    {
        return $this->hasMany(Revision::class);
    }
}
