<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileView extends Model
{
    use HasFactory;

    protected $fillable = [
        'talent_id',
        'user_id',
        'ip_address',
        'device_fingerprint',
    ];

    public function talent()
    {
        return $this->belongsTo(User::class, 'talent_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
