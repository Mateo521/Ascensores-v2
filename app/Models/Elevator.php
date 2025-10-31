<?php


// app/Models/Elevator.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Elevator extends Model
{
    protected $fillable = ['designation', 'address', 'public_token', 'is_active', 'created_by'];

    protected static function booted() {
        static::creating(function ($model) {
            $model->public_token = $model->public_token ?: Str::random(48);
        });
    }

    public function revisions() { return $this->hasMany(Revision::class); }
}
