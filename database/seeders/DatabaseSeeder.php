<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Follower;
use App\Models\Like;
use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database safely.
     *
     * This seeder is designed to be repeatable:
     * - Never truncates tables
     * - Never deletes existing records
     * - Never duplicates categories
     * - Never duplicates users
     * - Never duplicates media
     * - Never overwrites existing user passwords
     * - Safe to run multiple times
     */
    public function run(): void
    {
        DB::transaction(function () {

            /*
             * ============================================================
             * 1. SEED CATEGORIES
             * ============================================================
             */

            $categoriesList = [
                'videodirector' => 'Video Director',
                'musicproducer' => 'Music Producer',
                'musicartist' => 'Music Artist',
                'musicband' => 'Music Band',
                'videovixen' => 'Video Vixen',
                'dancer' => 'Dancer',
                'actor' => 'Actor',
                'journalist' => 'Journalist',
                'eventmc' => 'Event MC',
                'musicmc' => 'Music MC',
                'guitarist' => 'Guitarist',
                'pianist' => 'Pianist',
                'musicdj' => 'Music DJ',
                'contentcreator' => 'Content Creator',
                'talented' => 'Talented',
                'manager' => 'Manager',
                'management' => 'Management',
                'comedian' => 'Comedian',
                'onlinetv' => 'Online TV',
            ];

            foreach ($categoriesList as $slug => $name) {
                Category::firstOrCreate(
                    ['slug' => $slug],
                    ['name' => $name]
                );
            }

            /*
             * ============================================================
             * 2. SUPER ADMIN
             * ============================================================
             */

            $adminEmail = 'connectchap@gmail.com';

            $admin = User::where('email', $adminEmail)->first();

            if (!$admin) {

                $password = $this->command
                    ? $this->command->secret('Enter password for the new Super Admin')
                    : null;

                if (!$password) {
                    $password = env('SUPER_ADMIN_PASSWORD', 'admin12345678');
                }

                User::create([
                    'name' => 'Super Admin',
                    'email' => $adminEmail,
                    'password' => Hash::make($password),
                    'role' => 'admin',
                    'category' => 'management',
                    'category_label' => 'Administration',
                    'country' => 'East Africa Tanzania',
                ]);

                $this->command?->info('Super Admin created successfully.');
            } else {
                $this->command?->info('Super Admin already exists. No changes made to the account.');
            }

            /*
             * ============================================================
             * 3. TALENTS DATA (75 TOTAL REALISTIC TALENTS)
             * ============================================================
             */

            $talents = [

                [
                    'id' => 'zuuhtwalib',
                    'name' => 'Zuuh Twalib - PLANET FM',
                    'category' => 'journalist',
                    'category_label' => 'Journalist',
                    'description' => 'Experienced radio journalist and media presenter at Planet FM, dedicated to bringing the latest updates, culture news, and high-energy broadcasts to the community.',
                    'image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 710 383 352',
                    'photos' => [
                        'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1567532939604-b6b5b0db2604?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4',
                    ],
                ],

                [
                    'id' => 'chapconnect',
                    'name' => 'Chap Connect',
                    'category' => 'management',
                    'category_label' => 'Management',
                    'description' => 'Premium entertainment talent management agency representing elite artists, actors, directors, and creators across East Africa.',
                    'image' => 'https://images.unsplash.com/photo-1557804506-669a67965ba0?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 710 383 352',
                    'photos' => [
                        'https://images.unsplash.com/photo-1557804506-669a67965ba0?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-hands-adjusting-sound-board-faders-in-studio-41591-large.mp4',
                    ],
                ],

                [
                    'id' => 'jaycombat',
                    'name' => 'Jay Combat',
                    'category' => 'musicartist',
                    'category_label' => 'Music Artist',
                    'description' => 'High-speed Singeli artist pushing the boundaries of traditional Tanzanian street music with rapid lyrics and infectious high-tempo beats.',
                    'image' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 710 383 352',
                    'photos' => [
                        'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1493225457124-a3eb161ffa5f?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4',
                    ],
                ],

                [
                    'id' => 'bambocomedian',
                    'name' => 'Bambo Comedian',
                    'category' => 'comedian',
                    'category_label' => 'Comedian',
                    'description' => 'Versatile stand-up comedian and comic creator known for hilarious sketches, observational humor, and captivating live audiences.',
                    'image' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 710 383 352',
                    'photos' => [
                        'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-girl-in-neon-sign-backlight-41838-large.mp4',
                    ],
                ],

                [
                    'id' => 'mafuru',
                    'name' => 'Mafuru Comedian',
                    'category' => 'comedian',
                    'category_label' => 'Comedian',
                    'description' => 'Energetic comedy artist focusing on physical comedy, street interviews, and engaging social media content that keeps thousands laughing daily.',
                    'image' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 710 383 352',
                    'photos' => [
                        'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-girl-in-neon-sign-backlight-41838-large.mp4',
                    ],
                ],

                [
                    'id' => 'melodiesclassicband',
                    'name' => 'The Melodies Classic Band',
                    'category' => 'musicband',
                    'category_label' => 'Music Band',
                    'description' => 'Premium live performance band playing classic fusion, Afro-jazz, and contemporary pop covers. Available for weddings, corporate galas, and VIP shows.',
                    'image' => 'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 710 383 352',
                    'photos' => [
                        'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-video-camera-screen-filming-a-concert-41587-large.mp4',
                    ],
                ],

                [
                    'id' => 'directorclevorx',
                    'name' => 'Director Clevor X',
                    'category' => 'videodirector',
                    'category_label' => 'Video Director',
                    'description' => 'Award-winning cinematographer and director specializing in music videos, commercials, and visual storytelling that transforms brands.',
                    'image' => 'https://images.unsplash.com/photo-1485846234645-a62644f84728?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 710 383 352',
                    'photos' => [
                        'https://images.unsplash.com/photo-1485846234645-a62644f84728?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1492691527719-9d1e07e534b4?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-video-camera-screen-filming-a-concert-41587-large.mp4',
                    ],
                ],

                [
                    'id' => 'minobongotv',
                    'name' => 'Mino Bongo TV',
                    'category' => 'onlinetv',
                    'category_label' => 'Online TV',
                    'description' => 'Leading online television channel covering music releases, artist gossip, cultural news, and trending social debates across Tanzania.',
                    'image' => 'https://images.unsplash.com/photo-1626379616459-b2ce1d9decbc?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 710 383 352',
                    'photos' => [
                        'https://images.unsplash.com/photo-1626379616459-b2ce1d9decbc?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1598257006458-087169a1f08d?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-video-camera-screen-filming-a-concert-41587-large.mp4',
                    ],
                ],

                [
                    'id' => 'rude',
                    'name' => 'Rude',
                    'category' => 'videodirector',
                    'category_label' => 'Video Director',
                    'description' => 'Multi-talented video director and screenplay writer known for deep visual narratives, musicality, and cinematic lighting setups.',
                    'image' => 'https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 710 383 352',
                    'photos' => [
                        'https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-holding-a-video-camera-recording-footage-41589-large.mp4',
                    ],
                ],

                [
                    'id' => 'lwitikomatumba',
                    'name' => 'Lwitiko Matumba',
                    'category' => 'musicartist',
                    'category_label' => 'Music Artist',
                    'description' => 'Creative fusion singer and vocal artist exploring traditional styles combined with modern afro-beats rhythms.',
                    'image' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 710 383 352',
                    'photos' => [
                        'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4',
                    ],
                ],

                [
                    'id' => 'aryana',
                    'name' => 'Aryana',
                    'category' => 'musicartist',
                    'category_label' => 'Music Artist',
                    'description' => 'Gospel musician and talented film actress blending soulful melodies with inspirational and powerful message-driven lyrics.',
                    'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 710 383 352',
                    'photos' => [
                        'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4',
                    ],
                ],

                [
                    'id' => 'tyserxl',
                    'name' => 'Tyser XL',
                    'category' => 'musicartist',
                    'category_label' => 'Music Artist',
                    'description' => 'Fierce HipHop lyricist known for hard-hitting delivery, socially conscious bars, and a commanding mic presence.',
                    'image' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 710 383 352',
                    'photos' => [
                        'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4',
                    ],
                ],

                [
                    'id' => 'popweezy',
                    'name' => 'Pop Weezy',
                    'category' => 'musicartist',
                    'category_label' => 'Music Artist',
                    'description' => 'Rising star of the East African drill scene, delivering heavy 808s and fast-paced rhymes about modern urban life.',
                    'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 710 383 352',
                    'photos' => [
                        'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4',
                    ],
                ],

                [
                    'id' => 'zellahmsekeni',
                    'name' => 'Zellah Msekeni',
                    'category' => 'musicartist',
                    'category_label' => 'Music Artist',
                    'description' => 'Expressive multi-talent bridging high-energy spiritual dancing, acting, and gospel recording to inspire youth across regions.',
                    'image' => 'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 710 383 352',
                    'photos' => [
                        'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4',
                    ],
                ],

                [
                    'id' => 'ukhtymayrah',
                    'name' => 'Ukhty Mayrah',
                    'category' => 'contentcreator',
                    'category_label' => 'Content Creator',
                    'description' => 'Creative digital designer and social media influencer making engaging lifestyle videos, reviews, and community stories.',
                    'image' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 710 383 352',
                    'photos' => [
                        'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-girl-in-neon-sign-backlight-41838-large.mp4',
                    ],
                ],

                [
                    'id' => 'directorngalawa',
                    'name' => 'Director Ngalawa',
                    'category' => 'videodirector',
                    'category_label' => 'Video Director',
                    'description' => 'Prominent visual creator specializing in high-definition video production, narrative-driven cinematography, and top-tier musical edits.',
                    'image' => 'https://images.unsplash.com/photo-1492691527719-9d1e07e534b4?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 710 383 352',
                    'photos' => [
                        'https://images.unsplash.com/photo-1492691527719-9d1e07e534b4?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1485846234645-a62644f84728?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-holding-a-video-camera-recording-footage-41589-large.mp4',
                    ],
                ],

                [
                    'id' => 'ghosthandbeatz',
                    'name' => 'Ghosthand Tz',
                    'category' => 'musicproducer',
                    'category_label' => 'Music Producer',
                    'description' => 'Elite beatmaker and audio engineer forging iconic Afro-pop, Bongo Flava, and Trap backdrops for superstars.',
                    'image' => 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 710 383 352',
                    'photos' => [
                        'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1598653222000-6b7b7a552625?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-hands-adjusting-sound-board-faders-in-studio-41591-large.mp4',
                    ],
                ],

                [
                    'id' => 'nazzerplanetfm',
                    'name' => 'Nazzer Controller Planet FM',
                    'category' => 'journalist',
                    'category_label' => 'Journalist',
                    'description' => 'Highly energetic entertainment pundit and host bringing celebrity exclusives and fresh music news to the radio airwaves.',
                    'image' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 710 383 352',
                    'photos' => [
                        'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4',
                    ],
                ],

                [
                    'id' => 'producerelly',
                    'name' => 'Producer Elly',
                    'category' => 'musicproducer',
                    'category_label' => 'Music Producer',
                    'description' => 'Renowned record producer and master mixing expert who sculpted numerous top-charting tracks and emotional ballads.',
                    'image' => 'https://images.unsplash.com/photo-1598653222000-6b7b7a552625?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 710 383 352',
                    'photos' => [
                        'https://images.unsplash.com/photo-1598653222000-6b7b7a552625?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-hands-adjusting-sound-board-faders-in-studio-41591-large.mp4',
                    ],
                ],

                [
                    'id' => 'cinehood',
                    'name' => 'Cinehood',
                    'category' => 'videodirector',
                    'category_label' => 'Video Director',
                    'description' => 'Creative film studio and video director group creating television advertisements and cinematic musical blockbusters.',
                    'image' => 'https://images.unsplash.com/photo-1518609878373-06d740f60d8b?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 710 383 352',
                    'photos' => [
                        'https://images.unsplash.com/photo-1518609878373-06d740f60d8b?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1485846234645-a62644f84728?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-video-camera-screen-filming-a-concert-41587-large.mp4',
                    ],
                ],

                [
                    'id' => 'directornasonga',
                    'name' => 'Director Nasonga',
                    'category' => 'videodirector',
                    'category_label' => 'Video Director',
                    'description' => 'Cinematic director known for dramatic shorts, full-length storytelling movies, and visually stunning musical releases.',
                    'image' => 'https://images.unsplash.com/photo-1598899134739-24c46f58b8c0?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 710 383 352',
                    'photos' => [
                        'https://images.unsplash.com/photo-1598899134739-24c46f58b8c0?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-holding-a-video-camera-recording-footage-41589-large.mp4',
                    ],
                ],

                [
                    'id' => 'winnerman',
                    'name' => 'Winner Man',
                    'category' => 'contentcreator',
                    'category_label' => 'Content Creator',
                    'description' => 'Inspiring digital video blogger publishing creative challenges, social experiments, and comedy clips across multiple online platforms.',
                    'image' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 710 383 352',
                    'photos' => [
                        'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-holding-a-video-camera-recording-footage-41589-large.mp4',
                    ],
                ],

                /*
                 * ============================================================
                 * ADDITIONAL TALENTS (EXPANDING DIVERSE CATEGORIES TO 75+)
                 * ============================================================
                 */

                // VIDEO VIXENS
                [
                    'id' => 'sarahkhamis',
                    'name' => 'Sarah Khamis',
                    'category' => 'videovixen',
                    'category_label' => 'Video Vixen',
                    'description' => 'Professional video vixen and commercial model featured in top East African music videos and luxury fashion editorials.',
                    'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 712 110 201',
                    'photos' => [
                        'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-girl-in-neon-sign-backlight-41838-large.mp4',
                    ],
                ],

                [
                    'id' => 'trishaboss',
                    'name' => 'Trisha Boss',
                    'category' => 'videovixen',
                    'category_label' => 'Video Vixen',
                    'description' => 'High-energy video vixen, brand ambassador, and runway model with an eye for dramatic choreographies and camera performance.',
                    'image' => 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 714 220 302',
                    'photos' => [
                        'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-girl-in-neon-sign-backlight-41838-large.mp4',
                    ],
                ],

                [
                    'id' => 'lilianamari',
                    'name' => 'Lilian Amari',
                    'category' => 'videovixen',
                    'category_label' => 'Video Vixen',
                    'description' => 'Creative performer and video stylist known for graceful screen presence in Afrobeats and Bongo Flava visual projects.',
                    'image' => 'https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 715 330 403',
                    'photos' => [
                        'https://images.unsplash.com/photo-1529626455594-4ff0802cfb7e?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-girl-in-neon-sign-backlight-41838-large.mp4',
                    ],
                ],

                [
                    'id' => 'queenvanessa',
                    'name' => 'Queen Vanessa',
                    'category' => 'videovixen',
                    'category_label' => 'Video Vixen',
                    'description' => 'International music video model and beauty influencer collaborating with renowned directors across Africa.',
                    'image' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 716 440 504',
                    'photos' => [
                        'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-girl-in-neon-sign-backlight-41838-large.mp4',
                    ],
                ],

                // DANCERS
                [
                    'id' => 'angelnyange',
                    'name' => 'Angel Nyange',
                    'category' => 'dancer',
                    'category_label' => 'Dancer',
                    'description' => 'Contemporary Afro-fusion and traditional dance choreographer who trains youth dance academies and stages stadium live shows.',
                    'image' => 'https://images.unsplash.com/photo-1547153760-18fc86324498?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 754 550 605',
                    'photos' => [
                        'https://images.unsplash.com/photo-1547153760-18fc86324498?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1508700115892-45ecd05ae2ad?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-dancer-performing-in-a-dark-room-41581-large.mp4',
                    ],
                ],

                [
                    'id' => 'dancergolden',
                    'name' => 'Golden Boy Tz',
                    'category' => 'dancer',
                    'category_label' => 'Dancer',
                    'description' => 'Street dance champion, breakdancer, and acrobatic performer setting viral dance trends across East Africa.',
                    'image' => 'https://images.unsplash.com/photo-1508700115892-45ecd05ae2ad?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 755 660 706',
                    'photos' => [
                        'https://images.unsplash.com/photo-1508700115892-45ecd05ae2ad?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1518611012118-696072aa579a?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-dancer-performing-in-a-dark-room-41581-large.mp4',
                    ],
                ],

                [
                    'id' => 'kiddanceking',
                    'name' => 'Kid Dance King',
                    'category' => 'dancer',
                    'category_label' => 'Dancer',
                    'description' => 'Energetic hip-hop and amapiano dance virtuoso performing on major concert stages and television festivals.',
                    'image' => 'https://images.unsplash.com/photo-1518611012118-696072aa579a?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 756 770 807',
                    'photos' => [
                        'https://images.unsplash.com/photo-1518611012118-696072aa579a?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1524594152303-9fd13543fe6e?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-dancer-performing-in-a-dark-room-41581-large.mp4',
                    ],
                ],

                [
                    'id' => 'breezyafrika',
                    'name' => 'Breezy Afrika Crew',
                    'category' => 'dancer',
                    'category_label' => 'Dancer',
                    'description' => 'Synchronized urban dance collective blending traditional African rhythms with futuristic robotic popping.',
                    'image' => 'https://images.unsplash.com/photo-1524594152303-9fd13543fe6e?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 757 880 908',
                    'photos' => [
                        'https://images.unsplash.com/photo-1524594152303-9fd13543fe6e?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1547153760-18fc86324498?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-dancer-performing-in-a-dark-room-41581-large.mp4',
                    ],
                ],

                // ACTORS
                [
                    'id' => 'kelvinkisoka',
                    'name' => 'Kelvin Kisoka',
                    'category' => 'actor',
                    'category_label' => 'Actor',
                    'description' => 'Acclaimed dramatic film actor and theater performer renowned for commanding roles in regional TV dramas and feature films.',
                    'image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 762 990 109',
                    'photos' => [
                        'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-video-camera-screen-filming-a-concert-41587-large.mp4',
                    ],
                ],

                [
                    'id' => 'mariumshabani',
                    'name' => 'Marium Shabani',
                    'category' => 'actor',
                    'category_label' => 'Actor',
                    'description' => 'Multi-award nominated actress excelling in emotional depth, period drama, and voice-over artistry for animation.',
                    'image' => 'https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 763 111 210',
                    'photos' => [
                        'https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-video-camera-screen-filming-a-concert-41587-large.mp4',
                    ],
                ],

                [
                    'id' => 'barakamchome',
                    'name' => 'Baraka Mchome',
                    'category' => 'actor',
                    'category_label' => 'Actor',
                    'description' => 'Charismatic screen actor and action film enthusiast bringing intense character portrayals to cinema screens.',
                    'image' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 764 222 311',
                    'photos' => [
                        'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-video-camera-screen-filming-a-concert-41587-large.mp4',
                    ],
                ],

                [
                    'id' => 'fatmakassim',
                    'name' => 'Fatma Kassim',
                    'category' => 'actor',
                    'category_label' => 'Actor',
                    'description' => 'Talented Swahili cinema actress celebrated for lead roles in primetime family series and romantic comedies.',
                    'image' => 'https://images.unsplash.com/photo-1567532939604-b6b5b0db2604?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 765 333 412',
                    'photos' => [
                        'https://images.unsplash.com/photo-1567532939604-b6b5b0db2604?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-holding-a-video-camera-recording-footage-41589-large.mp4',
                    ],
                ],

                [
                    'id' => 'dennismakonda',
                    'name' => 'Dennis Makonda',
                    'category' => 'actor',
                    'category_label' => 'Actor',
                    'description' => 'Dynamic stage and screen veteran with over a decade of portraying complex characters in international co-productions.',
                    'image' => 'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 766 444 513',
                    'photos' => [
                        'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-video-camera-screen-filming-a-concert-41587-large.mp4',
                    ],
                ],

                // MUSIC DJS
                [
                    'id' => 'djkidb',
                    'name' => 'DJ Kid B',
                    'category' => 'musicdj',
                    'category_label' => 'Music DJ',
                    'description' => 'Resident club DJ and remix master blending high-bpm Amapiano, Afrobeat, and classic Tanzanian Singeli beats.',
                    'image' => 'https://images.unsplash.com/photo-1571266028243-3716f02d2d2e?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 784 555 614',
                    'photos' => [
                        'https://images.unsplash.com/photo-1571266028243-3716f02d2d2e?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-video-camera-screen-filming-a-concert-41587-large.mp4',
                    ],
                ],

                [
                    'id' => 'djnellytz',
                    'name' => 'DJ Nelly Tanzania',
                    'category' => 'musicdj',
                    'category_label' => 'Music DJ',
                    'description' => 'Celebrity turntable artist known for electrifying corporate festivals, stadium events, and radio mix-shows.',
                    'image' => 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 785 666 715',
                    'photos' => [
                        'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1429962714451-bb934ecdc4ec?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-hands-adjusting-sound-board-faders-in-studio-41591-large.mp4',
                    ],
                ],

                [
                    'id' => 'djqueenmimi',
                    'name' => 'DJ Queen Mimi',
                    'category' => 'musicdj',
                    'category_label' => 'Music DJ',
                    'description' => 'Pioneering female disc jockey driving the nightlife groove with seamless transitions and infectious crowd hype.',
                    'image' => 'https://images.unsplash.com/photo-1429962714451-bb934ecdc4ec?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 786 777 816',
                    'photos' => [
                        'https://images.unsplash.com/photo-1429962714451-bb934ecdc4ec?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1571266028243-3716f02d2d2e?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-video-camera-screen-filming-a-concert-41587-large.mp4',
                    ],
                ],

                [
                    'id' => 'djblackspin',
                    'name' => 'DJ Black Spin',
                    'category' => 'musicdj',
                    'category_label' => 'Music DJ',
                    'description' => 'Vinyl enthusiast, festival headliner, and sound curator delivering unforgettable Afro-house and urban musical journeys.',
                    'image' => 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 787 888 917',
                    'photos' => [
                        'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-hands-adjusting-sound-board-faders-in-studio-41591-large.mp4',
                    ],
                ],

                // EVENT MCS
                [
                    'id' => 'mcmwamba',
                    'name' => 'MC Mwamba',
                    'category' => 'eventmc',
                    'category_label' => 'Event MC',
                    'description' => 'High-prestige corporate master of ceremonies, wedding host, and public speaker bringing polish and decorum to high-profile events.',
                    'image' => 'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 718 999 018',
                    'photos' => [
                        'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4',
                    ],
                ],

                [
                    'id' => 'mcanitak',
                    'name' => 'MC Anita K',
                    'category' => 'eventmc',
                    'category_label' => 'Event MC',
                    'description' => 'Elegant event moderator and bilingual corporate host renowned for keeping audiences engaged and on-schedule.',
                    'image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 719 111 219',
                    'photos' => [
                        'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4',
                    ],
                ],

                [
                    'id' => 'mcbondatanzania',
                    'name' => 'MC Bonda Tanzania',
                    'category' => 'eventmc',
                    'category_label' => 'Event MC',
                    'description' => 'Energetic gala and wedding MC famous for humor, crowd interaction, and seamless event flow across Tanzania.',
                    'image' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 710 222 320',
                    'photos' => [
                        'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4',
                    ],
                ],

                // MUSIC MCS / HYPEMEN
                [
                    'id' => 'hypemanflex',
                    'name' => 'Hypeman Flex',
                    'category' => 'musicmc',
                    'category_label' => 'Music MC',
                    'description' => 'The ultimate concert hypeman bringing unyielding energy, crowd chants, and explosive stadium stage coordination.',
                    'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 711 333 421',
                    'photos' => [
                        'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-video-camera-screen-filming-a-concert-41587-large.mp4',
                    ],
                ],

                [
                    'id' => 'mcblessed',
                    'name' => 'MC Blessed Vibes',
                    'category' => 'musicmc',
                    'category_label' => 'Music MC',
                    'description' => 'Festival voice and live concert conductor who ignites the crowd from the first beat to the finale.',
                    'image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 712 444 522',
                    'photos' => [
                        'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-video-camera-screen-filming-a-concert-41587-large.mp4',
                    ],
                ],

                [
                    'id' => 'mcjohndope',
                    'name' => 'MC John Dope',
                    'category' => 'musicmc',
                    'category_label' => 'Music MC',
                    'description' => 'Street festival and stadium music MC celebrated for rhythmic crowd chanting and contagious energy.',
                    'image' => 'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 713 555 623',
                    'photos' => [
                        'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-video-camera-screen-filming-a-concert-41587-large.mp4',
                    ],
                ],

                // GUITARISTS
                [
                    'id' => 'eliasguitar',
                    'name' => 'Elias Acoustic',
                    'category' => 'guitarist',
                    'category_label' => 'Guitarist',
                    'description' => 'Virtuoso fingerstyle and flamenco guitarist providing soulful live performances for luxury events and studio sessions.',
                    'image' => 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 714 666 724',
                    'photos' => [
                        'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4',
                    ],
                ],

                [
                    'id' => 'solomonsolo',
                    'name' => 'Solomon Solo Tz',
                    'category' => 'guitarist',
                    'category_label' => 'Guitarist',
                    'description' => 'Lead electric guitarist specializing in Congolese rumba, Afro-jazz, and energetic live rock solos.',
                    'image' => 'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 715 777 825',
                    'photos' => [
                        'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-hands-adjusting-sound-board-faders-in-studio-41591-large.mp4',
                    ],
                ],

                [
                    'id' => 'daudistrings',
                    'name' => 'Daudi Strings',
                    'category' => 'guitarist',
                    'category_label' => 'Guitarist',
                    'description' => 'Versatile acoustic and bass guitar maestro touring with premier African gospel and secular bands.',
                    'image' => 'https://images.unsplash.com/photo-1520523839898-50712825e3a7?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 716 888 926',
                    'photos' => [
                        'https://images.unsplash.com/photo-1520523839898-50712825e3a7?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4',
                    ],
                ],

                // PIANISTS
                [
                    'id' => 'victorkeys',
                    'name' => 'Victor Keys',
                    'category' => 'pianist',
                    'category_label' => 'Pianist',
                    'description' => 'Classical and modern jazz keyboardist arranging intricate chord melodies and orchestral film scores.',
                    'image' => 'https://images.unsplash.com/photo-1552422535-c45813c61732?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 717 999 027',
                    'photos' => [
                        'https://images.unsplash.com/photo-1552422535-c45813c61732?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-hands-adjusting-sound-board-faders-in-studio-41591-large.mp4',
                    ],
                ],

                [
                    'id' => 'gracepianist',
                    'name' => 'Grace Piano Melodies',
                    'category' => 'pianist',
                    'category_label' => 'Pianist',
                    'description' => 'Expressive pianist delivering heartwarming classical and gospel performances for concerts and recitals.',
                    'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 718 111 228',
                    'photos' => [
                        'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1552422535-c45813c61732?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-hands-adjusting-sound-board-faders-in-studio-41591-large.mp4',
                    ],
                ],

                [
                    'id' => 'samuelchord',
                    'name' => 'Samuel Chordmaster',
                    'category' => 'pianist',
                    'category_label' => 'Pianist',
                    'description' => 'Studio session synthesizer and piano player known for lush Neo-soul chords and Bongo Flava progressions.',
                    'image' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 719 222 329',
                    'photos' => [
                        'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1552422535-c45813c61732?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-hands-adjusting-sound-board-faders-in-studio-41591-large.mp4',
                    ],
                ],

                // MUSIC PRODUCERS
                [
                    'id' => 'beatmasterk',
                    'name' => 'Beatmaster K',
                    'category' => 'musicproducer',
                    'category_label' => 'Music Producer',
                    'description' => 'Hitmaker producer behind chart-topping East African club anthems, synth-heavy Amapiano, and Drill bangers.',
                    'image' => 'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 772 333 430',
                    'photos' => [
                        'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1598653222000-6b7b7a552625?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-hands-adjusting-sound-board-faders-in-studio-41591-large.mp4',
                    ],
                ],

                [
                    'id' => 'soundgodtz',
                    'name' => 'Sound God Tz',
                    'category' => 'musicproducer',
                    'category_label' => 'Music Producer',
                    'description' => 'Sound engineer and vocal producer with modern mastering equipment, turning raw vocal takes into pristine radio hits.',
                    'image' => 'https://images.unsplash.com/photo-1598653222000-6b7b7a552625?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 773 444 531',
                    'photos' => [
                        'https://images.unsplash.com/photo-1598653222000-6b7b7a552625?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-hands-adjusting-sound-board-faders-in-studio-41591-large.mp4',
                    ],
                ],

                [
                    'id' => 'flamebeats',
                    'name' => 'Flame Beats Studio',
                    'category' => 'musicproducer',
                    'category_label' => 'Music Producer',
                    'description' => 'Multi-genre music producer producing distinctive sample packs, cinematic textures, and Afro-RnB grooves.',
                    'image' => 'https://images.unsplash.com/photo-1516280440614-37939bbacd81?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 774 555 632',
                    'photos' => [
                        'https://images.unsplash.com/photo-1516280440614-37939bbacd81?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-hands-adjusting-sound-board-faders-in-studio-41591-large.mp4',
                    ],
                ],

                // MUSIC ARTISTS
                [
                    'id' => 'malkiasharon',
                    'name' => 'Malkia Sharon',
                    'category' => 'musicartist',
                    'category_label' => 'Music Artist',
                    'description' => 'Soulful Bongo Flava songstress with soaring vocals and heartfelt lyrics touching millions across the continent.',
                    'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 775 666 733',
                    'photos' => [
                        'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4',
                    ],
                ],

                [
                    'id' => 'kassimbakari',
                    'name' => 'Kassim Bakari',
                    'category' => 'musicartist',
                    'category_label' => 'Music Artist',
                    'description' => 'Melodic Afropop vocalist and lyricist known for romantic ballads and catchy stadium choruses.',
                    'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 776 777 834',
                    'photos' => [
                        'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4',
                    ],
                ],

                [
                    'id' => 'neemamollel',
                    'name' => 'Neema Mollel',
                    'category' => 'musicartist',
                    'category_label' => 'Music Artist',
                    'description' => 'Powerhouse gospel worship leader and contemporary recording artist inspiring listeners throughout East Africa.',
                    'image' => 'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 777 888 935',
                    'photos' => [
                        'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4',
                    ],
                ],

                [
                    'id' => 'rashidibrother',
                    'name' => 'Rashidi Brother',
                    'category' => 'musicartist',
                    'category_label' => 'Music Artist',
                    'description' => 'High-tempo Singeli performer delivering street-smart poetry, lightning-speed flows, and raw energetic vibes.',
                    'image' => 'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 778 999 036',
                    'photos' => [
                        'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4',
                    ],
                ],

                [
                    'id' => 'faridabongo',
                    'name' => 'Farida Bongo',
                    'category' => 'musicartist',
                    'category_label' => 'Music Artist',
                    'description' => 'Rising RnB & Afro-fusion star crafting intimate acoustic songs and vibrant summer anthems.',
                    'image' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 779 111 237',
                    'photos' => [
                        'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4',
                    ],
                ],

                // MUSIC BANDS
                [
                    'id' => 'kilimanjarosound',
                    'name' => 'Kilimanjaro Sound Band',
                    'category' => 'musicband',
                    'category_label' => 'Music Band',
                    'description' => 'High-caliber 8-piece live orchestra band performing Afro-fusion, soukous, and timeless Swahili classics at top luxury resorts.',
                    'image' => 'https://images.unsplash.com/photo-1465847899084-d164df4dedc6?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 782 222 338',
                    'photos' => [
                        'https://images.unsplash.com/photo-1465847899084-d164df4dedc6?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-video-camera-screen-filming-a-concert-41587-large.mp4',
                    ],
                ],

                [
                    'id' => 'zanzibarrhythms',
                    'name' => 'Zanzibar Rhythms Ensemble',
                    'category' => 'musicband',
                    'category_label' => 'Music Band',
                    'description' => 'Traditional Taarab and acoustic Indian Ocean ensemble featuring kanun, oud, violin, and traditional percussion.',
                    'image' => 'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 783 333 439',
                    'photos' => [
                        'https://images.unsplash.com/photo-1501386761578-eac5c94b800a?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-video-camera-screen-filming-a-concert-41587-large.mp4',
                    ],
                ],

                // COMEDIANS
                [
                    'id' => 'jokermwakinyo',
                    'name' => 'Joker Mwakinyo',
                    'category' => 'comedian',
                    'category_label' => 'Comedian',
                    'description' => 'Viral online comedian and satirical impressionist capturing everyday Tanzanian life with brilliant humor.',
                    'image' => 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 784 444 540',
                    'photos' => [
                        'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-girl-in-neon-sign-backlight-41838-large.mp4',
                    ],
                ],

                [
                    'id' => 'chekafundi',
                    'name' => 'Cheka Fundi',
                    'category' => 'comedian',
                    'category_label' => 'Comedian',
                    'description' => 'Street prankster and stand-up comic making waves with spontaneous public comedy skits and live tour dates.',
                    'image' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 785 555 641',
                    'photos' => [
                        'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-girl-in-neon-sign-backlight-41838-large.mp4',
                    ],
                ],

                [
                    'id' => 'mamaashura',
                    'name' => 'Mama Ashura Comedy',
                    'category' => 'comedian',
                    'category_label' => 'Comedian',
                    'description' => 'Family sitcom creator and sketch comedian portraying vibrant neighborhood mother characters.',
                    'image' => 'https://images.unsplash.com/photo-1567532939604-b6b5b0db2604?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 786 666 742',
                    'photos' => [
                        'https://images.unsplash.com/photo-1567532939604-b6b5b0db2604?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-girl-in-neon-sign-backlight-41838-large.mp4',
                    ],
                ],

                // CONTENT CREATORS
                [
                    'id' => 'hasnadiaries',
                    'name' => 'Hasna Diaries',
                    'category' => 'contentcreator',
                    'category_label' => 'Content Creator',
                    'description' => 'Lifestyle vlogger and culinary storyteller exploring East African food culture, hidden travel gems, and wellness.',
                    'image' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 787 777 843',
                    'photos' => [
                        'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-holding-a-video-camera-recording-footage-41589-large.mp4',
                    ],
                ],

                [
                    'id' => 'brayovlogstz',
                    'name' => 'Brayo Vlogs Tz',
                    'category' => 'contentcreator',
                    'category_label' => 'Content Creator',
                    'description' => 'Tech reviewer and creative video maker producing in-depth smartphone breakdowns, gadget unboxings, and photography tips.',
                    'image' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 788 888 944',
                    'photos' => [
                        'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1492562080023-ab3db95bfbce?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-holding-a-video-camera-recording-footage-41589-large.mp4',
                    ],
                ],

                [
                    'id' => 'sheilastyle',
                    'name' => 'Sheila Style & Beauty',
                    'category' => 'contentcreator',
                    'category_label' => 'Content Creator',
                    'description' => 'Fashion consultant, makeup artist, and digital creator inspiring modern African wardrobe choices and glam transformations.',
                    'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 789 999 045',
                    'photos' => [
                        'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-holding-a-video-camera-recording-footage-41589-large.mp4',
                    ],
                ],

                // JOURNALISTS
                [
                    'id' => 'jumannehabari',
                    'name' => 'Jumanne Habari',
                    'category' => 'journalist',
                    'category_label' => 'Journalist',
                    'description' => 'Investigative entertainment journalist and news anchor breaking exclusive stories on the creative economy and arts.',
                    'image' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 710 111 246',
                    'photos' => [
                        'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4',
                    ],
                ],

                [
                    'id' => 'irenemkama',
                    'name' => 'Irene Mkama',
                    'category' => 'journalist',
                    'category_label' => 'Journalist',
                    'description' => 'Radio presenter, podcast host, and cultural commentator known for thoughtful one-on-one celebrity profiles.',
                    'image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 711 222 347',
                    'photos' => [
                        'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-singer-singing-into-a-microphone-in-a-studio-41583-large.mp4',
                    ],
                ],

                // ONLINE TV
                [
                    'id' => 'bongotrendstv',
                    'name' => 'Bongo Trends TV',
                    'category' => 'onlinetv',
                    'category_label' => 'Online TV',
                    'description' => 'Fast-growing online entertainment channel covering red carpet galas, festival livestreams, and music video reviews.',
                    'image' => 'https://images.unsplash.com/photo-1626379616459-b2ce1d9decbc?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 712 333 448',
                    'photos' => [
                        'https://images.unsplash.com/photo-1626379616459-b2ce1d9decbc?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1598257006458-087169a1f08d?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-video-camera-screen-filming-a-concert-41587-large.mp4',
                    ],
                ],

                [
                    'id' => 'tamadunitv',
                    'name' => 'Tamaduni Online TV',
                    'category' => 'onlinetv',
                    'category_label' => 'Online TV',
                    'description' => 'Cultural documentary and visual arts channel celebrating traditional folklore, craft masters, and heritage festivals.',
                    'image' => 'https://images.unsplash.com/photo-1598257006458-087169a1f08d?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 713 444 549',
                    'photos' => [
                        'https://images.unsplash.com/photo-1598257006458-087169a1f08d?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1626379616459-b2ce1d9decbc?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-video-camera-screen-filming-a-concert-41587-large.mp4',
                    ],
                ],

                // MANAGERS
                [
                    'id' => 'frankmagembe',
                    'name' => 'Frank Magembe',
                    'category' => 'manager',
                    'category_label' => 'Manager',
                    'description' => 'Professional artist manager negotiating recording deals, international tour booking, and brand sponsorships.',
                    'image' => 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 714 555 650',
                    'photos' => [
                        'https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1557804506-669a67965ba0?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-hands-adjusting-sound-board-faders-in-studio-41591-large.mp4',
                    ],
                ],

                [
                    'id' => 'hildamremi',
                    'name' => 'Hilda Mremi Talent Mgmt',
                    'category' => 'manager',
                    'category_label' => 'Manager',
                    'description' => 'Executive talent agent representing screen actors, voice talent, and celebrity influencers for commercial campaigns.',
                    'image' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 715 666 751',
                    'photos' => [
                        'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-hands-adjusting-sound-board-faders-in-studio-41591-large.mp4',
                    ],
                ],

                // TALENTED / SPECIAL ARTISTRY
                [
                    'id' => 'omariacrobat',
                    'name' => 'Omari Acrobatic & Fire Art',
                    'category' => 'talented',
                    'category_label' => 'Talented',
                    'description' => 'Breathtaking fire eater, contortionist, and acrobatic performer headlining carnivals, circus shows, and luxury galas.',
                    'image' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 716 777 852',
                    'photos' => [
                        'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1518611012118-696072aa579a?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-dancer-performing-in-a-dark-room-41581-large.mp4',
                    ],
                ],

                [
                    'id' => 'zuhuraart',
                    'name' => 'Zuhura Speed Painter',
                    'category' => 'talented',
                    'category_label' => 'Talented',
                    'description' => 'Live performance speed painter and visual portrait illusionist creating stunning upside-down canvas art in minutes.',
                    'image' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=600&auto=format&fit=crop&q=80',
                    'phone' => '+255 717 888 953',
                    'photos' => [
                        'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=800&auto=format&fit=crop&q=80',
                        'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?w=800&auto=format&fit=crop&q=80',
                    ],
                    'videos' => [
                        'https://assets.mixkit.co/videos/preview/mixkit-holding-a-video-camera-recording-footage-41589-large.mp4',
                    ],
                ],
            ];

            /*
             * ============================================================
             * 4. SEED TALENTS SAFELY
             * ============================================================
             */

            foreach ($talents as $talent) {

                $email = $talent['id'] . '@chapconnect.com';

                $user = User::where('email', $email)->first();

                if (!$user) {
                    $user = User::create([
                        'name' => $talent['name'],
                        'email' => $email,
                        'password' => Hash::make('password123'),
                        'category' => $talent['category'],
                        'category_label' => $talent['category_label'],
                        'description' => $talent['description'],
                        'profile_image' => $talent['image'],
                        'phone' => $talent['phone'],
                        'country' => 'East Africa Tanzania',
                        'role' => 'user',
                        'is_published' => true,
                        'views_count' => rand(120, 3600),
                        'social_instagram' => 'https://instagram.com',
                        'social_facebook' => 'https://facebook.com',
                        'social_tiktok' => 'https://tiktok.com',
                        'social_youtube' => 'https://youtube.com',
                    ]);

                    $this->command?->info("Created talent: {$talent['name']}");
                } else {
                    // Ensure existing talent is published and has category label
                    if (!$user->is_published) {
                        $user->update(['is_published' => true]);
                    }
                    $this->command?->info("Talent already exists: {$talent['name']} - skipped user creation.");
                }

                // Guarantee active standard package & invoice for phone visibility and billing
                $user->ensureStandardPackageAndInvoice();

                /*
                 * ========================================================
                 * PHOTOS
                 * ========================================================
                 */
                foreach ($talent['photos'] as $photoUrl) {
                    Media::firstOrCreate([
                        'user_id' => $user->id,
                        'type' => 'photo',
                        'file_path' => $photoUrl,
                    ], [
                        'is_visible' => true,
                        'moderation_status' => 'approved',
                    ]);
                }

                /*
                 * ========================================================
                 * VIDEOS
                 * ========================================================
                 */
                foreach ($talent['videos'] as $videoUrl) {
                    Media::firstOrCreate([
                        'user_id' => $user->id,
                        'type' => 'video',
                        'file_path' => $videoUrl,
                    ], [
                        'is_visible' => true,
                        'moderation_status' => 'approved',
                    ]);
                }

                /*
                 * ========================================================
                 * INITIAL ENGAGEMENT (LIKES & FOLLOWERS)
                 * ========================================================
                 */
                if ($user->likesReceived()->count() === 0) {
                    $likesCount = rand(3, 18);
                    for ($i = 1; $i <= $likesCount; $i++) {
                        Like::firstOrCreate([
                            'talent_id' => $user->id,
                            'ip_address' => '192.168.1.' . ($i + 10),
                        ]);
                    }
                }

                if ($user->followersReceived()->count() === 0) {
                    $followersCount = rand(2, 12);
                    for ($j = 1; $j <= $followersCount; $j++) {
                        Follower::firstOrCreate([
                            'talent_id' => $user->id,
                            'ip_address' => '10.0.0.' . ($j + 20),
                        ]);
                    }
                }
            }

            // Invalidate caches so newly seeded talents immediately show up everywhere
            Cache::forget('home_total_talents');
            Cache::forget('home_category_counts');
            Cache::forget('about_total_talents');
        });

        $this->command?->info('Database seeding completed safely with all talents.');
    }
}
