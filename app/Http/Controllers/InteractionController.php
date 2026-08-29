<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Follower;
use App\Models\Like;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InteractionController extends Controller
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
     * Toggle like / dislike status for a talent profile.
     */
    public function toggleLike(Request $request, $talentId)
    {
        $talent = User::findOrFail($talentId);
        $userId = Auth::id();
        $ip = $request->ip();
        $deviceFingerprint = $this->getDeviceFingerprint($request);

        $query = Like::where('talent_id', $talentId);
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where(function($q) use ($ip, $deviceFingerprint) {
                $q->where('ip_address', $ip);
                if ($deviceFingerprint) {
                    $q->orWhere('device_fingerprint', $deviceFingerprint);
                }
            })->whereNull('user_id');
        }

        $existing = $query->first();

        if ($existing) {
            // Dislike / Unlike action
            $existing->delete();
            $liked = false;
        } else {
            // Like action
            Like::create([
                'user_id' => $userId,
                'talent_id' => $talentId,
                'ip_address' => $ip,
                'device_fingerprint' => $deviceFingerprint,
            ]);
            $liked = true;
        }

        $count = Like::where('talent_id', $talentId)->count();

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'count' => $count,
        ]);
    }

    /**
     * Toggle follow / unfollow status for a talent profile.
     */
    public function toggleFollow(Request $request, $talentId)
    {
        $talent = User::findOrFail($talentId);
        $userId = Auth::id();
        $ip = $request->ip();
        $deviceFingerprint = $this->getDeviceFingerprint($request);

        $query = Follower::where('talent_id', $talentId);
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where(function($q) use ($ip, $deviceFingerprint) {
                $q->where('ip_address', $ip);
                if ($deviceFingerprint) {
                    $q->orWhere('device_fingerprint', $deviceFingerprint);
                }
            })->whereNull('user_id');
        }

        $existing = $query->first();

        if ($existing) {
            // Unfollow action
            $existing->delete();
            $following = false;
        } else {
            // Follow action
            Follower::create([
                'user_id' => $userId,
                'talent_id' => $talentId,
                'ip_address' => $ip,
                'device_fingerprint' => $deviceFingerprint,
            ]);
            $following = true;
        }

        $count = Follower::where('talent_id', $talentId)->count();

        return response()->json([
            'success' => true,
            'following' => $following,
            'count' => $count,
        ]);
    }

    /**
     * Submit a comment on a talent profile.
     */
    /**
     * Submit a comment or reply on a talent profile.
     */
    public function storeComment(Request $request, $talentId)
    {
        $talent = User::findOrFail($talentId);
        $userId = Auth::id();
        $ip = $request->ip();
        $deviceFingerprint = $this->getDeviceFingerprint($request);

        $request->validate([
            'comment' => 'required|string|max:1000',
            'author_name' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $parentId = $request->input('parent_id');
        if ($parentId) {
            // Ensure parent comment belongs to this talent profile
            $parentComment = Comment::where('id', $parentId)->where('talent_id', $talentId)->first();
            if (!$parentComment) {
                if ($request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'Invalid parent comment.'], 400);
                }
                return redirect()->back()->with('error', 'Invalid parent comment.');
            }
        }

        $authorName = $request->input('author_name');
        if (!$authorName) {
            $authorName = Auth::check() ? Auth::user()->name : 'Guest Visitor';
        }

        Comment::create([
            'user_id' => $userId,
            'talent_id' => $talentId,
            'parent_id' => $parentId,
            'author_name' => $authorName,
            'comment' => $request->input('comment'),
            'ip_address' => $ip,
            'device_fingerprint' => $deviceFingerprint,
        ]);

        if ($request->wantsJson()) {
            $commentsCount = Comment::where('talent_id', $talentId)->count();
            return response()->json([
                'success' => true,
                'message' => $parentId ? 'Reply posted successfully!' : 'Comment posted successfully!',
                'count' => $commentsCount,
            ]);
        }

        return redirect()->back()->with('success', $parentId ? 'Your reply has been posted successfully!' : 'Your comment has been posted successfully!');
    }

    /**
     * Delete a comment (Uncomment action)
     */
    public function deleteComment(Request $request, $commentId)
    {
        // If user is not authenticated, redirect to login page
        if (!Auth::check()) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'redirect' => route('login'),
                    'message' => 'Please sign in to manage or delete comments.'
                ], 401);
            }
            return redirect()->route('login')->with('info', 'Please sign in to manage or delete comments.');
        }

        $comment = Comment::findOrFail($commentId);
        $userId = Auth::id();
        $user = Auth::user();

        // Check authorization:
        // 1. Current user is comment author ($comment->user_id == $userId)
        // 2. Current user is profile owner ($comment->talent_id == $userId) - can delete comments violating rules
        // 3. Current user is admin ($user->role === 'admin')
        $isAuthorized = false;
        if ($userId && ($comment->user_id == $userId || $comment->talent_id == $userId || $user->role === 'admin')) {
            $isAuthorized = true;
        } elseif (!$comment->user_id && ($comment->ip_address == $request->ip() || ($this->getDeviceFingerprint($request) && $comment->device_fingerprint == $this->getDeviceFingerprint($request)))) {
            $isAuthorized = true;
        }

        if (!$isAuthorized) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized to delete this comment.'], 403);
            }
            return redirect()->back()->with('error', 'Unauthorized to delete this comment.');
        }

        $talentId = $comment->talent_id;
        $comment->delete();

        $commentsCount = Comment::where('talent_id', $talentId)->count();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully.',
                'count' => $commentsCount,
            ]);
        }

        return redirect()->back()->with('success', 'Comment deleted successfully!');
    }

    /**
     * Get real-time status and counts for interaction buttons on public talent cards.
     */
    public function getStatuses(Request $request)
    {
        $talentIds = $request->input('talent_ids', []);
        if (!is_array($talentIds)) {
            $talentIds = explode(',', $talentIds);
        }

        $userId = Auth::id();
        $ip = $request->ip();
        $deviceFingerprint = $this->getDeviceFingerprint($request);

        $statuses = [];

        foreach ($talentIds as $id) {
            $id = (int)$id;
            if ($id <= 0) continue;

            $likesCount = Like::where('talent_id', $id)->count();
            $followersCount = Follower::where('talent_id', $id)->count();
            $commentsCount = Comment::where('talent_id', $id)->count();

            $likeQuery = Like::where('talent_id', $id);
            if ($userId) {
                $likeQuery->where('user_id', $userId);
            } else {
                $likeQuery->where(function($q) use ($ip, $deviceFingerprint) {
                    $q->where('ip_address', $ip);
                    if ($deviceFingerprint) {
                        $q->orWhere('device_fingerprint', $deviceFingerprint);
                    }
                })->whereNull('user_id');
            }
            $isLiked = $likeQuery->exists();

            $followQuery = Follower::where('talent_id', $id);
            if ($userId) {
                $followQuery->where('user_id', $userId);
            } else {
                $followQuery->where(function($q) use ($ip, $deviceFingerprint) {
                    $q->where('ip_address', $ip);
                    if ($deviceFingerprint) {
                        $q->orWhere('device_fingerprint', $deviceFingerprint);
                    }
                })->whereNull('user_id');
            }
            $isFollowing = $followQuery->exists();

            $statuses[$id] = [
                'likes_count' => $likesCount,
                'followers_count' => $followersCount,
                'comments_count' => $commentsCount,
                'is_liked' => $isLiked,
                'is_following' => $isFollowing,
            ];
        }

        return response()->json([
            'success' => true,
            'statuses' => $statuses,
        ]);
    }
}
