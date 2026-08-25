<?php

namespace App\Helpers;

class VideoHelper
{
    /**
     * Helper to render video player HTML for local files or YouTube/Vimeo/external URLs.
     */
    public static function renderEmbed(?string $url, string $extraClass = ''): string
    {
        if (empty($url)) {
            return '';
        }

        // YouTube URL matching (youtube.com, youtu.be, shorts)
        if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=|shorts\/)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches)) {
            $youtubeId = $matches[1];
            return '<div class="video-container" style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:10px;background:#000;">
                        <iframe src="https://www.youtube.com/embed/' . $youtubeId . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;border:none;border-radius:10px;"></iframe>
                    </div>';
        }

        // Vimeo URL matching
        if (preg_match('/vimeo\.com\/(?:.*#|.*\/)?([0-9]+)/', $url, $matches)) {
            $vimeoId = $matches[1];
            return '<div class="video-container" style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;border-radius:10px;background:#000;">
                        <iframe src="https://player.vimeo.com/video/' . $vimeoId . '" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen style="position:absolute;top:0;left:0;width:100%;height:100%;border:none;border-radius:10px;"></iframe>
                    </div>';
        }

        // Default HTML5 video player for local files or direct MP4/WEBM URLs
        return '<div class="video-wrapper" style="position:relative;width:100%;overflow:hidden;border-radius:10px;background:#000;">
                    <video controls preload="metadata" style="width:100%; max-height: 450px; display:block; border-radius:10px;">
                        <source src="' . e($url) . '">
                        Your browser does not support the video tag.
                    </video>
                </div>';
    }
}
