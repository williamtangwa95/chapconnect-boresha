<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'talent_id',
        'client_name',
        'client_phone',
        'location',
        'booking_date',
        'message',
        'status',
        'action_by_user_id',
        'action_note',
        'action_at',
        'ip_address',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'action_at' => 'datetime',
    ];

    /**
     * The talent user being booked.
     */
    public function talent()
    {
        return $this->belongsTo(User::class, 'talent_id');
    }

    /**
     * The user (Talent, Admin, or Customer Care) who acted on this booking.
     */
    public function actionByUser()
    {
        return $this->belongsTo(User::class, 'action_by_user_id');
    }
}
