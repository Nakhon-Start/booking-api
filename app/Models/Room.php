<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    public $timestamps = true;
    protected $fillable = [
        'name',
        'description',
        'is_active',
        'building_id',
        'create_by'
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }
    
}
