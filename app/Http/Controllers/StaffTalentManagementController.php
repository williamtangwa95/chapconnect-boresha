<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Media;
use App\Models\User;
use App\Models\UserActivityLog;
use App\Services\ImageCompressor;
use App\Helpers\PhoneHelper;
use App\Helpers\VideoHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class StaffTalentManagementController extends Controller
{
    /**
     * Display the Staff Talent Management page for a specified talent.
     */
    public function manage($talentId)
    {
        $talent = User::where('role', 'user')->findOrFail($talentId);

        $categories = Category::orderBy('name')->pluck('name', 'slug')->toArray();
        $photos = $talent->media()->where('type', 'photo')->latest()->get();
        $videos = $talent->media()->where('type', 'video')->latest()->get();
        $newsItems = $talent->media()->where('type', 'news')->latest()->get();

        return view('staff.manage_talent', [
            'talent' => $talent,
            'categories' => $categories,
            'photos' => $photos,
            'videos' => $videos,
            'newsItems' => $newsItems,
        ]);
    }

    /**
     * Update talent profile details on behalf of the talent.
     */
    public function updateProfile(Request $request, $talentId)
    {
        $talent = User::where('role', 'user')->findOrFail($talentId);

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $talent->id,
            'phone' => 'nullable|string|max:30',
            'category' => 'required|string|exists:categories,slug',
            'country' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'social_instagram' => 'nullable|url|max:255',
            'social_facebook'  => 'nullable|url|max:255',
            'social_tiktok'    => 'nullable|url|max:255',
            'social_youtube'   => 'nullable|url|max:255',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:6|confirmed';
        }

        $request->validate($rules);

        if ($request->filled('phone')) {
            if (!PhoneHelper::isValidTanzanianPhone($request->phone)) {
                throw ValidationException::withMessages([
                    'phone' => __('Please enter a valid Tanzanian phone number starting with 06, 07, +255, or 255.'),
                ]);
            }

            $possibleFormats = PhoneHelper::getPossibleFormats($request->phone);
            if (User::where('id', '!=', $talent->id)->whereIn('phone', $possibleFormats)->exists()) {
                throw ValidationException::withMessages([
                    'phone' => __('Phone number already taken by another account.'),
                ]);
            }
        }

        $categorySlug = $request->input('category');
        $categoryRecord = Category::where('slug', $categorySlug)->first();
        $categoryLabel = $categoryRecord ? $categoryRecord->name : ucfirst($categorySlug);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'category' => $categorySlug,
            'category_label' => $categoryLabel,
            'country' => $request->country ?? 'Tanzania',
            'description' => $request->description,
            'social_instagram' => $request->social_instagram,
            'social_facebook' => $request->social_facebook,
            'social_tiktok' => $request->social_tiktok,
            'social_youtube' => $request->social_youtube,
        ];

        $data['phone'] = $request->filled('phone') ? PhoneHelper::normalizeToLocal($request->phone) : null;

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Profile Photo Upload & Compression
        if ($request->hasFile('profile_image')) {
            if ($talent->profile_image && !str_starts_with($talent->profile_image, 'http')) {
                $relativePath = str_replace('/storage/', '', $talent->profile_image);
                Storage::disk('public')->delete($relativePath);
            }

            try {
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

        $talent->update($data);

        UserActivityLog::log('UPDATED', "Staff '" . auth()->user()->name . "' updated profile details for talent: {$talent->name}", [
            'talent_id' => $talent->id,
            'updated_by' => auth()->user()->name,
        ], null, 'User', $talent->id);

        return redirect()->back()->with('success', "Profile details for talent '{$talent->name}' updated successfully.");
    }

    /**
     * Upload photo(s) on behalf of talent.
     */
    public function storePhoto(Request $request, $talentId)
    {
        @ini_set('memory_limit', '512M');
        @ini_set('max_execution_time', '300');

        $talent = User::where('role', 'user')->findOrFail($talentId);

        $files = [];
        if ($request->hasFile('photos')) {
            $files = is_array($request->file('photos')) ? $request->file('photos') : [$request->file('photos')];
        } elseif ($request->hasFile('photo')) {
            $files = [$request->file('photo')];
        }

        if (empty($files)) {
            return redirect()->back()->withInput()->withErrors(['photos' => 'Please select at least one image file to upload.']);
        }

        $request->validate([
            'title'    => 'nullable|string|max:255',
            'caption'  => 'nullable|string|max:1000',
            'photos'   => 'nullable|array',
            'photos.*' => 'file|image|mimes:jpeg,png,jpg,gif,webp,heic,heif,bmp|max:15360',
            'photo'    => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp,heic,heif,bmp|max:15360',
        ]);

        $uploadedCount = 0;

        foreach ($files as $file) {
            if (!$file->isValid()) continue;

            try {
                $path = ImageCompressor::compressAndStore(
                    $file,
                    'media/photos',
                    1920,
                    1920,
                    82,
                    'webp'
                );

                $talent->media()->create([
                    'type'              => 'photo',
                    'title'             => $request->title,
                    'content'           => $request->caption,
                    'file_path'         => '/storage/' . $path,
                    'moderation_status' => 'approved',
                    'is_visible'        => true,
                    'reviewed_by'       => auth()->id(),
                    'reviewed_at'       => now(),
                ]);

                $uploadedCount++;
            } catch (\InvalidArgumentException $e) {
                return redirect()->back()->withInput()->withErrors(['photos' => $e->getMessage()]);
            }
        }

        UserActivityLog::log('CREATED', "Staff '" . auth()->user()->name . "' uploaded {$uploadedCount} photo(s) for talent: {$talent->name}", [
            'talent_id' => $talent->id,
        ], null, 'Media', $talent->id);

        return redirect()->back()->with('success', "Successfully uploaded {$uploadedCount} photo(s) for talent '{$talent->name}'.");
    }

    /**
     * Delete photo of a talent.
     */
    public function deletePhoto($talentId, $mediaId)
    {
        $talent = User::where('role', 'user')->findOrFail($talentId);
        $media = $talent->media()->where('type', 'photo')->findOrFail($mediaId);

        if (!str_starts_with($media->file_path, 'http')) {
            $relativePath = str_replace('/storage/', '', $media->file_path);
            Storage::disk('public')->delete($relativePath);
        }

        $media->delete();

        return redirect()->back()->with('success', "Photo deleted successfully from talent '{$talent->name}' portfolio.");
    }

    /**
     * Update photo details or replace image file on behalf of talent.
     */
    public function updatePhoto(Request $request, $talentId, $mediaId)
    {
        @ini_set('memory_limit', '512M');
        $talent = User::where('role', 'user')->findOrFail($talentId);
        $photo = $talent->media()->where('type', 'photo')->findOrFail($mediaId);

        $request->validate([
            'title'   => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:1000',
            'photo'   => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp,heic,heif,bmp|max:15360',
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

        UserActivityLog::log('UPDATED', "Staff '" . auth()->user()->name . "' updated photo details for talent: {$talent->name}", [
            'talent_id' => $talent->id,
            'photo_id'  => $photo->id,
        ], null, 'Media', $talent->id);

        return redirect()->back()->with('success', "Photo updated successfully for talent '{$talent->name}'.");
    }

    /**
     * Upload/Add video on behalf of talent.
     */
    public function storeVideo(Request $request, $talentId)
    {
        @ini_set('memory_limit', '512M');
        @ini_set('max_execution_time', '300');

        $talent = User::where('role', 'user')->findOrFail($talentId);

        $request->validate([
            'title'   => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:1000',
        ]);

        // 1. If Video URL link is provided
        if ($request->filled('video_url')) {
            $cleaned = VideoHelper::cleanUrl($request->input('video_url'));
            $request->merge(['video_url' => $cleaned]);

            $request->validate([
                'video_url' => 'required|url|max:500',
            ]);

            $talent->media()->create([
                'type'              => 'video',
                'title'             => $request->title,
                'content'           => $request->caption,
                'file_path'         => $request->video_url,
                'moderation_status' => 'approved',
                'is_visible'        => true,
                'reviewed_by'       => auth()->id(),
                'reviewed_at'       => now(),
            ]);

            return redirect()->back()->with('success', "Video link added successfully for talent '{$talent->name}'.");
        }

        // 2. Video file upload
        if ($request->hasFile('video')) {
            $request->validate([
                'video' => 'required|file|mimes:mp4,mov,avi,wmv,webm,mkv|max:51200',
            ]);

            $file = $request->file('video');
            $filename = 'video_' . time() . '_' . \Illuminate\Support\Str::random(6) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('media/videos', $filename, 'public');

            $talent->media()->create([
                'type'              => 'video',
                'title'             => $request->title ?: 'Video Clip',
                'content'           => $request->caption,
                'file_path'         => '/storage/' . $path,
                'moderation_status' => 'approved',
                'is_visible'        => true,
                'reviewed_by'       => auth()->id(),
                'reviewed_at'       => now(),
            ]);

            return redirect()->back()->with('success', "Video file uploaded successfully for talent '{$talent->name}'.");
        }

        return redirect()->back()->withInput()->withErrors(['video' => 'Please enter a video URL link or upload a video file.']);
    }

    /**
     * Delete video of a talent.
     */
    public function deleteVideo($talentId, $mediaId)
    {
        $talent = User::where('role', 'user')->findOrFail($talentId);
        $media = $talent->media()->where('type', 'video')->findOrFail($mediaId);

        if (!str_starts_with($media->file_path, 'http')) {
            $relativePath = str_replace('/storage/', '', $media->file_path);
            Storage::disk('public')->delete($relativePath);
        }

        $media->delete();

        return redirect()->back()->with('success', "Video deleted successfully from talent '{$talent->name}' portfolio.");
    }

    /**
     * Update video details, link URL, or replacement file on behalf of talent.
     */
    public function updateVideo(Request $request, $talentId, $mediaId)
    {
        @ini_set('memory_limit', '512M');
        @ini_set('max_execution_time', '300');

        $talent = User::where('role', 'user')->findOrFail($talentId);
        $video = $talent->media()->where('type', 'video')->findOrFail($mediaId);

        $request->validate([
            'title'   => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:1000',
        ]);

        $data = [
            'title'   => $request->title,
            'content' => $request->caption,
        ];

        if ($request->filled('video_url')) {
            $cleaned = VideoHelper::cleanUrl($request->input('video_url'));
            $request->merge(['video_url' => $cleaned]);

            $request->validate([
                'video_url' => 'required|url|max:500',
            ]);

            if ($video->file_path && !str_starts_with($video->file_path, 'http')) {
                $relativePath = str_replace('/storage/', '', $video->file_path);
                Storage::disk('public')->delete($relativePath);
            }

            $data['file_path'] = $request->video_url;
        } elseif ($request->hasFile('video') && $request->file('video')->isValid()) {
            $request->validate([
                'video' => 'required|file|mimes:mp4,mov,avi,wmv,webm,mkv|max:51200',
            ]);

            if ($video->file_path && !str_starts_with($video->file_path, 'http')) {
                $relativePath = str_replace('/storage/', '', $video->file_path);
                Storage::disk('public')->delete($relativePath);
            }

            $file = $request->file('video');
            $filename = 'video_' . time() . '_' . \Illuminate\Support\Str::random(6) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('media/videos', $filename, 'public');
            $data['file_path'] = '/storage/' . $path;
        }

        $video->update($data);

        UserActivityLog::log('UPDATED', "Staff '" . auth()->user()->name . "' updated video details for talent: {$talent->name}", [
            'talent_id' => $talent->id,
            'video_id'  => $video->id,
        ], null, 'Media', $talent->id);

        return redirect()->back()->with('success', "Video updated successfully for talent '{$talent->name}'.");
    }

    /**
     * Publish News / Bulletin on behalf of talent.
     */
    public function storeNews(Request $request, $talentId)
    {
        $talent = User::where('role', 'user')->findOrFail($talentId);

        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
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

        $talent->media()->create([
            'type'              => 'news',
            'title'             => $request->title,
            'content'           => $request->content,
            'file_path'         => $filePath,
            'moderation_status' => 'approved',
            'is_visible'        => true,
            'reviewed_by'       => auth()->id(),
            'reviewed_at'       => now(),
        ]);

        UserActivityLog::log('CREATED', "Staff '" . auth()->user()->name . "' published news bulletin for talent: {$talent->name}", [
            'talent_id' => $talent->id,
            'title'     => $request->title,
        ], null, 'Media', $talent->id);

        return redirect()->back()->with('success', "News bulletin '{$request->title}' published successfully for talent '{$talent->name}'.");
    }

    /**
     * Delete news article of a talent.
     */
    public function deleteNews($talentId, $mediaId)
    {
        $talent = User::where('role', 'user')->findOrFail($talentId);
        $media = $talent->media()->where('type', 'news')->findOrFail($mediaId);

        if ($media->file_path && !str_starts_with($media->file_path, 'http')) {
            $relativePath = str_replace('/storage/', '', $media->file_path);
            Storage::disk('public')->delete($relativePath);
        }

        $media->delete();

        return redirect()->back()->with('success', "News article deleted successfully from talent '{$talent->name}'.");
    }
}
