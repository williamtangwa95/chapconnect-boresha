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

        // YouTube (enablejsapi=1 for postMessage pause control)
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=|shorts\/)|youtu\.be\/)([^"&?\/\s]{11})/', $trimmedUrl, $matches)) {
            $youtubeId = $matches[1];
            $embedSrc = 'https://www.youtube.com/embed/' . $youtubeId . '?enablejsapi=1';
            return $wrapper .
                '<iframe class="cc-managed-video" data-platform="youtube" data-video-src="' . $embedSrc . '" src="' . $embedSrc . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;border:none;border-radius:10px;"></iframe>' .
                $wrapperEnd;
        }

        // Vimeo
        if (preg_match('/vimeo\.com\/(?:.*#|.*\/)?([0-9]+)/', $trimmedUrl, $matches)) {
            $vimeoId = $matches[1];
            $embedSrc = 'https://player.vimeo.com/video/' . $vimeoId;
            return $wrapper .
                '<iframe class="cc-managed-video" data-platform="vimeo" data-video-src="' . $embedSrc . '" src="' . $embedSrc . '" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;border:none;border-radius:10px;"></iframe>' .
                $wrapperEnd;
        }

        // Instagram Reels / Posts / Videos
        if (str_contains($trimmedUrl, 'instagram.com')) {
            $igCode = null;
            if (preg_match('/(?:p|reel|reels|tv|share\/reel)\/([A-Za-z0-9_-]+)/i', $trimmedUrl, $igMatches)) {
                $igCode = $igMatches[1];
            }

            if (!empty($igCode)) {
                $embedSrc = 'https://www.instagram.com/p/' . $igCode . '/embed/';
                return '<div class="instagram-video-wrapper" style="position:relative; width:100%; height:520px; max-height:85vh; background:#000000; border-radius:10px; overflow:hidden; display:flex; justify-content:center; align-items:center;">' .
                    '<iframe class="cc-managed-video" data-platform="instagram" data-video-src="' . $embedSrc . '" src="' . $embedSrc . '" frameborder="0" scrolling="no" allowtransparency="true" allowfullscreen="true" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" style="width:100%; height:100%; min-height:520px; border:none; border-radius:10px; background:#fff;"></iframe>' .
                    '</div>';
            }

            $cleanIg = rtrim(preg_replace('/[?#].*$/', '', $trimmedUrl), '/');
            $embedSrc = $cleanIg . '/embed/';
            return '<div class="instagram-video-wrapper" style="position:relative; width:100%; height:520px; max-height:85vh; background:#000000; border-radius:10px; overflow:hidden; display:flex; justify-content:center; align-items:center;">' .
                '<iframe class="cc-managed-video" data-platform="instagram" data-video-src="' . e($embedSrc) . '" src="' . e($embedSrc) . '" frameborder="0" scrolling="no" allowtransparency="true" allowfullscreen="true" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" style="width:100%; height:100%; min-height:520px; border:none; border-radius:10px; background:#fff;"></iframe>' .
                '</div>';
        }

        // Facebook Videos
        if (str_contains($trimmedUrl, 'facebook.com') || str_contains($trimmedUrl, 'fb.watch')) {
            $encodedUrl = urlencode($trimmedUrl);
            $embedSrc = 'https://www.facebook.com/plugins/video.php?href=' . $encodedUrl . '&show_text=false&autoplay=false';
            return $wrapper .
                '<iframe class="cc-managed-video" data-platform="facebook" data-video-src="' . e($embedSrc) . '" src="' . e($embedSrc) . '" frameborder="0" allowfullscreen allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" style="position:absolute;top:0;left:0;width:100%;height:100%;border:none;border-radius:10px;"></iframe>' .
                $wrapperEnd;
        }

        // TikTok Videos
        if (str_contains($trimmedUrl, 'tiktok.com')) {
            $ttId = null;
            if (preg_match('/\/video\/(\d+)/', $trimmedUrl, $ttMatches)) {
                $ttId = $ttMatches[1];
            } elseif (preg_match('/\/v\/(\d+)/', $trimmedUrl, $ttMatches)) {
                $ttId = $ttMatches[1];
            } elseif (preg_match('/item_id=(\d+)/', $trimmedUrl, $ttMatches)) {
                $ttId = $ttMatches[1];
            }

            if (!empty($ttId)) {
                $embedSrc = 'https://www.tiktok.com/embed/v2/' . $ttId;
                return '<div class="tiktok-video-wrapper" style="position:relative; width:100%; height:520px; max-height:85vh; background:#000000; border-radius:10px; overflow:hidden; display:flex; justify-content:center; align-items:center;">' .
                    '<iframe class="cc-managed-video" data-platform="tiktok" data-video-src="' . $embedSrc . '" src="' . $embedSrc . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share; fullscreen" allowfullscreen scrolling="no" style="width:100%; height:100%; min-height:520px; border:none; border-radius:10px; background:#000;"></iframe>' .
                    '</div>';
            }

            return '<div class="tiktok-video-wrapper" style="position:relative; width:100%; height:520px; max-height:85vh; background:#000000; border-radius:10px; overflow:hidden; display:flex; justify-content:center; align-items:center;">' .
                '<iframe class="cc-managed-video" data-platform="tiktok" data-video-src="' . e($trimmedUrl) . '" src="' . e($trimmedUrl) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share; fullscreen" allowfullscreen scrolling="no" style="width:100%; height:100%; min-height:520px; border:none; border-radius:10px; background:#000;"></iframe>' .
                '</div>';
        }

        // Local file or direct URL
        $srcUrl = $trimmedUrl;
        if (!str_starts_with($trimmedUrl, 'http://') && !str_starts_with($trimmedUrl, 'https://')) {
            $srcUrl = asset(ltrim($trimmedUrl, '/'));
        }

        return '<div class="video-wrapper" style="position:relative;width:100%;background:#000;border-radius:10px 10px 0 0;overflow:visible;">
                    <video class="cc-managed-video" data-platform="local" controls playsinline preload="metadata" style="width:100%; height:auto; display:block; border-radius:10px 10px 0 0; max-height:450px;">
                        <source src="' . e($srcUrl) . '">
                        Your browser does not support the video tag.
                    </video>
                </div>';
    }
}