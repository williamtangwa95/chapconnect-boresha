<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Comment $comment): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Comment $comment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(?User $user, Comment $comment): bool
    {
        // Authenticated user checks
        if ($user) {
            if ($comment->user_id && $comment->user_id == $user->id) {
                return true;
            }
            if ($comment->talent_id && $comment->talent_id == $user->id) {
                return true;
            }
            if (isset($user->role) && $user->role === 'admin') {
                return true;
            }
            return false;
        }

        // Guest (unauthenticated) checks using IP and device fingerprint
        $ip = request()->ip();
        $fingerprint = request()->cookie('device_token');
        if (!$comment->user_id) {
            if ($comment->ip_address == $ip) {
                return true;
            }
            if ($fingerprint && $comment->device_fingerprint == $fingerprint) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Comment $comment): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Comment $comment): bool
    {
        return false;
    }
}
