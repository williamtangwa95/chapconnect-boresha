<?php

namespace App\Http\Controllers;

use App\Models\ChapPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class StaffChapHubController extends Controller
{
    /**
     * Display hub management dashboard for Super Admin and Customer Care.
     */
    public function index(Request $request)
    {
        $category = $request->query('category', 'all');
        $search = $request->query('q');

        $query = ChapPost::with('author');

        if ($category !== 'all' && in_array($category, ['tutorial', 'news', 'announcement', 'testimony'])) {
            $query->where('category', $category);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%");
            });
        }

        $posts = $query->orderBy('is_pinned', 'desc')->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => ChapPost::count(),
            'tutorials' => ChapPost::where('category', 'tutorial')->count(),
            'news' => ChapPost::where('category', 'news')->count(),
            'announcements' => ChapPost::where('category', 'announcement')->count(),
            'testimonies' => ChapPost::where('category', 'testimony')->count(),
            'audios' => ChapPost::where('media_type', 'audio')->orWhereNotNull('audio_path')->count(),
            'videos' => ChapPost::where('media_type', 'video')->orWhereNotNull('video_path')->orWhereNotNull('video_url')->count(),
        ];

        return view('staff.hub_management', compact('posts', 'category', 'search', 'stats'));
    }

    /**
     * Store new ChapConnect Hub post.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:tutorial,news,announcement,testimony',
            'media_type' => 'required|in:article,photo,video,audio',
            'summary' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:51200', // 50MB
            'audio_file' => 'nullable|file|mimes:mp3,wav,m4a,ogg,aac,webm|max:102400', // 100MB
            'video_file' => 'nullable|file|mimes:mp4,mov,webm,mkv|max:102400', // 100MB
            'video_url' => 'nullable|url|max:500',
            'is_pinned' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
        ]);

        $featuredImagePath = null;
        if ($request->hasFile('featured_image')) {
            $file = $request->file('featured_image');
            $dir = public_path('uploads/hub/images');
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
            $filename = 'img_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);
            $featuredImagePath = 'uploads/hub/images/' . $filename;
        }

        $audioPath = null;
        if ($request->hasFile('audio_file')) {
            $file = $request->file('audio_file');
            $dir = public_path('uploads/hub/audios');
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
            $filename = 'audio_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);
            $audioPath = 'uploads/hub/audios/' . $filename;
        }

        $videoPath = null;
        if ($request->hasFile('video_file')) {
            $file = $request->file('video_file');
            $dir = public_path('uploads/hub/videos');
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
            $filename = 'video_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);
            $videoPath = 'uploads/hub/videos/' . $filename;
        }

        // Auto-refine media_type if not explicitly set
        $mediaType = $request->input('media_type', 'article');
        if ($audioPath) {
            $mediaType = 'audio';
        } elseif ($videoPath || $request->filled('video_url')) {
            $mediaType = 'video';
        } elseif ($featuredImagePath && empty($request->input('content')) && $mediaType === 'article') {
            $mediaType = 'photo';
        }

        $post = ChapPost::create([
            'user_id' => auth()->id(),
            'title' => $request->input('title'),
            'category' => $request->input('category'),
            'media_type' => $mediaType,
            'featured_image' => $featuredImagePath,
            'audio_path' => $audioPath,
            'video_url' => $request->input('video_url'),
            'video_path' => $videoPath,
            'summary' => $request->input('summary'),
            'content' => $request->input('content'),
            'is_pinned' => $request->boolean('is_pinned', false),
            'is_published' => $request->boolean('is_published', true),
        ]);

        return redirect()->back()->with('success', __('Hub post published successfully!'));
    }

    /**
     * Update an existing post.
     */
    public function update(Request $request, $id)
    {
        $post = ChapPost::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:tutorial,news,announcement,testimony',
            'media_type' => 'required|in:article,photo,video,audio',
            'summary' => 'nullable|string|max:500',
            'content' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:51200',
            'audio_file' => 'nullable|file|mimes:mp3,wav,m4a,ogg,aac,webm|max:102400',
            'video_file' => 'nullable|file|mimes:mp4,mov,webm,mkv|max:102400',
            'video_url' => 'nullable|url|max:500',
            'is_pinned' => 'nullable|boolean',
            'is_published' => 'nullable|boolean',
        ]);

        if ($request->hasFile('featured_image')) {
            if ($post->featured_image && File::exists(public_path($post->featured_image))) {
                File::delete(public_path($post->featured_image));
            }
            $file = $request->file('featured_image');
            $dir = public_path('uploads/hub/images');
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
            $filename = 'img_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);
            $post->featured_image = 'uploads/hub/images/' . $filename;
        }

        if ($request->hasFile('audio_file')) {
            if ($post->audio_path && File::exists(public_path($post->audio_path))) {
                File::delete(public_path($post->audio_path));
            }
            $file = $request->file('audio_file');
            $dir = public_path('uploads/hub/audios');
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
            $filename = 'audio_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);
            $post->audio_path = 'uploads/hub/audios/' . $filename;
        }

        if ($request->hasFile('video_file')) {
            if ($post->video_path && File::exists(public_path($post->video_path))) {
                File::delete(public_path($post->video_path));
            }
            $file = $request->file('video_file');
            $dir = public_path('uploads/hub/videos');
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }
            $filename = 'video_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move($dir, $filename);
            $post->video_path = 'uploads/hub/videos/' . $filename;
        }

        if ($request->has('video_url')) {
            $post->video_url = $request->input('video_url');
        }

        $post->title = $request->input('title');
        $post->category = $request->input('category');
        $post->media_type = $request->input('media_type');
        $post->summary = $request->input('summary');
        $post->content = $request->input('content');
        $post->is_pinned = $request->boolean('is_pinned', false);
        $post->is_published = $request->boolean('is_published', true);
        $post->save();

        return redirect()->back()->with('success', __('Hub post updated successfully!'));
    }

    /**
     * Delete post and cleanup assets.
     */
    public function destroy($id)
    {
        $post = ChapPost::findOrFail($id);

        if ($post->featured_image && File::exists(public_path($post->featured_image))) {
            File::delete(public_path($post->featured_image));
        }
        if ($post->audio_path && File::exists(public_path($post->audio_path))) {
            File::delete(public_path($post->audio_path));
        }
        if ($post->video_path && File::exists(public_path($post->video_path))) {
            File::delete(public_path($post->video_path));
        }

        $post->delete();

        return redirect()->back()->with('success', __('Hub post deleted successfully!'));
    }

    /**
     * Toggle publish status.
     */
    public function togglePublish($id)
    {
        $post = ChapPost::findOrFail($id);
        $post->is_published = !$post->is_published;
        $post->save();

        return redirect()->back()->with('success', $post->is_published ? __('Post published!') : __('Post hidden as draft.'));
    }

    /**
     * Toggle pinned status.
     */
    public function togglePin($id)
    {
        $post = ChapPost::findOrFail($id);
        $post->is_pinned = !$post->is_pinned;
        $post->save();

        return redirect()->back()->with('success', $post->is_pinned ? __('Post pinned to top!') : __('Post unpinned.'));
    }
}
