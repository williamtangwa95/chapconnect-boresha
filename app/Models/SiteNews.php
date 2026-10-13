<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteNews extends Model
{
    use HasFactory;

    protected $table = 'site_news';

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'body',
        'image_path',
        'video_path',
        'video_url',
        'is_published',
        'priority',
        'expires_at',
        'views_count',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'expires_at' => 'datetime',
        'views_count' => 'integer',
        'priority' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(SiteNewsLike::class, 'site_news_id');
    }

    public function shares()
    {
        return $this->hasMany(SiteNewsShare::class, 'site_news_id');
    }

    /**
     * Scope for published, non-expired news.
     */
    public function scopeActive($query)
    {
        return $query->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Get the video source (external URL or local storage path).
     */
    public function getVideoSourceAttribute(): ?string
    {
        return $this->video_url ?: $this->video_path;
    }

    /**
     * Author display name.
     */
    public function getAuthorNameAttribute(): string
    {
        return $this->user ? $this->user->name : 'ChapConnect';
    }

    /**
     * Check if currently liked by given user/request.
     */
    public function isLikedBy($userId = null, $ip = null, $deviceFingerprint = null): bool
    {
        $query = $this->likes();
        if ($userId) {
            return $query->where('user_id', $userId)->exists();
        }
        return $query->where(function ($q) use ($ip, $deviceFingerprint) {
            $q->where('ip_address', $ip);
            if ($deviceFingerprint) {
                $q->orWhere('device_fingerprint', $deviceFingerprint);
            }
        })->whereNull('user_id')->exists();
    }
}
