<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorActivity extends Model
{
    use HasFactory;

    protected $table = 'visitor_activities';

    protected $fillable = [
        'ip_address',
        'location',
        'url',
        'method',
        'user_agent',
        'device_type',
        'browser',
        'user_id',
        'session_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
