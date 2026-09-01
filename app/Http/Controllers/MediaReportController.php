<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\MediaReport;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MediaReportController extends Controller
{
    /**
     * Submit an inappropriate content report for a media item.
     */
    public function report(Request $request, $mediaId)
    {
        $request->validate([
            'reason'  => 'required|string|in:nudity_nsfw,violence,copyright,spam,harassment,other',
            'details' => 'nullable|string|max:500',
        ]);

        $media = Media::findOrFail($mediaId);
        $user = Auth::user();
        $ip = $request->ip();

        // Prevent duplicate spam reporting from same user/IP for same media item
        $alreadyReported = MediaReport::where('media_id', $media->id)
            ->where(function ($q) use ($user, $ip) {
                if ($user) {
                    $q->where('reporter_user_id', $user->id);
                } else {
                    $q->where('reporter_ip', $ip);
                }
            })
            ->exists();

        if ($alreadyReported) {
            return response()->json([
                'success' => false,
                'message' => __('You have already submitted a report for this item. Our moderation team is reviewing it.'),
            ], 422);
        }

        MediaReport::create([
            'media_id'          => $media->id,
            'reporter_user_id'  => $user?->id,
            'reporter_ip'       => $ip,
            'reason'            => $request->reason,
            'details'           => $request->details,
            'status'            => 'pending',
        ]);

        $media->increment('report_count');

        // Community Auto-Hide Threshold: If 3 or more reports received, immediately hide pending admin review
        if ($media->report_count >= 3) {
            $media->update([
                'is_visible'        => false,
                'moderation_status' => 'flagged',
                'moderation_reason' => 'Auto-hidden: Received ' . $media->report_count . ' community reports for ' . $request->reason,
            ]);
        }

        // Notify Admin and Customer Care Staff of Content Report / NSFW Queue
        $staffMembers = User::whereIn('role', ['admin', 'customer_care'])->get();
        $reasonLabel = str_replace('_', ' ', strtoupper($request->reason));
        foreach ($staffMembers as $staff) {
            Notification::create([
                'user_id' => $staff->id,
                'type'    => 'content_moderation_report',
                'title'   => "🚨 Content Reported: #{$media->id} ({$media->type})",
                'message' => "Media item #{$media->id} by '{$media->user?->name}' was reported for '{$reasonLabel}'. Total reports: {$media->report_count}.",
                'link'    => route('admin.moderation.queue'),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => __('Thank you. Your report has been received and our team will review the content immediately.'),
        ]);
    }
}
