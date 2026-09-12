<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\MediaComment;
use App\Models\MediaLike;
use App\Models\MediaShare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MediaInteractionController extends Controller
{
    /**
     * Helper to get or generate device fingerprint token
     */
    private function getDeviceFingerprint(Request $request): string
    {
        $fingerprint = $request->cookie('device_token');
        if (!$fingerprint) {
            $fingerprint = md5($request->ip() . ($request->header('User-Agent') ?? '') . 'chap_device');
            cookie()->queue('device_token', $fingerprint, 525600); // 1 year
        }
        return $fingerprint;
    }

    /**
     * Toggle like / dislike status for a media post.
     */
    public function toggleLike(Request $request, $id)
    {
        $media = Media::findOrFail($id);
        $userId = Auth::id();
        $ip = $request->ip();
        $deviceFingerprint = $this->getDeviceFingerprint($request);

        $query = MediaLike::where('media_id', $id);
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
            MediaLike::create([
                'media_id' => $id,
                'user_id' => $userId,
                'ip_address' => $ip,
                'device_fingerprint' => $deviceFingerprint,
            ]);
            $liked = true;
        }

        $count = MediaLike::where('media_id', $id)->count();

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'count' => $count,
        ]);
    }

    /**
     * Submit a comment or reply on a media post.
     */
    public function storeComment(Request $request, $id)
    {
        $media = Media::findOrFail($id);
        $userId = Auth::id();
        $ip = $request->ip();
        $deviceFingerprint = $this->getDeviceFingerprint($request);

        $request->validate([
            'comment' => 'required|string|max:1000',
            'author_name' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:media_comments,id',
        ]);

        $parentId = $request->input('parent_id');
        if ($parentId) {
            $parentComment = MediaComment::where('id', $parentId)->where('media_id', $id)->first();
            if (!$parentComment) {
                return response()->json(['success' => false, 'message' => 'Invalid parent comment.'], 400);
            }
        }

        $authorName = $request->input('author_name');
        if (!$authorName) {
            $authorName = Auth::check() ? Auth::user()->name : 'Guest Visitor';
        }

        $newComment = MediaComment::create([
            'media_id' => $id,
            'user_id' => $userId,
            'parent_id' => $parentId,
            'author_name' => $authorName,
            'comment' => $request->input('comment'),
            'ip_address' => $ip,
            'device_fingerprint' => $deviceFingerprint,
        ]);

        $commentsCount = MediaComment::where('media_id', $id)->count();

        return response()->json([
            'success' => true,
            'message' => $parentId ? 'Reply posted successfully!' : 'Comment posted successfully!',
            'count' => $commentsCount,
            'comment' => [
                'id' => $newComment->id,
                'author_name' => $newComment->author_name,
                'comment' => $newComment->comment,
                'created_at_human' => $newComment->created_at->diffForHumans(),
                'user_avatar' => ($newComment->user && $newComment->user->profile_image)
                    ? asset($newComment->user->profile_image)
                    : null,
            ]
        ]);
    }

    /**
     * Fetch comments list for a media post.
     */
    public function fetchComments(Request $request, $id)
    {
        $media = Media::findOrFail($id);

        $comments = MediaComment::where('media_id', $id)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->latest()
            ->get()
            ->map(function ($c) {
                return [
                    'id' => $c->id,
                    'author_name' => $c->author_name,
                    'comment' => $c->comment,
                    'created_at_human' => $c->created_at->diffForHumans(),
                    'user_avatar' => ($c->user && $c->user->profile_image) ? asset($c->user->profile_image) : null,
                    'is_owner' => Auth::check() && (Auth::id() === $c->user_id || Auth::id() === $c->media->user_id),
                    'replies' => $c->replies->map(function ($r) {
                        return [
                            'id' => $r->id,
                            'author_name' => $r->author_name,
                            'comment' => $r->comment,
                            'created_at_human' => $r->created_at->diffForHumans(),
                            'user_avatar' => ($r->user && $r->user->profile_image) ? asset($r->user->profile_image) : null,
                        ];
                    }),
                ];
            });

        $totalCount = MediaComment::where('media_id', $id)->count();

        return response()->json([
            'success' => true,
            'comments' => $comments,
            'count' => $totalCount,
        ]);
    }

    /**
     * Delete a media comment.
     */
    public function deleteComment(Request $request, $commentId)
    {
        $comment = MediaComment::findOrFail($commentId);
        $user = Auth::user();

        // Check if user is author of comment, post owner, or admin/staff
        $isAuthor = $user && $user->id === $comment->user_id;
        $isPostOwner = $user && $user->id === $comment->media->user_id;
        $isStaff = $user && in_array($user->role, ['admin', 'customer_care', 'staff']);

        if (!$isAuthor && !$isPostOwner && !$isStaff) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action.'], 403);
        }

        $mediaId = $comment->media_id;
        $comment->delete();

        $count = MediaComment::where('media_id', $mediaId)->count();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully.',
            'count' => $count,
        ]);
    }

    /**
     * Record a share event for a media post.
     */
    public function recordShare(Request $request, $id)
    {
        $media = Media::findOrFail($id);
        $userId = Auth::id();
        $ip = $request->ip();
        $deviceFingerprint = $this->getDeviceFingerprint($request);
        $platform = $request->input('platform', 'general');

        MediaShare::create([
            'media_id' => $id,
            'user_id' => $userId,
            'platform' => $platform,
            'ip_address' => $ip,
            'device_fingerprint' => $deviceFingerprint,
        ]);

        $count = MediaShare::where('media_id', $id)->count();

        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }

    /**
     * Batch fetch interaction statuses (likes count, is_liked, comments count, shares count) for media IDs.
     */
    public function getStatuses(Request $request)
    {
        $mediaIds = $request->input('media_ids', []);
        if (!is_array($mediaIds)) {
            $mediaIds = array_filter(array_map('intval', explode(',', $mediaIds)));
        }

        $userId = Auth::id();
        $ip = $request->ip();
        $deviceFingerprint = $this->getDeviceFingerprint($request);

        $statuses = [];

        foreach ($mediaIds as $id) {
            $id = (int)$id;
            if ($id <= 0) continue;

            $likesCount = MediaLike::where('media_id', $id)->count();
            $commentsCount = MediaComment::where('media_id', $id)->count();
            $sharesCount = MediaShare::where('media_id', $id)->count();

            $likeQuery = MediaLike::where('media_id', $id);
            if ($userId) {
                $likeQuery->where('user_id', $userId);
            } else {
                $likeQuery->where(function ($q) use ($ip, $deviceFingerprint) {
                    $q->where('ip_address', $ip);
                    if ($deviceFingerprint) {
                        $q->orWhere('device_fingerprint', $deviceFingerprint);
                    }
                })->whereNull('user_id');
            }

            $statuses[$id] = [
                'likes_count' => $likesCount,
                'comments_count' => $commentsCount,
                'shares_count' => $sharesCount,
                'is_liked' => $likeQuery->exists(),
            ];
        }

        return response()->json([
            'success' => true,
            'statuses' => $statuses,
        ]);
    }

    /**
     * Load more recent media posts for the home sidebar feed.
     */
    public function loadMore(Request $request)
    {
        $offset = (int) $request->input('offset', 20);
        $limit = 20;

        // Fetch 1 extra item to check if more items exist without running invalid count() offset query
        $mediaItems = Media::publiclyVisible()
            ->whereHas('user', function ($q) {
                $q->where('role', 'user')
                  ->where('is_published', true);
            })
            ->with('user')
            ->withCount(['likes', 'comments', 'shares'])
            ->latest()
            ->skip($offset)
            ->take($limit + 1)
            ->get();

        $hasMore = $mediaItems->count() > $limit;
        if ($hasMore) {
            $mediaItems = $mediaItems->slice(0, $limit);
        }

        $html = view('partials.media-feed-items', ['recentMedia' => $mediaItems])->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'count' => $mediaItems->count(),
            'has_more' => $hasMore,
            'media_ids' => $mediaItems->pluck('id')->toArray(),
        ]);
    }
}


