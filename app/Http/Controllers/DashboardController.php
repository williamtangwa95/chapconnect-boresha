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
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $user->loadCount(['likesReceived', 'followersReceived', 'commentsReceived']);
        $completion = self::completionScore($user);

        // Package limits and usage metrics
        $packageDetails = $user->currentPackageDetails();
        $usage = [
            'images_used' => $user->media()->where('type', 'photo')->count(),
            'videos_used' => $user->media()->where('type', 'video')->count(),
            'news_used' => $user->media()->where('type', 'news')->count(),
        ];

        $myInvoices = $user->invoices()->latest()->get();

        $paymentSettings = [
            'payment_likes_required' => intval(\App\Models\SystemSetting::get('payment_likes_required', 100)),
            'payment_followers_required' => intval(\App\Models\SystemSetting::get('payment_followers_required', 50)),
            'payment_comments_required' => intval(\App\Models\SystemSetting::get('payment_comments_required', 20)),
            'payment_views_required' => intval(\App\Models\SystemSetting::get('payment_views_required', 500)),
            'payment_amount' => floatval(\App\Models\SystemSetting::get('payment_amount', 10000.00)),
        ];

        $paymentRequest = \App\Models\TalentPaymentRequest::where('user_id', $user->id)->first();

        return view('dashboard.index', [
            'user'       => $user,
            'completion' => $completion,
            'packageDetails' => $packageDetails,
            'usage' => $usage,
            'myInvoices' => $myInvoices,
            'paymentSettings' => $paymentSettings,
            'paymentRequest' => $paymentRequest,
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

        // Social media account link validation rules
        $rules['social_instagram'] = [
            'nullable',
            'url',
            'max:255',
            function ($attribute, $value, $fail) {
                if ($value) {
                    $host = strtolower(parse_url($value, PHP_URL_HOST) ?? '');
                    $allowed = ['instagram.com', 'www.instagram.com', 'instagr.am', 'www.instagr.am', 'm.instagram.com'];
                    if (!in_array($host, $allowed, true)) {
                        $fail('The Instagram link must be a valid Instagram URL (e.g. https://instagram.com/username).');
                    }
                }
            },
        ];

        $rules['social_facebook'] = [
            'nullable',
            'url',
            'max:255',
            function ($attribute, $value, $fail) {
                if ($value) {
                    $host = strtolower(parse_url($value, PHP_URL_HOST) ?? '');
                    $allowed = ['facebook.com', 'www.facebook.com', 'fb.com', 'www.fb.com', 'm.facebook.com', 'web.facebook.com', 'fb.watch'];
                    if (!in_array($host, $allowed, true)) {
                        $fail('The Facebook link must be a valid Facebook URL (e.g. https://facebook.com/page).');
                    }
                }
            },
        ];

        $rules['social_tiktok'] = [
            'nullable',
            'url',
            'max:255',
            function ($attribute, $value, $fail) {
                if ($value) {
                    $host = strtolower(parse_url($value, PHP_URL_HOST) ?? '');
                    $allowed = ['tiktok.com', 'www.tiktok.com', 'vm.tiktok.com', 'm.tiktok.com', 'vt.tiktok.com'];
                    if (!in_array($host, $allowed, true)) {
                        $fail('The TikTok link must be a valid TikTok URL (e.g. https://tiktok.com/@username).');
                    }
                }
            },
        ];

        $rules['social_youtube'] = [
            'nullable',
            'url',
            'max:255',
            function ($attribute, $value, $fail) {
                if ($value) {
                    $host = strtolower(parse_url($value, PHP_URL_HOST) ?? '');
                    $allowed = ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'music.youtube.com', 'youtu.be', 'www.youtu.be'];
                    if (!in_array($host, $allowed, true)) {
                        $fail('The YouTube link must be a valid YouTube URL (e.g. https://youtube.com/channel).');
                    }
                }
            },
        ];

        if ($user->role === 'user') {
            $rules['country'] = 'required|string|max:100';
            $rules['description'] = 'nullable|string';
        }

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:6|confirmed';
            if ($request->filled('current_password')) {
                if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
                    return redirect()->back()->withErrors(['current_password' => 'The current password provided is incorrect.']);
                }
            }
        }

        $request->validate($rules, [
            'social_instagram.url' => 'Please enter a valid Instagram URL including http:// or https://.',
            'social_facebook.url'  => 'Please enter a valid Facebook URL including http:// or https://.',
            'social_tiktok.url'    => 'Please enter a valid TikTok URL including http:// or https://.',
            'social_youtube.url'   => 'Please enter a valid YouTube URL including http:// or https://.',
        ]);

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
        @ini_set('memory_limit', '512M');

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $limits = $user->currentPackageDetails();
        $photoCount = $user->media()->where('type', 'photo')->count();
        if ($photoCount >= $limits['max_images']) {
            return redirect()->back()->withErrors(['photo' => "You have used {$photoCount} of {$limits['max_images']} allowed images. Upgrade your package to upload more images."]);
        }

        // Catch POST body overflow (post_max_size exceeded)
        if ($request->isMethod('post') && empty($_POST) && empty($_FILES) && isset($_SERVER['CONTENT_LENGTH']) && (int)$_SERVER['CONTENT_LENGTH'] > 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['photo' => 'The uploaded photo file is too large and exceeded the server payload limit. Please select an image under 15MB.']);
        }

        // Check if PHP upload limits were hit before Laravel validation
        if ($request->file('photo')) {
            $photoFile = $request->file('photo');
            if (!$photoFile->isValid()) {
                $errorCode = $photoFile->getError();
                if ($errorCode === UPLOAD_ERR_INI_SIZE || $errorCode === UPLOAD_ERR_FORM_SIZE) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['photo' => 'The photo file exceeds the server upload limit. Please select an image under 15MB.']);
                }
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['photo' => 'Photo upload failed (' . $photoFile->getErrorMessage() . '). Please select another image.']);
            }
        }

        $request->validate([
            'title'   => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:1000',
            'photo'   => 'required|file|image|mimes:jpeg,png,jpg,gif,webp,heic,heif,bmp|max:15360',
        ], [
            'photo.required' => 'Please select an image file to upload.',
            'photo.image'    => 'The file must be a valid image (JPEG, PNG, JPG, GIF, WEBP, or HEIC).',
            'photo.mimes'    => 'The photo must be a file of type: jpeg, png, jpg, gif, webp, heic, heif, bmp.',
            'photo.max'      => 'The photo file size cannot exceed 15MB.',
            'photo.uploaded' => 'The photo failed to upload. The image file may exceed server upload limits or was interrupted.',
        ]);

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
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
                'type'      => 'photo',
                'title'     => $request->title,
                'content'   => $request->caption,
                'file_path' => '/storage/' . $path,
            ]);

            return redirect()->route('dashboard.photos')->with('success', 'Photo compressed & uploaded successfully.');
        }

        return redirect()->back()->withInput()->withErrors(['photo' => 'Failed to upload photo. Please select a valid image file.']);
    }

    public function updatePhoto(Request $request, $id)
    {
        @ini_set('memory_limit', '512M');
        $photo = auth()->user()->media()->where('type', 'photo')->findOrFail($id);

        if ($request->isMethod('post') && empty($_POST) && empty($_FILES) && isset($_SERVER['CONTENT_LENGTH']) && (int)$_SERVER['CONTENT_LENGTH'] > 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['photo' => 'The uploaded photo file is too large and exceeded server payload limits. Please select an image under 15MB.']);
        }

        if ($request->file('photo')) {
            $photoFile = $request->file('photo');
            if (!$photoFile->isValid()) {
                $errorCode = $photoFile->getError();
                if ($errorCode === UPLOAD_ERR_INI_SIZE || $errorCode === UPLOAD_ERR_FORM_SIZE) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['photo' => 'The photo file exceeds the server upload limit. Please select an image under 15MB.']);
                }
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['photo' => 'Photo upload failed (' . $photoFile->getErrorMessage() . '). Please select another image file.']);
            }
        }

        $request->validate([
            'title'   => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:1000',
            'photo'   => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp,heic,heif,bmp|max:15360',
        ], [
            'photo.image' => 'The file must be a valid image format.',
            'photo.mimes' => 'The photo must be a file of type: jpeg, png, jpg, gif, webp, heic, heif, bmp.',
            'photo.max'   => 'The photo file size cannot exceed 15MB.',
        ]);

        $data = [
            'title'   => $request->title,
            'content' => $request->caption,
        ];

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            if ($photo->file_path && !str_starts_with($photo->file_path, 'http')) {
                $relativePath = str_replace('/storage/', '', $photo->file_path);
                Storage::disk('public')->delete($relativePath);
            }

            $path = ImageCompressor::compressAndStore(
                $request->file('photo'),
                'media/photos',
                1920,
                1920,
                82,
                'webp'
            );
            $data['file_path'] = '/storage/' . $path;
        }

        $photo->update($data);

        return redirect()->route('dashboard.photos')->with('success', 'Photo details updated successfully.');
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
        @ini_set('memory_limit', '512M');
        @ini_set('max_execution_time', '300');

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $limits = $user->currentPackageDetails();
        $videoCount = $user->media()->where('type', 'video')->count();
        if ($videoCount >= $limits['max_videos']) {
            return redirect()->back()->withErrors(['video' => "You have used {$videoCount} of {$limits['max_videos']} allowed videos. Upgrade your package to upload more videos."]);
        }

        // Catch POST body overflow (post_max_size exceeded)
        if ($request->isMethod('post') && empty($_POST) && empty($_FILES) && isset($_SERVER['CONTENT_LENGTH']) && (int)$_SERVER['CONTENT_LENGTH'] > 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['video' => 'The video file is too large and exceeded server payload limits. Please upload a clip under 50MB or embed a YouTube link.']);
        }

        $request->validate([
            'title'   => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:1000',
        ]);

        // 1. If Video URL is provided (must be YouTube)
        if ($request->filled('video_url')) {
            $cleaned = \App\Helpers\VideoHelper::cleanUrl($request->input('video_url'));
            $request->merge(['video_url' => $cleaned]);

            $request->validate([
                'video_url' => [
                    'required',
                    'url',
                    'max:500',
                    function ($attribute, $value, $fail) {
                        $host = strtolower(parse_url($value, PHP_URL_HOST) ?? '');
                        $allowed = ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'music.youtube.com', 'youtu.be', 'www.youtu.be'];
                        if (!in_array($host, $allowed, true)) {
                            $fail('The video URL must be a valid YouTube link (e.g. https://www.youtube.com/watch?v=... or https://youtu.be/...). No other video platform links are allowed.');
                        }
                    },
                ],
            ], [
                'video_url.required' => 'Please enter a YouTube video URL link.',
                'video_url.url'      => 'Please enter a valid YouTube video URL (e.g. https://www.youtube.com/watch?v=... or https://youtu.be/...).',
            ]);

            auth()->user()->media()->create([
                'type'      => 'video',
                'title'     => $request->title,
                'content'   => $request->caption,
                'file_path' => $request->video_url,
            ]);

            return redirect()->route('dashboard.videos')->with('success', 'YouTube video link added successfully to your portfolio.');
        }

        // 2. Check if video file upload was attempted but failed PHP ini limits before validation
        if ($request->file('video')) {
            $videoFile = $request->file('video');
            if (!$videoFile->isValid()) {
                $errorCode = $videoFile->getError();
                if ($errorCode === UPLOAD_ERR_INI_SIZE || $errorCode === UPLOAD_ERR_FORM_SIZE) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['video' => 'The video file exceeds the server upload limit. Please upload a smaller clip (under 50MB) or use a YouTube video link.']);
                }
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['video' => 'Video upload failed: ' . $videoFile->getErrorMessage() . '. Please try another file or use a YouTube video link.']);
            }
        }

        // 3. File upload validation
        $request->validate([
            'video' => 'required_without:video_url|nullable|file|mimes:mp4,mov,avi,webm,ogg,mkv,3gp,flv,qt|max:51200', // up to 50MB
        ], [
            'video.required_without' => 'Please select a video file to upload or enter a YouTube video link.',
            'video.file'             => 'The selected video file is invalid.',
            'video.mimes'            => 'The video format must be: MP4, MOV, AVI, WEBM, OGG, MKV, or 3GP.',
            'video.max'              => 'The video file size cannot exceed 50MB.',
            'video.uploaded'         => 'The video file failed to upload. Please ensure the clip is under 50MB or use a YouTube video link.',
        ]);

        if ($request->hasFile('video') && $request->file('video')->isValid()) {
            $path = $request->file('video')->store('media/videos', 'public');

            auth()->user()->media()->create([
                'type'      => 'video',
                'title'     => $request->title,
                'content'   => $request->caption,
                'file_path' => '/storage/' . $path,
            ]);

            return redirect()->route('dashboard.videos')->with('success', 'Video file uploaded successfully.');
        }

        return redirect()->back()->withInput()->withErrors(['video' => 'Failed to process video upload. Please select a valid video file or enter a YouTube video link.']);
    }

    public function updateVideo(Request $request, $id)
    {
        @ini_set('memory_limit', '512M');
        @ini_set('max_execution_time', '300');
        $video = auth()->user()->media()->where('type', 'video')->findOrFail($id);

        if ($request->isMethod('post') && empty($_POST) && empty($_FILES) && isset($_SERVER['CONTENT_LENGTH']) && (int)$_SERVER['CONTENT_LENGTH'] > 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['video' => 'The uploaded video file is too large and exceeded server payload limits. Please upload a clip under 50MB.']);
        }

        $request->validate([
            'title'   => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:1000',
        ]);

        $data = [
            'title'   => $request->title,
            'content' => $request->caption,
        ];

        if ($request->filled('video_url')) {
            $cleaned = \App\Helpers\VideoHelper::cleanUrl($request->input('video_url'));
            $request->merge(['video_url' => $cleaned]);

            $request->validate([
                'video_url' => [
                    'required',
                    'url',
                    'max:500',
                    function ($attribute, $value, $fail) {
                        $host = strtolower(parse_url($value, PHP_URL_HOST) ?? '');
                        $allowed = ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'music.youtube.com', 'youtu.be', 'www.youtu.be'];
                        if (!in_array($host, $allowed, true)) {
                            $fail('The video URL must be a valid YouTube link (e.g. https://www.youtube.com/watch?v=... or https://youtu.be/...). No other video platform links are allowed.');
                        }
                    },
                ],
            ], [
                'video_url.required' => 'Please enter a YouTube video URL link.',
                'video_url.url'      => 'Please enter a valid YouTube video URL (e.g. https://www.youtube.com/watch?v=... or https://youtu.be/...).',
            ]);

            if ($video->file_path && !str_starts_with($video->file_path, 'http')) {
                $relativePath = str_replace('/storage/', '', $video->file_path);
                Storage::disk('public')->delete($relativePath);
            }

            $data['file_path'] = $request->video_url;
        }

        if ($request->file('video')) {
            $videoFile = $request->file('video');
            if (!$videoFile->isValid()) {
                $errorCode = $videoFile->getError();
                if ($errorCode === UPLOAD_ERR_INI_SIZE || $errorCode === UPLOAD_ERR_FORM_SIZE) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['video' => 'The video file exceeds the server upload limit. Please select a clip under 50MB.']);
                }
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['video' => 'Video upload failed: ' . $videoFile->getErrorMessage()]);
            }

            $request->validate([
                'video' => 'nullable|file|mimes:mp4,mov,avi,webm,ogg,mkv,3gp,flv,qt|max:51200',
            ], [
                'video.mimes' => 'The video format must be: MP4, MOV, AVI, WEBM, OGG, MKV, or 3GP.',
                'video.max'   => 'The video file size cannot exceed 50MB.',
            ]);

            if ($videoFile->isValid()) {
                if ($video->file_path && !str_starts_with($video->file_path, 'http')) {
                    $relativePath = str_replace('/storage/', '', $video->file_path);
                    Storage::disk('public')->delete($relativePath);
                }

                $path = $videoFile->store('media/videos', 'public');
                $data['file_path'] = '/storage/' . $path;
            }
        }

        $video->update($data);

        return redirect()->route('dashboard.videos')->with('success', 'Video details updated successfully.');
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
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $limits = $user->currentPackageDetails();
        $newsCount = $user->media()->where('type', 'news')->count();
        if ($newsCount >= $limits['max_news']) {
            return redirect()->back()->withErrors(['title' => "You have used {$newsCount} of {$limits['max_news']} allowed news articles. Upgrade your package to publish more news."]);
        }

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

    public function updateNews(Request $request, $id)
    {
        @ini_set('memory_limit', '512M');
        $news = auth()->user()->media()->where('type', 'news')->findOrFail($id);

        if ($request->isMethod('post') && empty($_POST) && empty($_FILES) && isset($_SERVER['CONTENT_LENGTH']) && (int)$_SERVER['CONTENT_LENGTH'] > 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['image' => 'The uploaded banner image is too large and exceeded server payload limits.']);
        }

        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ], [
            'title.required'   => 'Please provide a title for the news article.',
            'content.required' => 'Please enter the content details for the news article.',
            'image.image'      => 'The cover file must be a valid image.',
            'image.max'        => 'The image file size cannot exceed 10MB.',
        ]);

        $data = [
            'title'   => $request->title,
            'content' => $request->content,
        ];

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($news->file_path && !str_starts_with($news->file_path, 'http')) {
                $relativePath = str_replace('/storage/', '', $news->file_path);
                Storage::disk('public')->delete($relativePath);
            }

            $path = ImageCompressor::compressAndStore(
                $request->file('image'),
                'media/news',
                1200,
                1200,
                82,
                'webp'
            );
            $data['file_path'] = '/storage/' . $path;
        }

        $news->update($data);

        return redirect()->route('dashboard.news')->with('success', 'News article updated successfully.');
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

    /**
     * View and print invoice details safely.
     */
    public function printInvoice($id)
    {
        $invoice = \App\Models\Invoice::with('user')->findOrFail($id);

        if (auth()->user()->role !== 'admin' && $invoice->user_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this invoice.');
        }

        return view('dashboard.invoice', compact('invoice'));
    }

    /**
     * View and manage comments received by authenticated user.
     */
    public function comments()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $comments = $user->commentsReceived()
            ->whereNull('parent_id')
            ->with(['replies.user', 'user'])
            ->latest()
            ->get();

        $totalComments = $user->commentsReceived()->count();
        $totalTopLevel = $comments->count();
        $totalReplies = $totalComments - $totalTopLevel;

        return view('dashboard.comments', [
            'comments' => $comments,
            'totalComments' => $totalComments,
            'totalTopLevel' => $totalTopLevel,
            'totalReplies' => $totalReplies,
        ]);
    }

    public function requestPayment(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if ($user->role !== 'user') {
            return redirect()->back()->with('error', 'Only talent accounts are eligible to request payments.');
        }

        if ($user->hasBeenPaid()) {
            return redirect()->back()->with('error', 'You have already received a payment and cannot request again.');
        }

        $existingPending = \App\Models\TalentPaymentRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        if ($existingPending) {
            return redirect()->back()->with('error', 'You already have a pending payment request.');
        }

        $likesCount = $user->likesReceived()->count();
        $followersCount = $user->followersReceived()->count();
        $commentsCount = $user->commentsReceived()->count();
        $viewsCount = $user->views_count;

        $likesRequired = intval(\App\Models\SystemSetting::get('payment_likes_required', 100));
        $followersRequired = intval(\App\Models\SystemSetting::get('payment_followers_required', 50));
        $commentsRequired = intval(\App\Models\SystemSetting::get('payment_comments_required', 20));
        $viewsRequired = intval(\App\Models\SystemSetting::get('payment_views_required', 500));
        $amount = floatval(\App\Models\SystemSetting::get('payment_amount', 10000.00));

        if ($likesCount < $likesRequired ||
            $followersCount < $followersRequired ||
            $commentsCount < $commentsRequired ||
            $viewsCount < $viewsRequired) {
            return redirect()->back()->with('error', 'You do not meet the minimum criteria to request payment yet.');
        }

        $paymentRequest = \App\Models\TalentPaymentRequest::create([
            'user_id' => $user->id,
            'likes_count' => $likesCount,
            'followers_count' => $followersCount,
            'comments_count' => $commentsCount,
            'views_count' => $viewsCount,
            'amount' => $amount,
            'status' => 'pending',
        ]);

        $staffMembers = \App\Models\User::whereIn('role', ['admin', 'customer_care'])->get();
        foreach ($staffMembers as $staff) {
            \App\Models\Notification::create([
                'user_id' => $staff->id,
                'type' => 'new_payment_request',
                'title' => '💰 New Talent Payment Request',
                'message' => "Talent '{$user->name}' has requested a payout of " . number_format($amount, 2) . " TZS.",
                'link' => $staff->role === 'admin' 
                    ? route('admin.dashboard') . '#payments' 
                    : route('customer-care.dashboard') . '#payments',
            ]);
        }

        \App\Models\UserActivityLog::log('CREATED', "Talent '{$user->name}' requested payout of " . number_format($amount, 2) . " TZS.", [
            'likes_count' => $likesCount,
            'followers_count' => $followersCount,
            'comments_count' => $commentsCount,
            'views_count' => $viewsCount,
            'amount' => $amount,
        ], $user->id, 'TalentPaymentRequest', $paymentRequest->id);

        return redirect()->back()->with('success', 'Your payment request has been submitted successfully.');
    }
}
