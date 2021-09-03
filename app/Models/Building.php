<?php

namespace App\Models;

use App\Models\Room;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Building extends Model
{
    use HasFactory;
    protected $table = 'building';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'description',
        'is_active',
        'create_by'
    ];

    public function room()
    {
        return $this->hasMany(Room::class);
    }

    public function checker()
    {
        return $this->hasMany(Responsibilities::class);
    }
}
