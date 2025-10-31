<?php
// app/Models/Revision.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Revision extends Model
{
    protected $fillable = ['elevator_id','year','month','checked','checked_at','technician_id','notes'];

    public function elevator() { return $this->belongsTo(Elevator::class); }
}
