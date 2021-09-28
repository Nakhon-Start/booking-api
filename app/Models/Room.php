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
        'create_by',
        'room_type',
        'quantity'
    ];

    public function building()
    {
        return $this->belongsTo(Building::class,'building_id','id');
    }
    public function user()
    {
        return $this->belongsTo(User::class,'create_by' , 'id');
    }
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',

    ];

}
