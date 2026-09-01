<?php

namespace App\Services;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ContentModerationService
{
    /**
     * Prohibited keywords list for text/captions
     */
    protected static array $blockedKeywords = [
        'porn', 'pornography', 'xxx', 'sex', 'nude', 'nudity', 'nsfw',
        'pussy', 'dick', 'vagina', 'penis', 'boobs', 'tits', 'anal',
        'kuma', 'mboo', 'ngono', 'malaya', 'kahaba', 'kufira', 'usherati'
    ];

    /**
     * Inspect image file and metadata for nudity/NSFW content.
     *
     * @param string|\Illuminate\Http\UploadedFile $image
     * @param string|null $title
     * @param string|null $content
     * @return array
     */
    public static function checkImage($image, ?string $title = null, ?string $content = null): array
    {
        // 1. Textual check on title & caption
        $textCheck = self::checkText($title . ' ' . $content);
        if ($textCheck['flagged']) {
            return [
                'flagged' => true,
                'status'  => 'flagged',
                'reason'  => 'Inappropriate or explicit keywords detected in title/caption: ' . $textCheck['matched'],
                'score'   => 0.95,
            ];
        }

        $imagePath = is_string($image) ? $image : $image->getRealPath();

        if (!file_exists($imagePath)) {
            return ['flagged' => false, 'status' => 'approved', 'reason' => null, 'score' => 0.0];
        }

        // 2. Check if Sightengine API is configured
        $apiUser = env('SIGHTENGINE_API_USER') ?: SystemSetting::get('sightengine_api_user');
        $apiSecret = env('SIGHTENGINE_API_SECRET') ?: SystemSetting::get('sightengine_api_secret');

        if (!empty($apiUser) && !empty($apiSecret)) {
            try {
                $response = Http::attach(
                    'media',
                    file_get_contents($imagePath),
                    basename($imagePath)
                )->post('https://api.sightengine.com/1.0/check.json', [
                    'models'     => 'nudity-2.0,wad,offensive',
                    'api_user'   => $apiUser,
                    'api_secret' => $apiSecret,
                ]);

                if ($response->successful()) {
                    $result = $response->json();
                    $nudity = $result['nudity'] ?? [];
                    
                    // High risk nudity metrics
                    $sexualActivity = $nudity['sexual_activity'] ?? 0;
                    $sexualDisplay  = $nudity['sexual_display'] ?? 0;
                    $erotica        = $nudity['erotica'] ?? 0;
                    $rawNudity      = $nudity['raw'] ?? 0;

                    $maxNudityScore = max($sexualActivity, $sexualDisplay, $erotica, $rawNudity);

                    if ($maxNudityScore >= 0.65) {
                        return [
                            'flagged' => true,
                            'status'  => 'flagged',
                            'reason'  => 'Automated AI Moderation: Explicit/Nudity detected (' . round($maxNudityScore * 100) . '% confidence)',
                            'score'   => round($maxNudityScore, 2),
                        ];
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('Sightengine Moderation API Error: ' . $e->getMessage());
            }
        }

        // 3. Built-in Local Heuristic Skin-Tone / Exposure Analysis (Offline Fallback)
        $localAnalysis = self::analyzeSkinExposure($imagePath);
        if ($localAnalysis['flagged']) {
            return [
                'flagged' => true,
                'status'  => 'flagged',
                'reason'  => 'Automated Heuristic Filter: Potential nudity / high skin exposure detected (' . round($localAnalysis['ratio'] * 100) . '%)',
                'score'   => round($localAnalysis['ratio'], 2),
            ];
        }

        return [
            'flagged' => false,
            'status'  => 'approved',
            'reason'  => null,
            'score'   => 0.0,
        ];
    }

    /**
     * Check if a string contains prohibited keywords.
     */
    public static function checkText(?string $text): array
    {
        if (empty($text)) {
            return ['flagged' => false, 'matched' => null];
        }

        $lower = mb_strtolower($text, 'UTF-8');
        foreach (self::$blockedKeywords as $kw) {
            if (preg_match('/\b' . preg_quote($kw, '/') . '\b/i', $lower)) {
                return ['flagged' => true, 'matched' => $kw];
            }
        }

        return ['flagged' => false, 'matched' => null];
    }

    /**
     * Local Skin Exposure Heuristic Analyzer using GD.
     * Evaluates human skin-tone spectrum in RGB + YCbCr space, upper torso concentration,
     * and contiguous bare skin exposure blobs.
     */
    protected static function analyzeSkinExposure(string $filePath): array
    {
        if (!function_exists('imagecreatefromstring')) {
            return ['flagged' => false, 'ratio' => 0.0];
        }

        $contents = @file_get_contents($filePath);
        if (!$contents) {
            return ['flagged' => false, 'ratio' => 0.0];
        }

        $img = @imagecreatefromstring($contents);
        if (!$img) {
            return ['flagged' => false, 'ratio' => 0.0];
        }

        // Resize down to 80x80 for instant, accurate matrix analysis
        $w = 80;
        $h = 80;
        $thumb = imagecreatetruecolor($w, $h);
        imagecopyresampled($thumb, $img, 0, 0, 0, 0, $w, $h, imagesx($img), imagesy($img));

        $skinMap = array_fill(0, $w, array_fill(0, $h, 0));
        $skinPixels = 0;
        $upperTorsoSkin = 0;
        $upperTorsoTotal = $w * (int)($h * 0.7);

        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                $rgb = imagecolorat($thumb, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                // 1. RGB Skin Rule
                $rgbSkin = ($r > 95 && $g > 40 && $b > 20 &&
                            (max($r, $g, $b) - min($r, $g, $b)) > 15 &&
                            abs($r - $g) > 15 &&
                            $r > $g && $r > $b);

                // 2. YCbCr Standard Range (Fair, Olive, Tan)
                $yVal  =  0.299 * $r + 0.587 * $g + 0.114 * $b;
                $cb = -0.169 * $r - 0.331 * $g + 0.500 * $b + 128;
                $cr =  0.500 * $r - 0.419 * $g - 0.081 * $b + 128;
                $ycbcrSkin = ($cb >= 77 && $cb <= 127 && $cr >= 133 && $cr <= 173);

                // 3. Darker / African Skin Tone YCbCr Range
                $darkSkin = ($yVal >= 35 && $cb >= 80 && $cb <= 135 && $cr >= 130 && $cr <= 180 && $r >= $g && $g >= $b);

                if ($rgbSkin || $ycbcrSkin || $darkSkin) {
                    $skinMap[$x][$y] = 1;
                    $skinPixels++;
                    if ($y < $h * 0.7) {
                        $upperTorsoSkin++;
                    }
                }
            }
        }

        imagedestroy($thumb);
        imagedestroy($img);

        $totalPixels = $w * $h;
        $totalRatio = $skinPixels / $totalPixels;
        $torsoRatio = $upperTorsoTotal > 0 ? ($upperTorsoSkin / $upperTorsoTotal) : 0;

        // BFS: Find largest contiguous bare skin cluster
        $visited = array_fill(0, $w, array_fill(0, $h, false));
        $maxBlob = 0;

        for ($x = 0; $x < $w; $x++) {
            for ($y = 0; $y < $h; $y++) {
                if ($skinMap[$x][$y] === 1 && !$visited[$x][$y]) {
                    $blobSize = 0;
                    $queue = [[$x, $y]];
                    $visited[$x][$y] = true;

                    while (!empty($queue)) {
                        [$cx, $cy] = array_pop($queue);
                        $blobSize++;

                        $neighbors = [
                            [$cx - 1, $cy], [$cx + 1, $cy],
                            [$cx, $cy - 1], [$cx, $cy + 1]
                        ];

                        foreach ($neighbors as [$nx, $ny]) {
                            if ($nx >= 0 && $nx < $w && $ny >= 0 && $ny < $h) {
                                if ($skinMap[$nx][$ny] === 1 && !$visited[$nx][$ny]) {
                                    $visited[$nx][$ny] = true;
                                    $queue[] = [$nx, $ny];
                                }
                            }
                        }
                    }

                    if ($blobSize > $maxBlob) {
                        $maxBlob = $blobSize;
                    }
                }
            }
        }

        $blobRatio = $maxBlob / $totalPixels;

        // Trigger flag if total skin ratio >= 35%, upper torso >= 40%, or contiguous blob >= 28%
        if ($totalRatio >= 0.35 || $torsoRatio >= 0.40 || $blobRatio >= 0.28) {
            return [
                'flagged' => true,
                'ratio'   => max($totalRatio, $torsoRatio, $blobRatio)
            ];
        }

        return ['flagged' => false, 'ratio' => $totalRatio];
    }
}
