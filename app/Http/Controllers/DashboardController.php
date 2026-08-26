<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Services\ImageCompressor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * Calculate profile completion percentage for a given user.
     */
    public static function completionScore($user): int
    {
        $checks = [
            !empty($user->name),
            !empty($user->description),
            !empty($user->profile_image),
            !empty($user->phone),
            !empty($user->country),
            $user->media()->where('type', 'photo')->exists(),
            (
                !empty($user->social_instagram) ||
                !empty($user->social_facebook) ||
                !empty($user->social_tiktok) ||
                !empty($user->social_youtube)
            ),
        ];
        $completed = count(array_filter($checks));
        return (int) round(($completed / count($checks)) * 100);
    }

    public function index()
    {
        $user = auth()->user();
        $completion = self::completionScore($user);
        return view('dashboard.index', [
            'user'       => $user,
            'completion' => $completion,
        ]);
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'country' => 'nullable|string|max:100',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:12288',
        ];

        if ($user->role === 'user') {
            $rules['country'] = 'required|string|max:100';
            $rules['description'] = 'nullable|string';
            $rules['social_instagram'] = 'nullable|url|max:255';
            $rules['social_facebook'] = 'nullable|url|max:255';
            $rules['social_tiktok'] = 'nullable|url|max:255';
            $rules['social_youtube'] = 'nullable|url|max:255';
        }

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:6|confirmed';
            if ($request->filled('current_password')) {
                if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
                    return redirect()->back()->withErrors(['current_password' => 'The current password provided is incorrect.']);
                }
            }
        }

        $request->validate($rules);

        $data = $request->only([
            'name', 'phone', 'country', 'description',
            'social_instagram', 'social_facebook', 'social_tiktok', 'social_youtube'
        ]);

        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        if ($request->hasFile('profile_image')) {
            // Delete old profile image if it exists and is not a default stock URL
            if ($user->profile_image && !str_starts_with($user->profile_image, 'http')) {
                $relativePath = str_replace('/storage/', '', $user->profile_image);
                Storage::disk('public')->delete($relativePath);
            }

            // Compress profile image to web-optimized WebP (800x800 max, 82% quality)
            $path = ImageCompressor::compressAndStore(
                $request->file('profile_image'),
                'profiles',
                800,
                800,
                82,
                'webp'
            );
            $data['profile_image'] = '/storage/' . $path;
        }

        $user->update($data);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function photos()
    {
        $user = auth()->user();
        if ($user->role === 'admin') {
            return redirect()->route('dashboard')->with('info', 'Staff accounts manage administrative controls directly from the Admin Panel.');
        }

        $photos = $user->media()->where('type', 'photo')->latest()->get();

        return view('dashboard.photos', [
            'photos' => $photos
        ]);
    }

    public function storePhoto(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:1000',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:15360', // up to 15MB before compression
        ]);

        if ($request->hasFile('photo')) {
            // Compress portfolio photo to web-optimized WebP (1920x1920 max, 82% quality)
            $path = ImageCompressor::compressAndStore(
                $request->file('photo'),
                'media/photos',
                1920,
                1920,
                82,
                'webp'
            );
            
            auth()->user()->media()->create([
                'type' => 'photo',
                'title' => $request->title,
                'content' => $request->caption,
                'file_path' => '/storage/' . $path,
            ]);

            return redirect()->route('dashboard.photos')->with('success', 'Photo compressed & uploaded successfully.');
        }

        return redirect()->back()->with('error', 'Failed to upload photo.');
    }

    public function deletePhoto($id)
    {
        $photo = auth()->user()->media()->where('type', 'photo')->findOrFail($id);

        // Delete from local disk
        if (!str_starts_with($photo->file_path, 'http')) {
            $relativePath = str_replace('/storage/', '', $photo->file_path);
            Storage::disk('public')->delete($relativePath);
        }

        $photo->delete();

        return redirect()->route('dashboard.photos')->with('success', 'Photo deleted successfully.');
    }

    public function videos()
    {
        $user = auth()->user();
        $videos = $user->media()->where('type', 'video')->latest()->get();

        return view('dashboard.videos', [
            'videos' => $videos
        ]);
    }

    public function storeVideo(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:1000',
        ]);

        // 1. If Video URL (YouTube, Vimeo, TikTok, Direct Link) is provided
        if ($request->filled('video_url')) {
            $request->validate([
                'video_url' => 'required|url|max:500',
            ], [
                'video_url.url' => 'Please enter a valid video URL (e.g. https://www.youtube.com/watch?v=... or direct MP4 link).',
            ]);

            auth()->user()->media()->create([
                'type' => 'video',
                'title' => $request->title,
                'content' => $request->caption,
                'file_path' => $request->video_url,
            ]);

            return redirect()->route('dashboard.videos')->with('success', 'Video link added successfully to your portfolio.');
        }

        // 2. Check if file upload was attempted but failed PHP ini limits before validation
        if ($request->hasFile('video') && !$request->file('video')->isValid()) {
            $errorCode = $request->file('video')->getError();
            if ($errorCode === UPLOAD_ERR_INI_SIZE || $errorCode === UPLOAD_ERR_FORM_SIZE) {
                return redirect()->back()->with('error', 'The video file exceeds the server upload limit. Please upload a smaller clip (under 50MB) or use a YouTube/video link.');
            }
        }

        // 3. File upload validation
        $request->validate([
            'video' => 'required_without:video_url|nullable|file|mimes:mp4,mov,avi,webm,ogg,mkv,3gp,flv,qt|max:51200', // up to 50MB
        ], [
            'video.required_without' => 'Please select a video file to upload or enter a video link.',
            'video.mimes' => 'The video format must be: MP4, MOV, AVI, WEBM, OGG, MKV, or 3GP.',
            'video.max' => 'The video file size cannot exceed 50MB.',
        ]);

        if ($request->hasFile('video')) {
            $path = $request->file('video')->store('media/videos', 'public');

            auth()->user()->media()->create([
                'type' => 'video',
                'title' => $request->title,
                'content' => $request->caption,
                'file_path' => '/storage/' . $path,
            ]);

            return redirect()->route('dashboard.videos')->with('success', 'Video file uploaded successfully.');
        }

        return redirect()->back()->with('error', 'Failed to process video upload. Please try again.');
    }

    public function deleteVideo($id)
    {
        $video = auth()->user()->media()->where('type', 'video')->findOrFail($id);

        // Delete from local disk
        if (!str_starts_with($video->file_path, 'http')) {
            $relativePath = str_replace('/storage/', '', $video->file_path);
            Storage::disk('public')->delete($relativePath);
        }

        $video->delete();

        return redirect()->route('dashboard.videos')->with('success', 'Video deleted successfully.');
    }

    public function news()
    {
        $user = auth()->user();
        $newsItems = $user->media()->where('type', 'news')->latest()->get();

        return view('dashboard.news', [
            'newsItems' => $newsItems
        ]);
    }

    public function storeNews(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        $filePath = null;
        if ($request->hasFile('image')) {
            $path = ImageCompressor::compressAndStore(
                $request->file('image'),
                'media/news',
                1200,
                1200,
                82,
                'webp'
            );
            $filePath = '/storage/' . $path;
        }

        auth()->user()->media()->create([
            'type' => 'news',
            'title' => $request->title,
            'content' => $request->content,
            'file_path' => $filePath ?? '',
        ]);

        return redirect()->route('dashboard.news')->with('success', 'Latest news update published successfully.');
    }

    public function deleteNews($id)
    {
        $news = auth()->user()->media()->where('type', 'news')->findOrFail($id);

        if ($news->file_path && !str_starts_with($news->file_path, 'http')) {
            $relativePath = str_replace('/storage/', '', $news->file_path);
            Storage::disk('public')->delete($relativePath);
        }

        $news->delete();

        return redirect()->route('dashboard.news')->with('success', 'News item deleted successfully.');
    }

    /**
     * Publish the authenticated user's profile.
     * Requires at least 60% completion.
     */
    public function publish()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $completion = self::completionScore($user);

        if ($completion < 60) {
            return redirect()->route('dashboard')
                ->with('error', 'Your profile must be at least 60% complete before publishing. Current: ' . $completion . '%.');
        }

        $user->update(['is_published' => true]);
        return redirect()->route('dashboard')->with('success', 'Your profile is now live and visible to the public!');
    }

    /**
     * Unpublish (hide) the authenticated user's profile.
     */
    public function unpublish()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->update(['is_published' => false]);
        return redirect()->route('dashboard')->with('success', 'Your profile has been hidden from the public directory.');
    }
}
