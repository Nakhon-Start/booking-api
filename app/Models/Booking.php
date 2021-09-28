<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    public $table = 'booking';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'booker_id',
        'checker_id',
        'room_id',
        'booker_note',
        'start_date',
        'end_date',
        'checker_note',
        'booking_status',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class , 'room_id' , 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class , 'booker_id' , 'id');
    }

    public function checker()
    {
        return $this->belongsTo(User::class , 'checker_id' , 'id');
    }
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',

    ];

}
