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
    
}
