<?php

namespace App\Helpers;

class VideoHelper
{
    /**
     * Clean and resolve Google search redirects to extract the actual YouTube URL.
     */
    public static function cleanUrl(?string $url): ?string
    {
        if (empty($url)) {
            return $url;
        }

        $trimmed = trim($url);

        // Check if it is a Google redirect URL
        if (str_contains($trimmed, 'google.com/url?') || str_contains($trimmed, 'google.co.tz/url?') || str_contains($trimmed, 'google.co.uk/url?')) {
            $parsed = parse_url($trimmed);
            if (isset($parsed['query'])) {
                parse_str($parsed['query'], $query);
                if (isset($query['q'])) {
                    return trim($query['q']);
                } elseif (isset($query['url'])) {
                    return trim($query['url']);
                }
            }
        }

        return $trimmed;
    }

    /**
     * Helper to render video player HTML for local files or YouTube/Vimeo/external URLs.
     */
    public static function renderEmbed(?string $url, string $extraClass = ''): string
    {
        $url = self::cleanUrl($url);

        if (empty($url)) {
            return '<div style="background:#0f172a; padding:30px; text-align:center; color:#94a3b8; border-radius:10px;"><i class="bi bi-film" style="font-size:2rem; display:block; margin-bottom:6px;"></i>No video available</div>';
        }

        $trimmedUrl = trim($url);

        // YouTube URL matching (youtube.com, youtu.be, shorts, embed)
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=|shorts\/)|youtu\.be\/)([^"&?\/\s]{11})/', $trimmedUrl, $matches)) {
            $youtubeId = $matches[1];
            return '<div class="video-container" style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:10px;background:#000;">
                        <iframe src="https://www.youtube.com/embed/' . $youtubeId . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;border:none;border-radius:10px;"></iframe>
                    </div>';
        }

        // Vimeo URL matching
        if (preg_match('/vimeo\.com\/(?:.*#|.*\/)?([0-9]+)/', $trimmedUrl, $matches)) {
            $vimeoId = $matches[1];
            return '<div class="video-container" style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:10px;background:#000;">
                        <iframe src="https://player.vimeo.com/video/' . $vimeoId . '" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;border:none;border-radius:10px;"></iframe>
                    </div>';
        }

        // Determine full URL for local media files or direct video links
        $srcUrl = $trimmedUrl;
        if (!str_starts_with($trimmedUrl, 'http://') && !str_starts_with($trimmedUrl, 'https://')) {
            $srcUrl = asset(ltrim($trimmedUrl, '/'));
        }

        // Default HTML5 video player for local files or direct MP4/WEBM URLs
        return '<div class="video-wrapper" style="position:relative;width:100%;background:#000;border-radius:10px 10px 0 0;overflow:visible;">
                    <video controls playsinline preload="metadata" style="width:100%; height:auto; display:block; border-radius:10px 10px 0 0; max-height:450px;">
                        <source src="' . e($srcUrl) . '">
                        Your browser does not support the video tag.
                    </video>
                </div>';
    }
}
