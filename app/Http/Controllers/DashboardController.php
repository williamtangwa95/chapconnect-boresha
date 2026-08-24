<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('dashboard.index', [
            'user' => $user
        ]);
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:30',
            'country' => 'required|string|max:100',
            'description' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'social_instagram' => 'nullable|url|max:255',
            'social_facebook' => 'nullable|url|max:255',
            'social_tiktok' => 'nullable|url|max:255',
            'social_youtube' => 'nullable|url|max:255',
        ]);

        $data = $request->only([
            'name', 'phone', 'country', 'description',
            'social_instagram', 'social_facebook', 'social_tiktok', 'social_youtube'
        ]);

        if ($request->hasFile('profile_image')) {
            // Delete old profile image if it exists and is not a default stock URL
            if ($user->profile_image && !str_starts_with($user->profile_image, 'http')) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $path = $request->file('profile_image')->store('profiles', 'public');
            $data['profile_image'] = '/storage/' . $path;
        }

        $user->update($data);

        return redirect()->route('dashboard')->with('success', 'Profile updated successfully.');
    }

    public function photos()
    {
        $user = auth()->user();
        $photos = $user->media()->where('type', 'photo')->latest()->get();

        return view('dashboard.photos', [
            'photos' => $photos
        ]);
    }

    public function storePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // up to 5MB
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('media/photos', 'public');
            
            auth()->user()->media()->create([
                'type' => 'photo',
                'file_path' => '/storage/' . $path,
            ]);

            return redirect()->route('dashboard.photos')->with('success', 'Photo uploaded successfully.');
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
            'video' => 'required|mimes:mp4,mov,avi,webm,ogg|max:20480', // up to 20MB
        ]);

        if ($request->hasFile('video')) {
            $path = $request->file('video')->store('media/videos', 'public');

            auth()->user()->media()->create([
                'type' => 'video',
                'file_path' => '/storage/' . $path,
            ]);

            return redirect()->route('dashboard.videos')->with('success', 'Video uploaded successfully.');
        }

        return redirect()->back()->with('error', 'Failed to upload video.');
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
}
