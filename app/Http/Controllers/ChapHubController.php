<?php

namespace App\Http\Controllers;

use App\Models\ChapPost;
use Illuminate\Http\Request;

class ChapHubController extends Controller
{
    /**
     * Display public ChapConnect Hub (Tutorials, News, Announcements & Testimonies).
     */
    public function index(Request $request)
    {
        $category = $request->query('category', 'all');
        $mediaType = $request->query('type', 'all');
        $search = $request->query('q');

        $query = ChapPost::with('author')->where('is_published', true);

        if ($category !== 'all' && in_array($category, ['tutorial', 'news', 'announcement', 'testimony'])) {
            $query->where('category', $category);
        }

        if ($mediaType !== 'all' && in_array($mediaType, ['audio', 'video', 'photo', 'article'])) {
            $query->where('media_type', $mediaType);
        }

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Pinned / Highlighted item for top banner
        $pinnedPost = null;
        if ($category === 'all' && $mediaType === 'all' && empty($search)) {
            $pinnedPost = ChapPost::with('author')
                ->where('is_published', true)
                ->where('is_pinned', true)
                ->latest()
                ->first();

            if ($pinnedPost) {
                $query->where('id', '!=', $pinnedPost->id);
            }
        }

        $posts = $query->orderBy('is_pinned', 'desc')->latest()->paginate(9)->withQueryString();

        // Count totals for tab badges
        $counts = [
            'all' => ChapPost::where('is_published', true)->count(),
            'tutorial' => ChapPost::where('is_published', true)->where('category', 'tutorial')->count(),
            'news' => ChapPost::where('is_published', true)->where('category', 'news')->count(),
            'announcement' => ChapPost::where('is_published', true)->where('category', 'announcement')->count(),
            'testimony' => ChapPost::where('is_published', true)->where('category', 'testimony')->count(),
        ];

        return view('hub.index', compact('posts', 'pinnedPost', 'category', 'mediaType', 'search', 'counts'));
    }

    /**
     * Display single post detail.
     */
    public function show($slug)
    {
        $post = ChapPost::with('author')
            ->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        // Increment views count safely
        $post->increment('views_count');

        // Related posts in same category
        $relatedPosts = ChapPost::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->where('category', $post->category)
            ->latest()
            ->take(3)
            ->get();

        return view('hub.show', compact('post', 'relatedPosts'));
    }

    /**
     * Toggle like on a hub post via AJAX.
     */
    public function toggleLike($id)
    {
        $post = ChapPost::where('is_published', true)->findOrFail($id);
        $sessionKey = "liked_hub_post_{$id}";

        if (session()->has($sessionKey)) {
            $post->decrement('likes_count');
            session()->forget($sessionKey);
            $liked = false;
        } else {
            $post->increment('likes_count');
            session()->put($sessionKey, true);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $post->likes_count,
        ]);
    }
}
