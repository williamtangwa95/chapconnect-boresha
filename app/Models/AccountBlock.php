<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountBlock extends Model
{
    protected $fillable = [
        'user_id',
        'attempts_count',
        'time_interval',
        'customer_complaint',
        'requested_by',
        'issued_by',
        'status',
        'unblocked_at',
    ];

    protected $casts = [
        'unblocked_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
