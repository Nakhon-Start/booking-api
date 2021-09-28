<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Responsibilities extends Model
{
    use HasFactory;
    protected $table = 'responsibilities';
    protected $fillable = [
        'user_id',
        'building_id',
    ];

    public function checker()
    {
        return $this->belongsTo(User::class , 'user_id' , 'id');
    }
    public function building(){
        return $this->belongsTo(Building::class , 'building_id' , 'id');
    }

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',

    ];
}
