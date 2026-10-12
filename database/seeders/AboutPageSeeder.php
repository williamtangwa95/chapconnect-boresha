<?php

namespace Database\Seeders;

use App\Models\AboutPost;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Database\Seeder;

class AboutPageSeeder extends Seeder
{
    /**
     * Seed About Us page settings and initial posts.
     */
    public function run(): void
    {
        $defaults = [
            'about_hero_badge' => 'Empowering Creators, Performers & Visionaries Across East Africa',
            'about_hero_title' => 'Connecting Talents with Limitless Opportunities',
            'about_hero_subtitle' => 'ChapConnect is the next-generation creative talent directory and verified networking platform. We help musicians, dancers, comedians, models, MCs, visual artists, and performers showcase their artistry and connect directly with clients, promoters, event planners, and audiences worldwide.',
            'about_hero_btn_explore' => 'Explore Talents',
            'about_hero_btn_join' => 'Join as a Talent',

            'about_story_badge' => 'Who We Are',
            'about_story_title' => 'Building the Future of Talent Discovery & Direct Booking',
            'about_story_p1' => 'In East Africa and beyond, millions of remarkably talented people go unnoticed simply because they lack professional digital presence and direct access to clients, brands, event managers, and entertainment agencies.',
            'about_story_p2' => 'ChapConnect was created to solve this challenge. We provide every creative individual—from budding artists to seasoned professionals—with an all-in-one digital portfolio where they can present verified bios, high-resolution photo galleries, performance videos, and direct contact details.',
            'about_highlight_1' => 'Zero middleman commission',
            'about_highlight_2' => 'Verified contact credentials',
            'about_highlight_3' => 'High-definition media playback',
            'about_highlight_4' => 'Swahili & English interface',
            'about_visual_brand_subtitle' => 'Official Creative Network • Tanzania',
            'about_visual_badge_1' => 'Active Community Content Moderation',
            'about_visual_badge_2' => 'Instant WhatsApp & Telephone Direct Connect',
            'about_visual_badge_3' => 'Global Visibility for Local Tanzanian Creators',
            'about_visual_badge_4' => 'Dedicated Helpdesk & Friendly Staff Care',

            'about_mission_title' => 'Our Mission',
            'about_mission_text' => 'To empower every creative individual across East Africa with a world-class digital platform to showcase their gifts, gain authentic visibility, and monetize their skills through friction-free, direct connections with clients and audiences worldwide.',
            'about_vision_title' => 'Our Vision',
            'about_vision_text' => 'To become the most comprehensive, respected, and vibrant digital ecosystem for creative discovery, cross-border talent booking, and cultural empowerment throughout Africa.',

            'about_pillars_badge' => 'Why Choose ChapConnect',
            'about_pillars_title' => 'Designed for Creators & Clients Alike',
            'about_pillars_subtitle' => 'Everything you need to discover exceptional talent or build a thriving career in creative arts and entertainment.',
            'about_pillar_1_title' => 'Direct Contact, Zero Middlemen',
            'about_pillar_1_desc' => 'Clients and organizers contact talents directly via WhatsApp, phone call, or official inquiry with no hidden fees or excessive brokerage commissions.',
            'about_pillar_2_title' => 'Showcase Full Portfolio',
            'about_pillar_2_desc' => 'Upload and manage high-definition photos, YouTube and direct performance videos, press announcements, and social handles in one elegant link.',
            'about_pillar_3_title' => 'Bilingual & Inclusive',
            'about_pillar_3_desc' => 'Full dual-language support in Kiswahili and English, ensuring equal accessibility and high engagement for local and international users.',
            'about_pillar_4_title' => 'Safe & Moderated Platform',
            'about_pillar_4_desc' => 'Continuous moderation, secure authentication, dedicated customer care ticketing, and verified user controls protect our vibrant creative community.',

            'about_workflow_badge' => 'How ChapConnect Works',
            'about_workflow_title' => 'Simple 3-Step Journey to Success',
            'about_workflow_subtitle' => 'Whether you are offering a talent or looking to hire one, ChapConnect makes the process seamless and transparent.',
            'about_step_talent_1_title' => '1. Create Your Profile',
            'about_step_talent_1_text' => 'Register your account, choose your primary talent category, and write an engaging professional bio.',
            'about_step_talent_2_title' => '2. Upload Media & Work',
            'about_step_talent_2_text' => 'Add your best photos, performance videos, recent achievements, and verified contact channels.',
            'about_step_talent_3_title' => '3. Get Booked & Discovered',
            'about_step_talent_3_text' => 'Publish your portfolio live on ChapConnect, receive direct client requests, and expand your audience.',
            'about_step_client_1_title' => '1. Search & Filter',
            'about_step_client_1_text' => 'Browse talents by category (Music, Dance, MCs, Comedy, etc.), location, and keyword with instantaneous search.',
            'about_step_client_2_title' => '2. Review Portfolios',
            'about_step_client_2_text' => 'Watch video reels, view photo shoots, check community reviews, and verify real work before reaching out.',
            'about_step_client_3_title' => '3. Contact Directly',
            'about_step_client_3_text' => 'Connect instantly with the talent via WhatsApp or direct phone call to agree on terms and book events.',

            'office_address' => 'Posta / CBD, Dar es Salaam, Tanzania',
            'office_city' => 'Dar es Salaam, Tanzania',
            'office_landmark' => 'Near Clock Tower, Posta, Dar es Salaam',
            'office_hours' => 'Monday - Friday: 08:00 - 18:00 | Saturday: 09:00 - 15:00',
            'support_phone' => '+255 710 383 352',
            'support_email' => 'support@chapconnect.com',
            'whatsapp_number' => '+255 710 383 352',

            'about_support_badge' => 'Customer Support & Helpdesk',
            'about_support_title' => 'Get in Touch with Our Team',
            'about_support_text' => 'Have questions or need assistance? Our customer care desk is always here to support you. Whether you want help publishing your profile or need assistance with event bookings, our staff is ready to assist.',

            'about_cta_title' => 'Ready to Showcase Your Talent?',
            'about_cta_subtitle' => 'Join thousands of creatives and build your verified digital portfolio today.',
        ];

        foreach ($defaults as $key => $val) {
            if (!SystemSetting::where('key', $key)->exists()) {
                SystemSetting::create(['key' => $key, 'value' => $val]);
            }
        }

        // Seed 2 initial official posts if none exist
        if (AboutPost::count() === 0) {
            $staff = User::whereIn('role', ['admin', 'customer_care'])->first();

            AboutPost::create([
                'user_id' => $staff?->id,
                'author_name' => 'Customer Care & Admin Desk',
                'author_role' => 'Customer Care',
                'title' => 'Official Launch of ChapConnect Verified Portfolios',
                'badge' => 'Announcement',
                'content' => 'We are thrilled to officially welcome artists, creators, dancers, video directors, and event organizers across Tanzania and East Africa to ChapConnect. Our digital portal is now open for artist registration, media uploads, and direct bookings with zero brokerage commission.',
                'image_path' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=800&auto=format&fit=crop&q=80',
                'is_published' => true,
            ]);

            AboutPost::create([
                'user_id' => $staff?->id,
                'author_name' => 'Support & Community Team',
                'author_role' => 'Support Desk',
                'title' => '24/7 WhatsApp & Helpdesk Support Now Available',
                'badge' => 'Community Update',
                'content' => 'Need help setting up your profile, uploading high-definition media, or verifying your contact details? Our dedicated customer care staff is available via WhatsApp and in-app support tickets to guide you through every step of growing your audience.',
                'image_path' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&auto=format&fit=crop&q=80',
                'is_published' => true,
            ]);
        }
    }
}
