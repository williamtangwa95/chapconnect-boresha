<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaShare extends Model
{
    use HasFactory;

    protected $fillable = [
        'media_id',
        'user_id',
        'platform',
        'ip_address',
        'device_fingerprint',
    ];

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
