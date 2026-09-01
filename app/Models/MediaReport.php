<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaReport extends Model
{
    protected $fillable = [
        'media_id',
        'reporter_user_id',
        'reporter_ip',
        'reason',
        'details',
        'status',
        'resolved_by',
        'resolved_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_user_id');
    }

    public function resolver()
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
