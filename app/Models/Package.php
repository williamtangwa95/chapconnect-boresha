<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'phone_visibility',
        'max_images',
        'max_videos',
        'max_news',
        'price',
        'duration',
        'duration_unit',
        'package_type',
        'status',
    ];

    public function userPackages()
    {
        return $this->hasMany(UserPackage::class);
    }
}
