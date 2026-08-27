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
     * Toggle like status for a talent profile.
     */
    public function toggleLike(Request $request, $talentId)
    {
        $talent = User::findOrFail($talentId);
        $userId = Auth::id();
        $ip = $request->ip();

        $query = Like::where('talent_id', $talentId);
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('ip_address', $ip)->whereNull('user_id');
        }

        $existing = $query->first();

        if ($existing) {
            $existing->delete();
            $liked = false;
        } else {
            Like::create([
                'user_id' => $userId,
                'talent_id' => $talentId,
                'ip_address' => $ip,
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
     * Toggle follow status for a talent profile.
     */
    public function toggleFollow(Request $request, $talentId)
    {
        $talent = User::findOrFail($talentId);
        $userId = Auth::id();
        $ip = $request->ip();

        $query = Follower::where('talent_id', $talentId);
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('ip_address', $ip)->whereNull('user_id');
        }

        $existing = $query->first();

        if ($existing) {
            $existing->delete();
            $following = false;
        } else {
            Follower::create([
                'user_id' => $userId,
                'talent_id' => $talentId,
                'ip_address' => $ip,
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
    public function storeComment(Request $request, $talentId)
    {
        $talent = User::findOrFail($talentId);

        $request->validate([
            'comment' => 'required|string|max:1000',
            'author_name' => 'nullable|string|max:255',
        ]);

        $authorName = $request->input('author_name');
        if (!$authorName) {
            $authorName = Auth::check() ? Auth::user()->name : 'Guest Visitor';
        }

        Comment::create([
            'user_id' => Auth::id(),
            'talent_id' => $talentId,
            'author_name' => $authorName,
            'comment' => $request->input('comment'),
        ]);

        if ($request->wantsJson()) {
            $commentsCount = Comment::where('talent_id', $talentId)->count();
            return response()->json([
                'success' => true,
                'message' => 'Comment posted successfully!',
                'count' => $commentsCount,
            ]);
        }

        return redirect()->back()->with('success', 'Your comment has been posted successfully!');
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
                $likeQuery->where('ip_address', $ip)->whereNull('user_id');
            }
            $isLiked = $likeQuery->exists();

            $followQuery = Follower::where('talent_id', $id);
            if ($userId) {
                $followQuery->where('user_id', $userId);
            } else {
                $followQuery->where('ip_address', $ip)->whereNull('user_id');
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
