<?php

namespace App\Http\Controllers;

use App\Models\AboutPost;
use App\Models\SystemSetting;
use App\Models\UserActivityLog;
use App\Services\ImageCompressor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class AboutCmsController extends Controller
{
    /**
     * Update About Us page settings in system_settings.
     */
    public function updateSettings(Request $request)
    {
        $fields = [
            // Hero Section
            'about_hero_badge',
            'about_hero_title',
            'about_hero_subtitle',
            'about_hero_btn_explore',
            'about_hero_btn_join',

            // Narrative / Who We Are
            'about_story_badge',
            'about_story_title',
            'about_story_p1',
            'about_story_p2',
            'about_highlight_1',
            'about_highlight_2',
            'about_highlight_3',
            'about_highlight_4',
            'about_visual_brand_subtitle',
            'about_visual_badge_1',
            'about_visual_badge_2',
            'about_visual_badge_3',
            'about_visual_badge_4',

            // Mission & Vision
            'about_mission_title',
            'about_mission_text',
            'about_vision_title',
            'about_vision_text',

            // Why Choose Us (Core Pillars)
            'about_pillars_badge',
            'about_pillars_title',
            'about_pillars_subtitle',
            'about_pillar_1_title',
            'about_pillar_1_desc',
            'about_pillar_2_title',
            'about_pillar_2_desc',
            'about_pillar_3_title',
            'about_pillar_3_desc',
            'about_pillar_4_title',
            'about_pillar_4_desc',

            // Workflow Steps
            'about_workflow_badge',
            'about_workflow_title',
            'about_workflow_subtitle',
            'about_step_talent_1_title',
            'about_step_talent_1_text',
            'about_step_talent_2_title',
            'about_step_talent_2_text',
            'about_step_talent_3_title',
            'about_step_talent_3_text',
            'about_step_client_1_title',
            'about_step_client_1_text',
            'about_step_client_2_title',
            'about_step_client_2_text',
            'about_step_client_3_title',
            'about_step_client_3_text',

            // Office & Contact Information
            'office_address',
            'office_city',
            'office_landmark',
            'office_hours',
            'support_phone',
            'support_email',
            'whatsapp_number',

            // Support & CTA
            'about_support_badge',
            'about_support_title',
            'about_support_text',
            'about_cta_title',
            'about_cta_subtitle',
        ];

        foreach ($request->except(['_token']) as $key => $value) {
            if (is_string($value) || is_numeric($value)) {
                SystemSetting::set($key, (string)$value);
            }
        }

        // Invalidate cached data for the About page
        Cache::forget('about_settings');
        Cache::forget('about_total_talents');
        Cache::forget('about_total_media');
        Cache::forget('about_posts');

        $staffUser = auth()->user();
        if (class_exists(UserActivityLog::class)) {
            UserActivityLog::log(
                'UPDATE',
                "{$staffUser->name} ({$staffUser->role}) updated public About Us page content settings (EN/SW).",
                ['user_id' => $staffUser->id]
            );
        }

        return redirect()->to(url()->previous() . '#about')
            ->with('success', 'About Us page content settings (English & Swahili) updated successfully in the database.');
    }

    /**
     * Store a new About Us announcement or official update post.
     */
    public function storePost(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_sw' => 'nullable|string|max:255',
            'badge' => 'nullable|string|max:60',
            'badge_sw' => 'nullable|string|max:60',
            'content' => 'required|string',
            'content_sw' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:102400',
        ]);

        $filePath = null;
        if ($request->hasFile('image')) {
            try {
                $path = ImageCompressor::compressAndStore(
                    $request->file('image'),
                    'about/posts',
                    1200,
                    1200,
                    82,
                    'webp'
                );
                $filePath = '/storage/' . $path;
            } catch (\Exception $e) {
                $file = $request->file('image');
                $filename = 'about_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('about/posts', $filename, 'public');
                $filePath = '/storage/' . $path;
            }
        }

        $user = auth()->user();
        $authorRole = ($user->role === 'admin' || $user->role === 'super_admin') ? 'Administration' : 'Customer Care Desk';

        $post = AboutPost::create([
            'user_id' => $user->id,
            'author_name' => $user->name,
            'author_role' => $authorRole,
            'title' => $request->title,
            'title_sw' => $request->title_sw,
            'badge' => $request->badge ?: 'Announcement',
            'badge_sw' => $request->badge_sw,
            'content' => $request->content,
            'content_sw' => $request->content_sw,
            'image_path' => $filePath,
            'is_published' => true,
        ]);

        Cache::forget('about_posts');

        if (class_exists(UserActivityLog::class)) {
            UserActivityLog::log(
                'CREATE',
                "{$user->name} published new About Page Announcement: \"{$post->title}\"",
                ['post_id' => $post->id]
            );
        }

        return redirect()->to(url()->previous() . '#about')
            ->with('success', 'New About page article published successfully.');
    }

    /**
     * Toggle the published status of an About Us post.
     */
    public function togglePost($id)
    {
        $post = AboutPost::findOrFail($id);
        $post->update([
            'is_published' => !$post->is_published,
        ]);

        Cache::forget('about_posts');

        $status = $post->is_published ? 'published' : 'hidden';
        return redirect()->to(url()->previous() . '#about')
            ->with('success', "About article visibility changed to {$status}.");
    }

    /**
     * Delete an About Us post.
     */
    public function deletePost($id)
    {
        $post = AboutPost::findOrFail($id);

        if ($post->image_path && !str_starts_with($post->image_path, 'http')) {
            $relativePath = str_replace('/storage/', '', $post->image_path);
            Storage::disk('public')->delete($relativePath);
        }

        $title = $post->title;
        $post->delete();

        Cache::forget('about_posts');

        if (class_exists(UserActivityLog::class)) {
            UserActivityLog::log(
                'DELETE',
                auth()->user()->name . " deleted About Page Announcement: \"{$title}\""
            );
        }

        return redirect()->to(url()->previous() . '#about')
            ->with('success', 'About page article removed successfully.');
    }
}
