<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'package_id',
        'package_name_snapshot',
        'price_snapshot',
        'duration_snapshot',
        'duration_unit_snapshot',
        'phone_visibility_snapshot',
        'max_images_snapshot',
        'max_videos_snapshot',
        'max_news_snapshot',
        'package_type_snapshot',
        'start_date',
        'end_date',
        'status',
        'assigned_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function package()
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'user_package_id');
    }
}
