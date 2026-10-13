<?php

namespace App\Http\Controllers;

use App\Models\SiteNews;
use App\Models\SiteNewsLike;
use App\Models\SiteNewsShare;
use App\Models\SystemSetting;
use App\Models\UserActivityLog;
use App\Services\ImageCompressor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SiteNewsController extends Controller
{
    /**
     * Helper to get or generate device fingerprint token
     */
    private function getDeviceFingerprint(Request $request): string
    {
        $fingerprint = $request->cookie('device_token');
        if (!$fingerprint) {
            $fingerprint = md5($request->ip() . ($request->header('User-Agent') ?? '') . 'chap_device');
            cookie()->queue('device_token', $fingerprint, 525600);
        }
        return $fingerprint;
    }

    /**
     * Blend a collection of media posts with active Site News based on the configured interval.
     * Each active news item appears only once per page load.
     */
    public static function buildFeedWithNews($mediaItems, int $offset = 0): array
    {
        $interval = (int) SystemSetting::get('site_news_interval', 5);
        if ($interval <= 0) {
            $interval = 5;
        }

        $activeNews = SiteNews::active()
            ->with('user')
            ->withCount(['likes', 'shares'])
            ->orderByDesc('priority')
            ->orderByDesc('created_at')
            ->get();

        $feed = [];
        foreach ($mediaItems->values() as $i => $media) {
            $feed[] = [
                'type' => 'media',
                'data' => $media,
            ];

            $globalPos = $offset + $i + 1;
            if ($globalPos % $interval === 0 && $activeNews->isNotEmpty()) {
                $newsIndex = intdiv($globalPos, $interval) - 1;
                if (isset($activeNews[$newsIndex])) {
                    $feed[] = [
                        'type' => 'news',
                        'data' => $activeNews[$newsIndex],
                    ];
                }
            }
        }

        return $feed;
    }

    /**
     * Store new Site News post.
     */
    public function store(Request $request)
    {
        $type = $request->input('type', 'text');

        $rules = [
            'type' => 'required|in:text,photo,video,text_photo,text_video',
            'priority' => 'nullable|integer|min:0|max:999',
            'expires_at' => 'nullable|date',
        ];

        if (in_array($type, ['text', 'text_photo', 'text_video'])) {
            $rules['title'] = 'required|string|max:255';
            $rules['body'] = 'required|string';
        } else {
            $rules['title'] = 'nullable|string|max:255';
            $rules['body'] = 'nullable|string';
        }

        if (in_array($type, ['photo', 'text_photo'])) {
            $rules['image'] = 'required|image|mimes:jpeg,png,jpg,webp,gif|max:10240';
        }

        if (in_array($type, ['video', 'text_video'])) {
            $videoSourceType = $request->input('video_source_type', 'upload');
            if ($videoSourceType === 'link') {
                $rules['video_url'] = 'required|url|max:500';
            } else {
                $rules['video_file'] = 'required|mimes:mp4,mov,webm,ogg|max:102400';
            }
        }

        $request->validate($rules);

        $imagePath = null;
        if ($request->hasFile('image')) {
            try {
                if (class_exists(ImageCompressor::class)) {
                    $compressed = ImageCompressor::compressAndStore(
                        $request->file('image'),
                        'site-news/images',
                        1200,
                        1200,
                        82,
                        'webp'
                    );
                    $imagePath = '/storage/' . $compressed;
                } else {
                    $path = $request->file('image')->store('site-news/images', 'public');
                    $imagePath = '/storage/' . $path;
                }
            } catch (\Exception $e) {
                $path = $request->file('image')->store('site-news/images', 'public');
                $imagePath = '/storage/' . $path;
            }
        }

        $videoPath = null;
        $videoUrl = null;
        if (in_array($type, ['video', 'text_video'])) {
            if ($request->input('video_source_type') === 'link') {
                $videoUrl = trim($request->input('video_url'));
            } elseif ($request->hasFile('video_file')) {
                $file = $request->file('video_file');
                $filename = 'news_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('site-news/videos', $filename, 'public');
                $videoPath = '/storage/' . $path;
            }
        }

        $user = auth()->user();

        $news = SiteNews::create([
            'user_id' => $user ? $user->id : null,
            'type' => $type,
            'title' => $request->title,
            'body' => $request->body,
            'image_path' => $imagePath,
            'video_path' => $videoPath,
            'video_url' => $videoUrl,
            'priority' => (int) $request->input('priority', 0),
            'expires_at' => $request->expires_at,
            'is_published' => true,
        ]);

        if (class_exists(UserActivityLog::class)) {
            UserActivityLog::log(
                'CREATE',
                ($user ? $user->name : 'Staff') . " published new Site News ({$type}): \"{$news->title}\"",
                ['news_id' => $news->id]
            );
        }

        return redirect()->back()->with('success', __('Site news post created and published successfully!'));
    }

    /**
     * Update existing Site News post.
     */
    public function update(Request $request, $id)
    {
        $news = SiteNews::findOrFail($id);
        $type = $news->type;

        $rules = [
            'priority' => 'nullable|integer|min:0|max:999',
            'expires_at' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:10240',
            'video_file' => 'nullable|mimes:mp4,mov,webm,ogg|max:102400',
            'video_url' => 'nullable|url|max:500',
        ];

        if (in_array($type, ['text', 'text_photo', 'text_video'])) {
            $rules['title'] = 'required|string|max:255';
            $rules['body'] = 'required|string';
        } else {
            $rules['title'] = 'nullable|string|max:255';
            $rules['body'] = 'nullable|string';
        }

        $request->validate($rules);

        $data = [
            'title' => $request->title,
            'body' => $request->body,
            'priority' => (int) $request->input('priority', $news->priority),
            'expires_at' => $request->expires_at,
        ];

        if ($request->hasFile('image')) {
            try {
                if (class_exists(ImageCompressor::class)) {
                    $compressed = ImageCompressor::compressAndStore(
                        $request->file('image'),
                        'site-news/images',
                        1200,
                        1200,
                        82,
                        'webp'
                    );
                    $data['image_path'] = '/storage/' . $compressed;
                } else {
                    $path = $request->file('image')->store('site-news/images', 'public');
                    $data['image_path'] = '/storage/' . $path;
                }
            } catch (\Exception $e) {
                $path = $request->file('image')->store('site-news/images', 'public');
                $data['image_path'] = '/storage/' . $path;
            }
        }

        if ($request->input('video_source_type') === 'link' && $request->filled('video_url')) {
            $data['video_url'] = trim($request->input('video_url'));
            $data['video_path'] = null;
        } elseif ($request->hasFile('video_file')) {
            $file = $request->file('video_file');
            $filename = 'news_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('site-news/videos', $filename, 'public');
            $data['video_path'] = '/storage/' . $path;
            $data['video_url'] = null;
        }

        $news->update($data);

        return redirect()->back()->with('success', __('Site news post updated successfully!'));
    }

    /**
     * Toggle published state.
     */
    public function toggle($id)
    {
        $news = SiteNews::findOrFail($id);
        $news->is_published = !$news->is_published;
        $news->save();

        return redirect()->back()->with('success', __('Site news status updated!'));
    }

    /**
     * Delete news post.
     */
    public function destroy($id)
    {
        $news = SiteNews::findOrFail($id);
        $news->delete();

        return redirect()->back()->with('success', __('Site news deleted successfully!'));
    }

    /**
     * Update interval setting (Admin only).
     */
    public function updateInterval(Request $request)
    {
        $request->validate([
            'site_news_interval' => 'required|integer|min:1|max:50',
        ]);

        SystemSetting::set('site_news_interval', (int) $request->site_news_interval);

        return redirect()->back()->with('success', __('News display interval updated successfully!'));
    }

    /**
     * Toggle like for site news (Public).
     */
    public function toggleLike(Request $request, $id)
    {
        $news = SiteNews::findOrFail($id);
        $userId = Auth::id();
        $ip = $request->ip();
        $deviceFingerprint = $this->getDeviceFingerprint($request);

        $query = SiteNewsLike::where('site_news_id', $id);
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where(function ($q) use ($ip, $deviceFingerprint) {
                $q->where('ip_address', $ip);
                if ($deviceFingerprint) {
                    $q->orWhere('device_fingerprint', $deviceFingerprint);
                }
            })->whereNull('user_id');
        }

        $existing = $query->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            SiteNewsLike::create([
                'site_news_id' => $id,
                'user_id' => $userId,
                'ip_address' => $ip,
                'device_fingerprint' => $deviceFingerprint,
            ]);
            $liked = true;
        }

        $count = SiteNewsLike::where('site_news_id', $id)->count();

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'count' => $count,
        ]);
    }

    /**
     * Record share for site news (Public).
     */
    public function recordShare(Request $request, $id)
    {
        $news = SiteNews::findOrFail($id);
        $userId = Auth::id();
        $ip = $request->ip();
        $deviceFingerprint = $this->getDeviceFingerprint($request);
        $platform = $request->input('platform', 'general');

        SiteNewsShare::create([
            'site_news_id' => $id,
            'user_id' => $userId,
            'platform' => substr($platform, 0, 50),
            'ip_address' => $ip,
            'device_fingerprint' => $deviceFingerprint,
        ]);

        $count = SiteNewsShare::where('site_news_id', $id)->count();

        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }

    /**
     * Record view impression (Public).
     */
    public function recordView(Request $request, $id)
    {
        $sessionKey = 'viewed_site_news_' . $id;
        if (!session()->has($sessionKey)) {
            session()->put($sessionKey, true);
            $news = SiteNews::find($id);
            if ($news) {
                $news->increment('views_count');
                return response()->json(['success' => true, 'views_count' => $news->views_count]);
            }
        }

        return response()->json(['success' => true]);
    }
}
