<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Notification;
use App\Models\User;
use App\Services\ImageCompressor;
use App\Services\ContentModerationService;
use App\Helpers\PhoneHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

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

        // Ensure talent user has standard package & invoice setup
        if ($user && $user->role === 'user') {
            $user->ensureStandardPackageAndInvoice();
        }

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

    public function profile()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        return view('dashboard.profile', [
            'user' => $user,
        ]);
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:30',
            'country' => 'nullable|string|max:100',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:102400',
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

        if ($request->filled('phone')) {
            if (!PhoneHelper::isValidTanzanianPhone($request->phone)) {
                throw ValidationException::withMessages([
                    'phone' => __('Please enter a valid Tanzanian phone number starting with 06, 07, +255, or 255 (e.g. 0678429492 or +255678429492).'),
                ]);
            }

            $possibleFormats = PhoneHelper::getPossibleFormats($request->phone);
            if (User::where('id', '!=', $user->id)->whereIn('phone', $possibleFormats)->exists()) {
                throw ValidationException::withMessages([
                    'phone' => __('Phone number already taken'),
                ]);
            }
        }

        $rules['security_question'] = 'nullable|string|max:255';
        $rules['security_answer'] = 'nullable|string|max:255';

        $data = $request->only([
            'name', 'email', 'country', 'description',
            'social_instagram', 'social_facebook', 'social_tiktok', 'social_youtube',
            'security_question', 'security_answer'
        ]);

        $data['phone'] = $request->filled('phone') ? PhoneHelper::normalizeToLocal($request->phone) : null;

        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        if ($request->boolean('remove_profile_image') || $request->input('remove_profile_image') == '1') {
            if ($user->profile_image && !str_starts_with($user->profile_image, 'http')) {
                $relativePath = str_replace('/storage/', '', $user->profile_image);
                Storage::disk('public')->delete($relativePath);
            }
            $data['profile_image'] = null;
        }

        if ($request->hasFile('profile_image')) {
            $request->validate([
                'profile_image' => 'file|image|mimes:jpeg,png,jpg,gif,webp|max:102400',
            ], [
                'profile_image.image' => 'The profile image must be a valid image file (JPEG, PNG, JPG, GIF, or WEBP).',
                'profile_image.mimes' => 'SVG and non-standard file formats are strictly prohibited.',
                'profile_image.max'   => 'Profile image file size cannot exceed 100MB.',
            ]);

            // Delete old profile image if it exists and is not a default stock URL
            if ($user->profile_image && !str_starts_with($user->profile_image, 'http')) {
                $relativePath = str_replace('/storage/', '', $user->profile_image);
                Storage::disk('public')->delete($relativePath);
            }

            try {
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
            } catch (\InvalidArgumentException $e) {
                return redirect()->back()->withInput()->withErrors(['profile_image' => $e->getMessage()]);
            }
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

        $photos = $user->media()->where('type', 'photo')->withCount(['likes', 'comments', 'shares'])->latest()->get();

        return view('dashboard.photos', [
            'photos' => $photos
        ]);
    }

    public function storePhoto(Request $request)
    {
        @ini_set('memory_limit', '512M');
        @ini_set('max_execution_time', '300');

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $limits = $user->currentPackageDetails();
        $photoCount = $user->media()->where('type', 'photo')->count();

        // Catch POST body overflow (post_max_size exceeded)
        if ($request->isMethod('post') && empty($_POST) && empty($_FILES) && isset($_SERVER['CONTENT_LENGTH']) && (int)$_SERVER['CONTENT_LENGTH'] > 0) {
            $msg = 'The uploaded file payload is too large and exceeded server upload limits. Please select smaller images under 100MB each.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()
                ->withInput()
                ->withErrors(['photos' => $msg]);
        }

        // Support both array 'photos' and single file 'photo'
        $files = [];
        if ($request->hasFile('photos')) {
            $files = is_array($request->file('photos')) ? $request->file('photos') : [$request->file('photos')];
        } elseif ($request->hasFile('photo')) {
            $files = [$request->file('photo')];
        } elseif ($request->hasFile('photo_file')) {
            $files = [$request->file('photo_file')];
        }

        if (empty($files)) {
            if (isset($_SERVER['CONTENT_LENGTH']) && intval($_SERVER['CONTENT_LENGTH']) > 0 && empty($_POST) && empty($_FILES)) {
                $maxSize = ini_get('post_max_size') ?: '128M';
                $msg = "The selected image payload exceeds the server upload limit ({$maxSize}). Please select a smaller photo or compress it first.";
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }
                return redirect()->back()->withInput()->withErrors(['photos' => $msg]);
            }

            $rawFiles = $request->file('photos') ?? $request->file('photo');
            if ($rawFiles) {
                $rawList = is_array($rawFiles) ? $rawFiles : [$rawFiles];
                foreach ($rawList as $rawFile) {
                    if ($rawFile && !$rawFile->isValid()) {
                        $errCode = $rawFile->getError();
                        if ($errCode === UPLOAD_ERR_INI_SIZE || $errCode === UPLOAD_ERR_FORM_SIZE) {
                            $maxSize = ini_get('upload_max_filesize') ?: '128M';
                            $msg = "The selected image file exceeds the PHP server upload limit ({$maxSize}). Please select a smaller photo or compress it first.";
                            if ($request->expectsJson() || $request->ajax()) {
                                return response()->json(['success' => false, 'message' => $msg], 422);
                            }
                            return redirect()->back()->withInput()->withErrors(['photos' => $msg]);
                        }
                    }
                }
            }

            $msg = 'Please select at least one image file to upload.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()->withInput()->withErrors(['photos' => $msg]);
        }

        $batchCount = count($files);
        if ($limits['max_images'] >= 0 && ($photoCount + $batchCount) > $limits['max_images']) {
            $remaining = max(0, $limits['max_images'] - $photoCount);
            $msg = "Uploading {$batchCount} images exceeds your package limit ({$photoCount}/{$limits['max_images']} used). You can only upload {$remaining} more image(s).";
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()->withInput()->withErrors(['photos' => $msg, 'photo' => $msg]);
        }

        $request->validate([
            'title'    => 'nullable|string|max:255',
            'caption'  => 'nullable|string|max:1000',
            'photos'   => 'nullable|array',
            'photos.*' => 'file|image|mimes:jpeg,png,jpg,gif,webp,heic,heif,bmp|max:102400',
            'photo'    => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp,heic,heif,bmp|max:102400',
        ], [
            'photos.*.image' => 'Each file must be a valid image format (JPEG, PNG, JPG, GIF, WEBP, or HEIC).',
            'photos.*.mimes' => 'Each photo must be a file of type: jpeg, png, jpg, gif, webp, heic, heif, bmp.',
            'photos.*.max'   => 'Individual photo file size cannot exceed 100MB.',
        ]);

        $uploadedCount = 0;
        $flaggedCount = 0;

        foreach ($files as $file) {
            if (!$file->isValid()) continue;

            $moderation = ContentModerationService::checkImage($file, $request->title, $request->caption);

            try {
                $path = ImageCompressor::compressAndStore($file, 'media/photos', 1920, 1920, 82, 'webp');
            } catch (\InvalidArgumentException $e) {
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
                }
                return redirect()->back()->withInput()->withErrors(['photos' => $e->getMessage()]);
            }

            $status = $moderation['flagged'] ? 'flagged' : 'approved';
            $isVisible = !$moderation['flagged'];

            $user->media()->create([
                'type'              => 'photo',
                'title'             => $request->title,
                'content'           => $request->caption,
                'file_path'         => '/storage/' . $path,
                'moderation_status' => $status,
                'moderation_reason' => $moderation['reason'],
                'moderation_score'  => $moderation['score'],
                'is_visible'        => $isVisible,
            ]);

            $uploadedCount++;
            if ($moderation['flagged']) {
                $flaggedCount++;
                $staffMembers = User::whereIn('role', ['admin', 'customer_care'])->get();
                foreach ($staffMembers as $staff) {
                    Notification::create([
                        'user_id' => $staff->id,
                        'type'    => 'nsfw_media_flagged',
                        'title'   => "⚠️ NSFW / Explicit Photo Auto-Flagged",
                        'message' => "Talent '" . $user->name . "' uploaded a photo auto-flagged by moderation: " . $moderation['reason'],
                        'link'    => route('admin.moderation'),
                    ]);
                }
            }
        }

        if ($uploadedCount === 0) {
            $msg = 'No valid images were processed.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()->withInput()->withErrors(['photos' => $msg]);
        }

        if ($flaggedCount > 0) {
            $msg = __("{$uploadedCount} photo(s) processed. {$flaggedCount} photo(s) flagged for admin review.");
            session()->flash('warning', $msg);
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => $msg]);
            }
            return redirect()->route('dashboard.photos')->with('warning', $msg);
        }

        $msg = __("{$uploadedCount} photo(s) compressed & uploaded successfully.");
        session()->flash('success', $msg);
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => $msg]);
        }
        return redirect()->route('dashboard.photos')->with('success', $msg);
    }

    public function updatePhoto(Request $request, $id)
    {
        @ini_set('memory_limit', '512M');
        $photo = auth()->user()->media()->where('type', 'photo')->findOrFail($id);

        if ($request->isMethod('post') && empty($_POST) && empty($_FILES) && isset($_SERVER['CONTENT_LENGTH']) && (int)$_SERVER['CONTENT_LENGTH'] > 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['photo' => 'The uploaded photo file is too large and exceeded server payload limits. Please select an image under 100MB.']);
        }

        if ($request->file('photo')) {
            $photoFile = $request->file('photo');
            if (!$photoFile->isValid()) {
                $errorCode = $photoFile->getError();
                if ($errorCode === UPLOAD_ERR_INI_SIZE || $errorCode === UPLOAD_ERR_FORM_SIZE) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['photo' => 'The photo file exceeds the server upload limit. Please select an image under 100MB.']);
                }
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['photo' => 'Photo upload failed (' . $photoFile->getErrorMessage() . '). Please select another image file.']);
            }
        }

        $request->validate([
            'title'   => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:1000',
            'photo'   => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp,heic,heif,bmp|max:102400',
        ], [
            'photo.image' => 'The file must be a valid image format.',
            'photo.mimes' => 'The photo must be a file of type: jpeg, png, jpg, gif, webp, heic, heif, bmp.',
            'photo.max'   => 'The photo file size cannot exceed 100MB.',
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

            try {
                $path = ImageCompressor::compressAndStore(
                    $request->file('photo'),
                    'media/photos',
                    1920,
                    1920,
                    82,
                    'webp'
                );
                $data['file_path'] = '/storage/' . $path;
            } catch (\InvalidArgumentException $e) {
                return redirect()->back()->withInput()->withErrors(['photo' => $e->getMessage()]);
            }
        }

        $photo->update($data);

        return redirect()->route('dashboard.photos')->with('success', 'Photo details updated successfully.');
    }

    public function deletePhoto($id)
    {
        $photo = auth()->user()->media()->where('type', 'photo')->findOrFail($id);

        if ($photo->file_path && !str_starts_with($photo->file_path, 'http')) {
            $relativePath = str_replace('/storage/', '', $photo->file_path);
            Storage::disk('public')->delete($relativePath);
        }

        $photo->delete();

        return redirect()->route('dashboard.photos')->with('success', 'Photo deleted successfully.');
    }

    public function videos()
    {
        $user = auth()->user();
        $videos = $user->media()->where('type', 'video')->withCount(['likes', 'comments', 'shares'])->latest()->get();

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

        // Catch POST body overflow (post_max_size exceeded)
        if ($request->isMethod('post') && empty($_POST) && empty($_FILES) && isset($_SERVER['CONTENT_LENGTH']) && (int)$_SERVER['CONTENT_LENGTH'] > 0) {
            $msg = 'The uploaded video file is too large and exceeded server payload limits. Please upload clips under 100MB each or embed a YouTube link.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()
                ->withInput()
                ->withErrors(['videos' => $msg]);
        }

        $request->validate([
            'title'   => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:1000',
        ]);

        // Moderation text check on video title & caption
        $textMod = ContentModerationService::checkText($request->title . ' ' . $request->caption);
        $status = $textMod['flagged'] ? 'flagged' : 'approved';
        $reason = $textMod['flagged'] ? 'Explicit keywords detected in title/caption: ' . $textMod['matched'] : null;
        $isVisible = !$textMod['flagged'];

        // 1. If Video URL is provided (must be YouTube, TikTok, Vimeo, etc.)
        if ($request->filled('video_url')) {
            if ($limits['max_videos'] >= 0 && $videoCount >= $limits['max_videos']) {
                $msg = "You have used {$videoCount} of {$limits['max_videos']} allowed videos. Upgrade your package to upload more videos.";
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }
                return redirect()->back()->withErrors(['videos' => $msg]);
            }

            $cleaned = \App\Helpers\VideoHelper::cleanUrl($request->input('video_url'));
            $request->merge(['video_url' => $cleaned]);

            $request->validate([
                'video_url' => [
                    'required',
                    'url',
                    'max:500',
                    function ($attribute, $value, $fail) {
                        $host = strtolower(parse_url($value, PHP_URL_HOST) ?? '');
                        $allowed = [
                            'youtube.com', 'www.youtube.com', 'm.youtube.com', 'music.youtube.com', 'youtu.be', 'www.youtu.be',
                            'tiktok.com', 'www.tiktok.com', 'm.tiktok.com', 'vm.tiktok.com', 'vt.tiktok.com',
                            'instagram.com', 'www.instagram.com',
                            'facebook.com', 'www.facebook.com', 'web.facebook.com', 'm.facebook.com', 'fb.watch',
                            'vimeo.com', 'www.vimeo.com', 'player.vimeo.com'
                        ];
                        $isAllowed = false;
                        foreach ($allowed as $domain) {
                            if ($host === $domain || str_ends_with($host, '.' . $domain)) {
                                $isAllowed = true;
                                break;
                            }
                        }
                        if (!$isAllowed) {
                            $fail('The video URL must be a valid link from TikTok, YouTube, Instagram, Facebook, or Vimeo.');
                        }
                    },
                ],
            ], [
                'video_url.required' => 'Please enter a video URL link from TikTok, YouTube, Instagram, Facebook, or Vimeo.',
                'video_url.url'      => 'Please enter a valid video link (e.g. TikTok, YouTube, Instagram, Facebook, or Vimeo).',
            ]);

            auth()->user()->media()->create([
                'type'              => 'video',
                'title'             => $request->title,
                'content'           => $request->caption,
                'file_path'         => $request->video_url,
                'moderation_status' => $status,
                'moderation_reason' => $reason,
                'is_visible'        => $isVisible,
            ]);

            if ($textMod['flagged']) {
                $staffMembers = User::whereIn('role', ['admin', 'customer_care'])->get();
                foreach ($staffMembers as $staff) {
                    Notification::create([
                        'user_id' => $staff->id,
                        'type'    => 'nsfw_media_flagged',
                        'title'   => "⚠️ Explicit Video Link Flagged",
                        'message' => "Talent '" . auth()->user()->name . "' added a video auto-flagged by moderation: " . ($reason ?? 'Explicit content'),
                        'link'    => route('admin.moderation'),
                    ]);
                }

                $msg = __('Video added but flagged for review due to potentially inappropriate content. It will remain hidden from the public until reviewed.');
                session()->flash('warning', $msg);
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => true, 'message' => $msg]);
                }
                return redirect()->route('dashboard.videos')->with('warning', $msg);
            }

            $msg = __('Video link added successfully to your portfolio.');
            session()->flash('success', $msg);
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => $msg]);
            }
            return redirect()->route('dashboard.videos')->with('success', $msg);
        }

        // 2. Process file uploads (batch `videos[]` or single `video`)
        $files = [];
        if ($request->hasFile('videos')) {
            $files = is_array($request->file('videos')) ? $request->file('videos') : [$request->file('videos')];
        } elseif ($request->hasFile('video')) {
            $files = [$request->file('video')];
        }

        if (empty($files)) {
            // Check for post_max_size overflow (when upload exceeds post_max_size, $_FILES and $_POST are empty)
            if (isset($_SERVER['CONTENT_LENGTH']) && intval($_SERVER['CONTENT_LENGTH']) > 0 && empty($_POST) && empty($_FILES)) {
                $maxSize = ini_get('post_max_size') ?: '128M';
                $msg = "The selected video file exceeds the PHP server upload limit ({$maxSize}). Please upload a smaller clip or embed a video link (YouTube, TikTok, Instagram, Facebook, Vimeo).";
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }
                return redirect()->back()->withInput()->withErrors(['videos' => $msg]);
            }

            // Check if user actually attempted to upload a file that failed due to PHP upload limits
            $rawFiles = $request->file('videos') ?? $request->file('video');
            if ($rawFiles) {
                $rawList = is_array($rawFiles) ? $rawFiles : [$rawFiles];
                foreach ($rawList as $rawFile) {
                    if ($rawFile && !$rawFile->isValid()) {
                        $errCode = $rawFile->getError();
                        if ($errCode === UPLOAD_ERR_INI_SIZE || $errCode === UPLOAD_ERR_FORM_SIZE) {
                            $maxSize = ini_get('upload_max_filesize') ?: '128M';
                            $msg = "The selected video file exceeds the PHP server upload limit ({$maxSize}). Please upload a smaller clip or embed a video link (YouTube, TikTok, Instagram, Facebook, Vimeo).";
                            if ($request->expectsJson() || $request->ajax()) {
                                return response()->json(['success' => false, 'message' => $msg], 422);
                            }
                            return redirect()->back()->withInput()->withErrors(['videos' => $msg]);
                        }
                    }
                }
            }

            $msg = 'Please select at least one video file to upload or enter a video link.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()->withInput()->withErrors(['videos' => $msg]);
        }

        $batchCount = count($files);
        if ($limits['max_videos'] >= 0 && ($videoCount + $batchCount) > $limits['max_videos']) {
            $remaining = max(0, $limits['max_videos'] - $videoCount);
            $msg = "Uploading {$batchCount} video clip(s) exceeds your package limit ({$videoCount}/{$limits['max_videos']} used). You can only upload {$remaining} more video clip(s).";
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return redirect()->back()->withInput()->withErrors(['videos' => $msg]);
        }

        $request->validate([
            'videos'   => 'nullable|array',
            'videos.*' => 'file|mimes:mp4,mov,avi,webm,ogg,mkv,3gp,flv,qt|max:102400',
            'video'    => 'nullable|file|mimes:mp4,mov,avi,webm,ogg,mkv,3gp,flv,qt|max:102400',
        ], [
            'videos.*.mimes' => 'The video format must be: MP4, MOV, AVI, WEBM, OGG, MKV, or 3GP.',
            'videos.*.max'   => 'Individual video file size cannot exceed 100MB.',
        ]);

        $uploadedCount = 0;
        $flaggedCount = 0;
        $allowedExts = ['mp4', 'mov', 'avi', 'webm', 'ogg', 'mkv', '3gp', 'flv', 'qt'];

        foreach ($files as $videoFile) {
            if (!$videoFile->isValid()) continue;

            $mime = strtolower($videoFile->getMimeType() ?? '');
            $ext = strtolower($videoFile->getClientOriginalExtension() ?? '');

            if (
                str_contains($mime, 'svg') || str_contains($mime, 'xml') || str_contains($mime, 'html') ||
                in_array($ext, ['svg', 'svgz', 'xml', 'html', 'htm', 'php', 'phtml', 'phar', 'exe', 'sh', 'js', 'bat']) ||
                !in_array($ext, $allowedExts)
            ) {
                $msg = 'One or more files have an invalid or prohibited video format.';
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }
                return redirect()->back()->withInput()->withErrors(['videos' => $msg]);
            }

            $safeFilename = \Illuminate\Support\Str::random(40) . '.' . $ext;
            $path = $videoFile->storeAs('media/videos', $safeFilename, 'public');

            $user->media()->create([
                'type'              => 'video',
                'title'             => $request->title,
                'content'           => $request->caption,
                'file_path'         => '/storage/' . $path,
                'moderation_status' => $status,
                'moderation_reason' => $reason,
                'is_visible'        => $isVisible,
            ]);

            $uploadedCount++;
            if ($textMod['flagged']) {
                $flaggedCount++;
                $staffMembers = User::whereIn('role', ['admin', 'customer_care'])->get();
                foreach ($staffMembers as $staff) {
                    Notification::create([
                        'user_id' => $staff->id,
                        'type'    => 'nsfw_media_flagged',
                        'title'   => "⚠️ NSFW / Explicit Video Auto-Flagged",
                        'message' => "Talent '" . $user->name . "' uploaded a video auto-flagged by moderation: " . ($reason ?? 'Explicit content'),
                        'link'    => route('admin.moderation'),
                    ]);
                }
            }
        }

        if ($uploadedCount === 0) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => 'No valid video files were processed.'], 422);
            }
            return redirect()->back()->withInput()->withErrors(['videos' => 'No valid video files were processed.']);
        }

        if ($flaggedCount > 0) {
            $msg = __("{$uploadedCount} video file(s) uploaded. {$flaggedCount} flagged for review.");
            session()->flash('warning', $msg);
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'message' => $msg]);
            }
            return redirect()->route('dashboard.videos')->with('warning', $msg);
        }

        $msg = __("{$uploadedCount} video file(s) uploaded successfully.");
        session()->flash('success', $msg);
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => $msg]);
        }
        return redirect()->route('dashboard.videos')->with('success', $msg);
    }

    public function updateVideo(Request $request, $id)
    {
        @ini_set('memory_limit', '512M');
        @ini_set('max_execution_time', '300');
        $video = auth()->user()->media()->where('type', 'video')->findOrFail($id);

        if ($request->isMethod('post') && empty($_POST) && empty($_FILES) && isset($_SERVER['CONTENT_LENGTH']) && (int)$_SERVER['CONTENT_LENGTH'] > 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['video' => 'The uploaded video file is too large and exceeded server payload limits. Please upload a clip under 100MB.']);
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
                        $allowed = [
                            'youtube.com', 'www.youtube.com', 'm.youtube.com', 'music.youtube.com', 'youtu.be', 'www.youtu.be',
                            'tiktok.com', 'www.tiktok.com', 'm.tiktok.com', 'vm.tiktok.com', 'vt.tiktok.com',
                            'instagram.com', 'www.instagram.com',
                            'facebook.com', 'www.facebook.com', 'web.facebook.com', 'm.facebook.com', 'fb.watch',
                            'vimeo.com', 'www.vimeo.com', 'player.vimeo.com'
                        ];
                        $isAllowed = false;
                        foreach ($allowed as $domain) {
                            if ($host === $domain || str_ends_with($host, '.' . $domain)) {
                                $isAllowed = true;
                                break;
                            }
                        }
                        if (!$isAllowed) {
                            $fail('The video URL must be a valid link from TikTok, YouTube, Instagram, Facebook, or Vimeo.');
                        }
                    },
                ],
            ], [
                'video_url.required' => 'Please enter a video URL link from TikTok, YouTube, Instagram, Facebook, or Vimeo.',
                'video_url.url'      => 'Please enter a valid video link (e.g. TikTok, YouTube, Instagram, Facebook, or Vimeo).',
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
                        ->withErrors(['video' => 'The video file exceeds the server upload limit. Please select a clip under 100MB.']);
                }
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['video' => 'Video upload failed: ' . $videoFile->getErrorMessage()]);
            }

            $request->validate([
                'video' => 'nullable|file|mimes:mp4,mov,avi,webm,ogg,mkv,3gp,flv,qt|max:102400',
            ], [
                'video.mimes' => 'The video format must be: MP4, MOV, AVI, WEBM, OGG, MKV, or 3GP.',
                'video.max'   => 'The video file size cannot exceed 100MB.',
            ]);

            if ($videoFile->isValid()) {
                $mime = strtolower($videoFile->getMimeType() ?? '');
                $ext = strtolower($videoFile->getClientOriginalExtension() ?? '');

                if (
                    str_contains($mime, 'svg') || str_contains($mime, 'xml') || str_contains($mime, 'html') ||
                    in_array($ext, ['svg', 'svgz', 'xml', 'html', 'htm', 'php', 'phtml', 'phar', 'exe', 'sh', 'js', 'bat'])
                ) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['video' => 'SVG, HTML, XML, and executable formats are strictly prohibited for video uploads.']);
                }

                $allowedExts = ['mp4', 'mov', 'avi', 'webm', 'ogg', 'mkv', '3gp', 'flv', 'qt'];
                if (!in_array($ext, $allowedExts)) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['video' => 'The video format must be: MP4, MOV, AVI, WEBM, OGG, MKV, or 3GP.']);
                }

                if ($video->file_path && !str_starts_with($video->file_path, 'http')) {
                    $relativePath = str_replace('/storage/', '', $video->file_path);
                    Storage::disk('public')->delete($relativePath);
                }

                $safeFilename = \Illuminate\Support\Str::random(40) . '.' . $ext;
                $path = $videoFile->storeAs('media/videos', $safeFilename, 'public');
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
        if ($limits['max_news'] >= 0 && $newsCount >= $limits['max_news']) {
            return redirect()->back()->withErrors(['title' => "You have used {$newsCount} of {$limits['max_news']} allowed news articles. Upgrade your package to publish more news."]);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:102400',
        ]);

        $filePath = null;
        if ($request->hasFile('image')) {
            try {
                $path = ImageCompressor::compressAndStore(
                    $request->file('image'),
                    'media/news',
                    1200,
                    1200,
                    82,
                    'webp'
                );
                $filePath = '/storage/' . $path;
            } catch (\InvalidArgumentException $e) {
                return redirect()->back()->withInput()->withErrors(['image' => $e->getMessage()]);
            }
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
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:102400',
        ], [
            'title.required'   => 'Please provide a title for the news article.',
            'content.required' => 'Please enter the content details for the news article.',
            'image.image'      => 'The cover file must be a valid image.',
            'image.max'        => 'The image file size cannot exceed 100MB.',
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

            try {
                $path = ImageCompressor::compressAndStore(
                    $request->file('image'),
                    'media/news',
                    1200,
                    1200,
                    82,
                    'webp'
                );
                $data['file_path'] = '/storage/' . $path;
            } catch (\InvalidArgumentException $e) {
                return redirect()->back()->withInput()->withErrors(['image' => $e->getMessage()]);
            }
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

        if ($user->requiresPaymentToPublish()) {
            $amount = floatval(\App\Models\SystemSetting::get('payment_amount', 10000.00));
            return redirect()->route('dashboard')
                ->with('open_payment_modal', true)
                ->with('error', 'Payment confirmation required. You have to pay TZS ' . number_format($amount) . '/- to complete payment before publishing your account.');
        }

        $user->update(['is_published' => true]);
        return redirect()->route('dashboard')->with('success', 'Your profile is now live and visible to the public!');
    }

    /**
     * Submit payment confirmation transaction ID / reference for payment verification.
     */
    public function submitPaymentConfirmation(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'transaction_reference' => 'required|string|max:255',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'notes' => 'nullable|string|max:500',
        ]);

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $amount = floatval(\App\Models\SystemSetting::get('payment_amount', 10000.00));

        $noteText = 'Transaction Ref: ' . $request->transaction_reference . ($request->notes ? (' - ' . $request->notes) : '');

        // Get unpaid invoice or create a publishing fee invoice for this payment
        $invoice = $user->invoices()->where('payment_status', 'Unpaid')->latest()->first();
        if ($invoice) {
            $invoice->update([
                'notes' => $noteText,
            ]);
        } else {
            $invoiceNumber = \App\Models\Invoice::generateInvoiceNumber();
            $startDate = now()->toDateString();
            $endDate = date('Y-m-d', strtotime($startDate . ' + 365 days'));
            $activeSub = $user->activeSubscription;
            $invoice = \App\Models\Invoice::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => $user->id,
                'user_package_id' => $activeSub ? $activeSub->id : 1,
                'package_name' => 'Profile Publishing Fee',
                'start_date' => $startDate,
                'end_date' => $endDate,
                'duration' => 365,
                'duration_unit' => 'days',
                'amount' => $amount,
                'amount_paid' => 0.00,
                'payment_status' => 'Unpaid',
                'invoice_date' => $startDate,
                'due_date' => date('Y-m-d', strtotime($startDate . ' + 7 days')),
                'notes' => $noteText,
            ]);
        }

        // Notify Admin and Customer Care staff of payment received notice
        $adminStaff = \App\Models\User::whereIn('role', ['admin', 'customer_care'])->get();
        foreach ($adminStaff as $staff) {
            \App\Models\Notification::create([
                'user_id' => $staff->id,
                'type' => 'payment_submission',
                'title' => "💳 Notice of Payment Received: {$user->name}",
                'message' => "User {$user->name} submitted payment transaction reference '{$request->transaction_reference}' (TZS " . number_format($amount) . ") for profile publishing. Please verify payment under Invoices.",
                'link' => ($staff->role === 'admin') ? route('admin.dashboard') . '#invoices' : route('customer-care.dashboard') . '#invoices',
            ]);
        }

        return redirect()->back()->with('success', 'Your payment transaction reference (' . $request->transaction_reference . ') has been submitted successfully! Admin / Customer Care will verify payment received under Invoices shortly.');
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
