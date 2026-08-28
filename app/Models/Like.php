<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'talent_id',
        'ip_address',
        'device_fingerprint',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function talent()
    {
        return $this->belongsTo(User::class, 'talent_id');
    }
}
