<?php

namespace Database\Seeders;

use App\Models\SiteNews;
use App\Models\SiteNewsLike;
use App\Models\SiteNewsShare;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteNewsSeeder extends Seeder
{
    /**
     * Run the database seeds for Site News.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first() ?: User::first();
        $customerCare = User::where('role', 'customer_care')->first() ?: $admin;

        $adminId = $admin ? $admin->id : 1;
        $ccId = $customerCare ? $customerCare->id : $adminId;

        $newsItems = [
            [
                'type' => 'text_photo',
                'title' => '🌟 ChapConnect VIP Gala & Creative Showcase 2026',
                'body' => "Tunayo furaha kuwatangazia tamasha lijalo la ChapConnect Creative Gala jijini Dar es Salaam! Wasanii wote waliojisajili kwenye kifurushi cha VIP na Premium watapata nafasi adimu ya kukutana na waongozaji (directors), maprodyuza wakuu wa filamu, na wadhamini wa kibiashara.\n\nJiandae kung'aa jukwaani na kuungana na wadau wakuu wa tasnia ya burudani Afrika Mashariki.",
                'image_path' => '/storage/media/photos/SojiVpx89fJuFbu6NFQB5vbPTh5H8JEA4nL48KG5.webp',
                'video_url' => null,
                'video_path' => null,
                'user_id' => $adminId,
                'priority' => 20,
                'is_published' => true,
                'views_count' => 380,
                'likes' => 45,
                'shares' => 18,
            ],
            [
                'type' => 'video',
                'title' => '🎥 Studio Live Sessions: Behind The Scenes na Wasanii Wetu',
                'body' => null,
                'image_path' => null,
                'video_url' => 'https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4',
                'video_path' => null,
                'user_id' => $ccId,
                'priority' => 16,
                'is_published' => true,
                'views_count' => 512,
                'likes' => 64,
                'shares' => 27,
            ],
            [
                'type' => 'text',
                'title' => '📢 Tahadhari Muhimu ya Usalama & Ulinzi wa Akaunti Yako',
                'body' => "Wapendwa wasanii na watumiaji wa ChapConnect, tafadhali zingatieni kuwa timu ya ChapConnect haitawahi kukuomba nenosiri (password) lako au PIN yako ya fedha kupitia SMS au simu.\n\nKama umepokea ujumbe wowote wa kutiliwa shaka, tafadhali ripoti mara moja kwa dawati letu la Customer Care kupitia kitufe cha 'Need Help' kwenye dashibodi yako ili tulinde usalama wa jamii yetu.",
                'image_path' => null,
                'video_url' => null,
                'video_path' => null,
                'user_id' => $ccId,
                'priority' => 14,
                'is_published' => true,
                'views_count' => 290,
                'likes' => 31,
                'shares' => 12,
            ],
            [
                'type' => 'text_video',
                'title' => '🎶 Mbinu 5 za Kuboresha Wasifu Wako Ili Kupata Dili Nyingi Zaidi',
                'body' => "Tazama video hii fupi inayoelezea jinsi unavyoweza kufanya profile yako ivutie wateja zaidi:\n1. Tumia picha zenye mwanga mzuri na ubora wa juu.\n2. Weka video fupi inayoonyesha kipaji chako ukiwa jukwaani au studio.\n3. Jaza maelezo mafupi lakini yenye nguvu kuhusu uzoefu wako.\n4. Jibu maombi ya wateja (Contact Requests) ndani ya saa chache.\n5. Shiriki kiungo cha wasifu wako kwenye mitandao yako ya kijamii.",
                'image_path' => null,
                'video_url' => 'https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4',
                'video_path' => null,
                'user_id' => $adminId,
                'priority' => 11,
                'is_published' => true,
                'views_count' => 440,
                'likes' => 58,
                'shares' => 22,
            ],
            [
                'type' => 'photo',
                'title' => '📸 Picha ya Wiki: Wasanii Wetu Wakifanya Mazoezi ya Muziki',
                'body' => null,
                'image_path' => '/storage/media/photos/WhWRrJWVeQZ7qcuXi80t59cgFpfjfqObDEFu7JEN.webp',
                'video_url' => null,
                'video_path' => null,
                'user_id' => $ccId,
                'priority' => 8,
                'is_published' => true,
                'views_count' => 165,
                'likes' => 29,
                'shares' => 7,
            ],
            [
                'type' => 'text',
                'title' => '🚀 Maboresho Mapya: Kasi ya Kupakia Video za TikTok Imeongezeka',
                'body' => "Tumeboresha zaidi mifumo yetu ya kucheza video za mtandaoni (TikTok, YouTube, na Vimeo). Sasa wageni wanaotembelea wasifu wako wanaweza kutazama video zako papo hapo kwa kasi ya juu bila kukatika.\n\nJaribu sasa kwa kuweka viungo vya video zako mpya kwenye dashibodi yako!",
                'image_path' => null,
                'video_url' => null,
                'video_path' => null,
                'user_id' => $adminId,
                'priority' => 6,
                'is_published' => true,
                'views_count' => 310,
                'likes' => 43,
                'shares' => 16,
            ],
            [
                'type' => 'text_photo',
                'title' => '🏆 Fursa ya Usahili Wazi: Wanamitindo 10 na Waigizaji 5 Wanahitajika',
                'body' => "Kuna kampuni kubwa ya utayarishaji wa matangazo inayotafuta wanamitindo na waigizaji wapya kwa ajili ya kampeni ya msimu ujao. Usahili unafanyika mtandaoni kupitia ChapConnect.\n\nWale wote wenye akaunti zilizohakikiwa (Active Verified) watapewa kipaumbele cha kwanza. Hakikisha wasifu wako una picha za hivi karibuni!",
                'image_path' => '/storage/media/photos/x0fBg7WNfPWDMpbGWoPcicUEFPDNK5mQhBtjxzPE.webp',
                'video_url' => null,
                'video_path' => null,
                'user_id' => $ccId,
                'priority' => 5,
                'is_published' => true,
                'views_count' => 525,
                'likes' => 78,
                'shares' => 34,
            ],
        ];

        foreach ($newsItems as $data) {
            $likesCount = $data['likes'];
            $sharesCount = $data['shares'];
            unset($data['likes'], $data['shares']);

            $news = SiteNews::firstOrCreate(
                ['title' => $data['title']],
                $data
            );

            // Seed realistic interaction records if none exist yet
            if ($news->likes()->count() === 0) {
                for ($k = 0; $k < $likesCount; $k++) {
                    SiteNewsLike::create([
                        'site_news_id' => $news->id,
                        'user_id' => null,
                        'ip_address' => '192.168.1.' . rand(10, 240),
                        'device_fingerprint' => md5('seed_device_' . $news->id . '_' . $k),
                    ]);
                }
            }

            if ($news->shares()->count() === 0) {
                $platforms = ['whatsapp', 'facebook', 'twitter', 'copy_link'];
                for ($s = 0; $s < $sharesCount; $s++) {
                    SiteNewsShare::create([
                        'site_news_id' => $news->id,
                        'user_id' => null,
                        'platform' => $platforms[array_rand($platforms)],
                        'ip_address' => '192.168.1.' . rand(10, 240),
                        'device_fingerprint' => md5('seed_share_' . $news->id . '_' . $s),
                    ]);
                }
            }
        }

        $this->command?->info('Site News seeded successfully with all 5 formats (text, photo, video, text_photo, text_video)!');
    }
}
