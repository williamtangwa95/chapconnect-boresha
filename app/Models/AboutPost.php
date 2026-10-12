<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'author_name',
        'author_role',
        'title',
        'title_sw',
        'badge',
        'badge_sw',
        'content',
        'content_sw',
        'image_path',
        'is_published',
    ];

    /**
     * Get the localized title based on active app locale.
     */
    public function getLocalizedTitleAttribute()
    {
        if (app()->getLocale() === 'sw' && !empty($this->title_sw)) {
            return $this->title_sw;
        }
        return __($this->title);
    }

    /**
     * Get the localized badge based on active app locale.
     */
    public function getLocalizedBadgeAttribute()
    {
        if (app()->getLocale() === 'sw' && !empty($this->badge_sw)) {
            return $this->badge_sw;
        }
        return __($this->badge ?: 'Announcement');
    }

    /**
     * Get the localized content based on active app locale.
     */
    public function getLocalizedContentAttribute()
    {
        if (app()->getLocale() === 'sw' && !empty($this->content_sw)) {
            return $this->content_sw;
        }
        return __($this->content);
    }

    protected $casts = [
        'is_published' => 'boolean',
    ];

    /**
     * Get the user/staff who authored the post.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for published posts.
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
