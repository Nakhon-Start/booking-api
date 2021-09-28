<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessTokens extends Model
{
    use HasFactory;
    public $table = 'personal_access_tokens';
    public $timestamps = true;
    protected $fillable = [
        'id',
        'tokenable',
        'name',
        'token',
        'abilities',
        'last_used_at'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',

    ];
}
