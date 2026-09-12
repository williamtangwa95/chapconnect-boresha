<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'file_path',
        'content',
        'moderation_status',
        'moderation_reason',
        'moderation_score',
        'is_visible',
        'reviewed_by',
        'reviewed_at',
        'report_count',
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'moderation_score' => 'float',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Scope for media that is approved and visible to the public.
     */
    public function scopePubliclyVisible($query)
    {
        return $query->where('is_visible', true)
                     ->where('moderation_status', 'approved');
    }

    /**
     * Get the user that owns the media.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get reviewer.
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get user reports for this media item.
     */
    public function reports()
    {
        return $this->hasMany(MediaReport::class);
    }

    /**
     * Get likes for this media post.
     */
    public function likes()
    {
        return $this->hasMany(MediaLike::class);
    }

    /**
     * Get comments for this media post.
     */
    public function comments()
    {
        return $this->hasMany(MediaComment::class);
    }

    /**
     * Get shares for this media post.
     */
    public function shares()
    {
        return $this->hasMany(MediaShare::class);
    }
}
