<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'user_id',
        'reporter_name',
        'reporter_email',
        'reporter_phone',
        'subject',
        'category',
        'priority',
        'status',
        'description',
        'assigned_to',
        'resolution_notes',
        'recommendations',
    ];

    /**
     * Auto-generate unique ticket_number on create if missing.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            if (empty($ticket->ticket_number)) {
                $ticket->ticket_number = 'TK-' . strtoupper(substr(uniqid(), -6));
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Helpers for badge styling.
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'open', 'pending' => 'background: rgba(239, 68, 68, 0.12); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.25);',
            'in_progress', 'approved' => 'background: rgba(245, 158, 11, 0.12); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.25);',
            'resolved' => 'background: rgba(16, 185, 129, 0.12); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.25);',
            'closed', 'cancelled' => 'background: rgba(100, 116, 139, 0.12); color: #64748b; border: 1px solid rgba(100, 116, 139, 0.25);',
            default => 'background: rgba(100, 116, 139, 0.12); color: #64748b; border: 1px solid rgba(100, 116, 139, 0.25);',
        };
    }

    public function getPriorityBadgeClassAttribute()
    {
        return match($this->priority) {
            'urgent' => 'background: #dc2626; color: #fff;',
            'high' => 'background: #f97316; color: #fff;',
            'medium' => 'background: #3b82f6; color: #fff;',
            'low' => 'background: #64748b; color: #fff;',
            default => 'background: #64748b; color: #fff;',
        };
    }
}
