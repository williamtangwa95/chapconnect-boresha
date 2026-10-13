<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteNewsLike extends Model
{
    use HasFactory;

    protected $table = 'site_news_likes';

    protected $fillable = [
        'site_news_id',
        'user_id',
        'ip_address',
        'device_fingerprint',
    ];

    public function siteNews()
    {
        return $this->belongsTo(SiteNews::class, 'site_news_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
