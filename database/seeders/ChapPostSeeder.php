<?php

namespace Database\Seeders;

use App\Models\ChapPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class ChapPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::whereIn('role', ['admin', 'customer_care'])->first() ?? User::first();
        $adminId = $admin ? $admin->id : 1;

        if (ChapPost::count() === 0) {
            ChapPost::create([
                'user_id' => $adminId,
                'title' => 'Welcome to ChapConnect: Tanzania\'s Premier Creative Talent Marketplace',
                'slug' => 'welcome-to-chapconnect-marketplace',
                'category' => 'tutorial',
                'media_type' => 'article',
                'summary' => 'Discover how ChapConnect helps artists, dancers, actors, and media creators build verified digital portfolios and connect with paying clients.',
                'content' => "ChapConnect is built with a singular vision: to empower East African talents by providing a centralized, trusted, and modern digital platform to showcase their craft.

Whether you are a singer looking for record label visibility, a dancer seeking event bookings, a comedian growing your audience, or a host/MC booking corporate gigs, ChapConnect provides:
1. **Verified Talent Portfolio**: Upload high-resolution photos, direct MP4 video clips, YouTube/TikTok showcases, and press releases.
2. **Direct Client Engagement**: Allow event managers, producers, and individuals to send direct booking inquiries with complete transparency.
3. **Performance Monetization**: Earn payouts directly through platform milestones and client connections.

Explore our tutorials to learn how to optimize your profile, manage portfolio media, and grow your digital presence.",
                'is_pinned' => true,
                'is_published' => true,
                'views_count' => 142,
                'likes_count' => 38,
            ]);

            ChapPost::create([
                'user_id' => $adminId,
                'title' => 'Guide: 5 Steps to Complete a 100% Verified Profile for Maximum Bookings',
                'slug' => 'guide-5-steps-complete-verified-profile',
                'category' => 'tutorial',
                'media_type' => 'article',
                'summary' => 'Learn the proven strategies to complete your talent profile, add high quality portfolio media, and attract corporate and wedding clients.',
                'content' => "Profiles with complete biographies, active social links, and multiple portfolio media receive 4x more direct booking requests.

Follow these 5 essential steps:
- **Step 1: Set a High-Resolution Stage/Portrait Picture**: Clear lighting and high resolution establish instant credibility.
- **Step 2: Link Active Social Media**: Add your YouTube, Instagram, TikTok, and Facebook channels so clients can follow your latest performances.
- **Step 3: Post High-Quality Photos & Videos**: Use the 'Post Photos' and 'Post Videos' tools to showcase real stage performances, studio sessions, and showreels.
- **Step 4: Craft a Compelling Bio**: Specify your genres, experience level, past notable events, and equipment/setup capabilities.
- **Step 5: Keep Pricing & Contact Preferences Up-to-Date**: Ensure your phone, WhatsApp, and booking email are verified for rapid client responses.",
                'is_pinned' => false,
                'is_published' => true,
                'views_count' => 89,
                'likes_count' => 24,
            ]);

            ChapPost::create([
                'user_id' => $adminId,
                'title' => 'Platform Update: Instant Client Connect & Video Optimization Now Live',
                'slug' => 'platform-update-instant-client-connect-live',
                'category' => 'news',
                'media_type' => 'article',
                'summary' => 'We are excited to roll out new high-speed media processing, enabling up to 100MB video and photo uploads with real-time browser progress.',
                'content' => "ChapConnect continues to evolve with our latest platform infrastructure updates:
- **100MB Multi-Media Support**: Talents can now post larger high-definition showreel clips and high-res photography portfolios.
- **Enhanced Mobile Drawer Navigation**: Smoother page switching, instant Swahili/English language toggles, and unified main controls.
- **Real-Time Upload Progress**: Live progress bars ensure you always know exactly how much data is uploaded.

Stay tuned for upcoming audio podcast interviews and live masterclass announcements!",
                'is_pinned' => false,
                'is_published' => true,
                'views_count' => 112,
                'likes_count' => 45,
            ]);

            ChapPost::create([
                'user_id' => $adminId,
                'title' => 'Success Testimony: How DJ Blackfox Booked 12 Corporate Events in 2 Months',
                'slug' => 'success-testimony-dj-blackfox-booking-growth',
                'category' => 'testimony',
                'media_type' => 'article',
                'summary' => 'Read how a local Dar es Salaam DJ leveraged his ChapConnect verified portfolio to land major corporate festival bookings.',
                'content' => "\"Before joining ChapConnect, I had to manually send Google Drive links and WhatsApp videos to every event promoter. Many clients never even opened the links.

Once I set up my ChapConnect profile with my live club mixes, DJ showreel, and verified contact details, promoters started booking me directly through the platform. In just 60 days, I secured 12 corporate event contracts and doubled my booking rate!\"

— *DJ Blackfox, Professional DJ & Sound Producer (Dar es Salaam)*",
                'is_pinned' => false,
                'is_published' => true,
                'views_count' => 205,
                'likes_count' => 67,
            ]);
        }
    }
}
