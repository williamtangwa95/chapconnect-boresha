<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'target_user_id',
        'requester_user_id',
        'requester_type',
        'requester_full_name',
        'contact_type',
        'contact_value',
        'region',
        'message',
        'status',
        'reviewed_by',
        'reviewed_at',
        'response',
        'admin_notes',
        'staff_notes',
        'ip_address',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function requesterUser()
    {
        return $this->belongsTo(User::class, 'requester_user_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
