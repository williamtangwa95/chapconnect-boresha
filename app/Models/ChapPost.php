<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ChapPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'category',
        'media_type',
        'featured_image',
        'audio_path',
        'video_url',
        'video_path',
        'summary',
        'content',
        'views_count',
        'likes_count',
        'is_pinned',
        'is_published',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_published' => 'boolean',
        'views_count' => 'integer',
        'likes_count' => 'integer',
    ];

    /**
     * Relationship: Author of the post (Admin / Customer Care)
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Automatically generate unique slug if not set
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->slug)) {
                $baseSlug = Str::slug($post->title);
                $slug = $baseSlug ?: 'post-' . time();
                $count = static::where('slug', 'LIKE', "{$slug}%")->count();
                $post->slug = $count ? "{$slug}-{$count}" : $slug;
            }
        });
    }

    /**
     * Category badges helper
     */
    public function getCategoryDetailsAttribute()
    {
        switch ($this->category) {
            case 'tutorial':
                return [
                    'label' => __('Tutorials & Guides'),
                    'short_label' => __('Tutorial'),
                    'icon' => 'bi-mortarboard-fill',
                    'bg' => '#e0e7ff',
                    'color' => '#4338ca',
                    'border' => '#c7d2fe',
                ];
            case 'news':
                return [
                    'label' => __('ChapConnect News'),
                    'short_label' => __('News'),
                    'icon' => 'bi-newspaper',
                    'bg' => '#dcfce7',
                    'color' => '#15803d',
                    'border' => '#bbf7d0',
                ];
            case 'announcement':
                return [
                    'label' => __('Official Announcements'),
                    'short_label' => __('Announcement'),
                    'icon' => 'bi-megaphone-fill',
                    'bg' => '#fef3c7',
                    'color' => '#b45309',
                    'border' => '#fde68a',
                ];
            case 'testimony':
                return [
                    'label' => __('Success Testimonies'),
                    'short_label' => __('Testimony'),
                    'icon' => 'bi-trophy-fill',
                    'bg' => '#fce7f3',
                    'color' => '#be185d',
                    'border' => '#fbcfe8',
                ];
            default:
                return [
                    'label' => __('General'),
                    'short_label' => __('General'),
                    'icon' => 'bi-info-circle-fill',
                    'bg' => '#f1f5f9',
                    'color' => '#475569',
                    'border' => '#cbd5e1',
                ];
        }
    }

    /**
     * Media type badge helper
     */
    public function getMediaTypeDetailsAttribute()
    {
        switch ($this->media_type) {
            case 'audio':
                return [
                    'label' => __('Audio Guide'),
                    'icon' => 'bi-music-note-beamed',
                    'color' => '#8b5cf6',
                ];
            case 'video':
                return [
                    'label' => __('Video Clip'),
                    'icon' => 'bi-play-circle-fill',
                    'color' => '#ef4444',
                ];
            case 'photo':
                return [
                    'label' => __('Photo Showcase'),
                    'icon' => 'bi-image-fill',
                    'color' => '#3b82f6',
                ];
            default:
                return [
                    'label' => __('Article'),
                    'icon' => 'bi-file-text-fill',
                    'color' => '#64748b',
                ];
        }
    }
}
