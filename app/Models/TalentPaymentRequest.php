<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentPaymentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'likes_count',
        'followers_count',
        'comments_count',
        'views_count',
        'amount',
        'status',
        'payment_method',
        'payment_reference',
        'admin_notes',
        'paid_at',
        'paid_by',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the user that requested the payment.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the admin that completed the payment.
     */
    public function payer()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }
}
