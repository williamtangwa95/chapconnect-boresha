<?php

namespace App\Helpers;

class VideoHelper
{
    /**
     * Clean and resolve Google search redirects to extract the actual video URL.
     */
    public static function cleanUrl(?string $url): ?string
    {
        if (empty($url)) {
            return $url;
        }

        $trimmed = trim($url);

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
     * Detect platform from URL.
     */
    public static function detectPlatform(?string $url): string
    {
        if (empty($url)) return 'unknown';
        $url = strtolower(trim($url));
        if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) return 'youtube';
        if (str_contains($url, 'vimeo.com')) return 'vimeo';
        if (str_contains($url, 'instagram.com')) return 'instagram';
        if (str_contains($url, 'facebook.com') || str_contains($url, 'fb.watch')) return 'facebook';
        if (str_contains($url, 'tiktok.com')) return 'tiktok';
        return 'file';
    }

    /**
     * Helper to render video player HTML for local files or YouTube/Vimeo/Instagram/Facebook/TikTok URLs.
     */
    public static function renderEmbed(?string $url, string $extraClass = ''): string
    {
        $url = self::cleanUrl($url);

        if (empty($url)) {
            return '<div style="background:#0f172a; padding:30px; text-align:center; color:#94a3b8; border-radius:10px;"><i class="bi bi-film" style="font-size:2rem; display:block; margin-bottom:6px;"></i>No video available</div>';
        }

        $trimmedUrl = trim($url);
        $wrapper = '<div class="video-container" style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:10px;background:#000;">';
        $wrapperEnd = '</div>';

        // YouTube
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=|shorts\/)|youtu\.be\/)([^"&?\/\s]{11})/', $trimmedUrl, $matches)) {
            $youtubeId = $matches[1];
            return $wrapper .
                '<iframe src="https://www.youtube.com/embed/' . $youtubeId . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;border:none;border-radius:10px;"></iframe>' .
                $wrapperEnd;
        }

        // Vimeo
        if (preg_match('/vimeo\.com\/(?:.*#|.*\/)?([0-9]+)/', $trimmedUrl, $matches)) {
            $vimeoId = $matches[1];
            return $wrapper .
                '<iframe src="https://player.vimeo.com/video/' . $vimeoId . '" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;border:none;border-radius:10px;"></iframe>' .
                $wrapperEnd;
        }

        // Instagram Reels / Posts
        if (str_contains($trimmedUrl, 'instagram.com')) {
            $cleanIg = rtrim(preg_replace('/[?#].*$/', '', $trimmedUrl), '/');
            $embedUrl = $cleanIg . '/embed/';
            return $wrapper .
                '<iframe src="' . e($embedUrl) . '" frameborder="0" scrolling="no" allowtransparency="true" allowfullscreen="true" style="position:absolute;top:0;left:0;width:100%;height:100%;border:none;border-radius:10px;"></iframe>' .
                $wrapperEnd;
        }

        // Facebook Videos
        if (str_contains($trimmedUrl, 'facebook.com') || str_contains($trimmedUrl, 'fb.watch')) {
            $encodedUrl = urlencode($trimmedUrl);
            return $wrapper .
                '<iframe src="https://www.facebook.com/plugins/video.php?href=' . $encodedUrl . '&show_text=false&autoplay=false" frameborder="0" allowfullscreen allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" style="position:absolute;top:0;left:0;width:100%;height:100%;border:none;border-radius:10px;"></iframe>' .
                $wrapperEnd;
        }

        // TikTok Videos
        if (str_contains($trimmedUrl, 'tiktok.com')) {
            preg_match('/\/video\/(\d+)/', $trimmedUrl, $ttMatches);
            if (!empty($ttMatches[1])) {
                $ttId = $ttMatches[1];
                return $wrapper .
                    '<iframe src="https://www.tiktok.com/embed/v2/' . $ttId . '" frameborder="0" allow="autoplay; encrypted-media; fullscreen" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;border:none;border-radius:10px;"></iframe>' .
                    $wrapperEnd;
            }
            return '<div style="background:#0f172a; padding:20px; text-align:center; color:#94a3b8; border-radius:10px;">
                <i class="bi bi-tiktok" style="font-size:2rem; display:block; margin-bottom:8px; color:#69C9D0;"></i>
                <p style="margin:0 0 10px 0; font-size:0.88rem;">TikTok video</p>
                <a href="' . e($trimmedUrl) . '" target="_blank" rel="noopener" style="display:inline-flex;align-items:center;gap:6px;background:#69C9D0;color:#000;font-weight:700;padding:8px 18px;border-radius:20px;text-decoration:none;font-size:0.85rem;">
                    <i class="bi bi-box-arrow-up-right"></i> Watch on TikTok
                </a></div>';
        }

        // Local file or direct URL
        $srcUrl = $trimmedUrl;
        if (!str_starts_with($trimmedUrl, 'http://') && !str_starts_with($trimmedUrl, 'https://')) {
            $srcUrl = asset(ltrim($trimmedUrl, '/'));
        }

        return '<div class="video-wrapper" style="position:relative;width:100%;background:#000;border-radius:10px 10px 0 0;overflow:visible;">
                    <video controls playsinline preload="metadata" style="width:100%; height:auto; display:block; border-radius:10px 10px 0 0; max-height:450px;">
                        <source src="' . e($srcUrl) . '">
                        Your browser does not support the video tag.
                    </video>
                </div>';
    }
}