<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'media_id',
        'user_id',
        'parent_id',
        'author_name',
        'comment',
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

    public function replies()
    {
        return $this->hasMany(MediaComment::class, 'parent_id')->oldest();
    }

    public function parent()
    {
        return $this->belongsTo(MediaComment::class, 'parent_id');
    }
}
