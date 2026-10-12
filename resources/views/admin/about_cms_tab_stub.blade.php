<!-- ==========================================================================
     ABOUT US PAGE CMS TAB CONTENT (Used in Admin & Customer Care Dashboards)
     Bilingual English & Swahili Dynamic Editor
     ========================================================================== -->
<div id="tab-about" class="tab-content">
    <!-- Top Alert / Header -->
    <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: #ffffff; padding: 22px 26px; border-radius: 18px; margin-bottom: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.12); border: 1px solid rgba(255,255,255,0.08); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 52px; height: 52px; border-radius: 16px; background: rgba(56, 189, 248, 0.15); border: 1px solid rgba(56, 189, 248, 0.35); display: flex; align-items: center; justify-content: center; color: #38bdf8; font-size: 1.6rem; flex-shrink: 0;">
                <i class="bi bi-translate"></i>
            </div>
            <div>
                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                    <h2 style="margin: 0; font-size: 1.3rem; font-weight: 800; color: #ffffff;">About Us Page CMS</h2>
                    <span style="background: rgba(16, 185, 129, 0.2); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.35); font-size: 0.72rem; font-weight: 700; padding: 3px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 5px;">
                        <span style="width: 6px; height: 6px; border-radius: 50%; background: #34d399;"></span> Bilingual (English &amp; Kiswahili)
                    </span>
                </div>
                <p style="margin: 4px 0 0 0; color: #94a3b8; font-size: 0.86rem;">
                    Manage public text content in both English &amp; Swahili side-by-side, mission, vision, workflow steps, contact info, and official announcements.
                </p>
            </div>
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('about') }}" target="_blank" class="vbtn" style="background: rgba(255,255,255,0.12); color: #fff; border: 1px solid rgba(255,255,255,0.25); border-radius: 30px; font-weight: 700; font-size: 0.84rem; padding: 9px 18px; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
                <i class="bi bi-box-arrow-up-right"></i> View Public About Page
            </a>
        </div>
    </div>

    <!-- Inner Navigation Pills for CMS Sub-sections -->
    <div style="display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 22px; background: #ffffff; padding: 8px 12px; border-radius: 14px; border: 1px solid #e2e8f0;">
        <button type="button" class="about-subtab-btn active" onclick="switchAboutSubtab('hero', this)">
            <i class="bi bi-megaphone-fill"></i> Hero &amp; Header
        </button>
        <button type="button" class="about-subtab-btn" onclick="switchAboutSubtab('story', this)">
            <i class="bi bi-book-half"></i> Who We Are &amp; Story
        </button>
        <button type="button" class="about-subtab-btn" onclick="switchAboutSubtab('mission', this)">
            <i class="bi bi-rocket-takeoff-fill"></i> Mission &amp; Vision
        </button>
        <button type="button" class="about-subtab-btn" onclick="switchAboutSubtab('pillars', this)">
            <i class="bi bi-grid-fill"></i> Core Pillars
        </button>
        <button type="button" class="about-subtab-btn" onclick="switchAboutSubtab('workflow', this)">
            <i class="bi bi-diagram-3-fill"></i> How It Works Steps
        </button>
        <button type="button" class="about-subtab-btn" onclick="switchAboutSubtab('office', this)">
            <i class="bi bi-geo-alt-fill"></i> Office, Map &amp; Support
        </button>
        <button type="button" class="about-subtab-btn" onclick="switchAboutSubtab('announcements', this)">
            <i class="bi bi-newspaper"></i> Official Posts ({{ isset($aboutPosts) ? count($aboutPosts) : 0 }})
        </button>
    </div>

    <style>
        .about-subtab-btn {
            background: transparent;
            border: none;
            color: #475569;
            font-size: 0.83rem;
            font-weight: 700;
            padding: 8px 14px;
            border-radius: 10px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: all 0.2s ease;
        }
        .about-subtab-btn:hover {
            background: #f1f5f9;
            color: #0f172a;
        }
        .about-subtab-btn.active {
            background: #0066fe;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(0, 102, 254, 0.25);
        }
        .cms-field-group {
            margin-bottom: 20px;
        }
        .cms-field-group > label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 7px;
        }
        .dual-lang-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }
        @media (max-width: 768px) {
            .dual-lang-row {
                grid-template-columns: 1fr;
                gap: 10px;
            }
        }
        .dual-lang-col {
            position: relative;
        }
        .lang-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 0.72rem;
            font-weight: 800;
            padding: 3px 9px;
            border-radius: 6px;
            margin-bottom: 6px;
        }
        .lang-badge.en {
            background: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }
        .lang-badge.sw {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }
        .cms-field-group .form-control {
            width: 100%;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            padding: 10px 14px;
            font-size: 0.88rem;
            color: #0f172a;
            background: #ffffff;
            box-sizing: border-box;
            transition: border-color 0.2s;
        }
        .cms-field-group .form-control:focus {
            border-color: #0066fe;
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 102, 254, 0.12);
        }
        .cms-card {
            background: #ffffff;
            border-radius: 18px;
            border: 1px solid #e2e8f0;
            padding: 24px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
            margin-bottom: 24px;
        }
        .cms-card-header {
            margin-bottom: 18px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        .cms-card-header h3 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 800;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>

    <!-- FORM: ALL PAGE CONTENT SECTIONS (ENGLISH & SWAHILI) -->
    <form action="{{ route('staff.about.update-settings') }}" method="POST" id="aboutSettingsForm">
        @csrf

        <!-- SUBTAB 1: HERO SECTION -->
        <div id="subtab-hero" class="about-subtab-pane">
            <div class="cms-card">
                <div class="cms-card-header">
                    <h3><i class="bi bi-megaphone-fill" style="color: #0066fe;"></i> Hero Banner Content (Bilingual)</h3>
                    <button type="submit" class="vbtn" style="background: linear-gradient(135deg, #0066fe 0%, #2563eb 100%); color: #fff; border: none; border-radius: 10px; font-weight: 700; padding: 8px 18px; font-size: 0.85rem; cursor: pointer;">
                        <i class="bi bi-save"></i> Save Changes
                    </button>
                </div>

                <div class="cms-field-group">
                    <label>Hero Badge Pill Text</label>
                    <div class="dual-lang-row">
                        <div class="dual-lang-col">
                            <span class="lang-badge en"><i class="bi bi-translate"></i> 🇬🇧 English</span>
                            <input type="text" name="about_hero_badge_en" class="form-control" value="{{ $aboutSettings['about_hero_badge_en'] ?? $aboutSettings['about_hero_badge'] ?? 'Empowering Creators, Performers & Visionaries Across East Africa' }}">
                        </div>
                        <div class="dual-lang-col">
                            <span class="lang-badge sw"><i class="bi bi-translate"></i> 🇹🇿 Kiswahili</span>
                            <input type="text" name="about_hero_badge_sw" class="form-control" value="{{ $aboutSettings['about_hero_badge_sw'] ?? 'Kuwezesha Wasanii, Watumbuizaji na Wabunifu Kote Afrika Mashariki' }}">
                        </div>
                    </div>
                </div>

                <div class="cms-field-group">
                    <label>Hero Main Headline (Title)</label>
                    <div class="dual-lang-row">
                        <div class="dual-lang-col">
                            <span class="lang-badge en"><i class="bi bi-translate"></i> 🇬🇧 English</span>
                            <input type="text" name="about_hero_title_en" class="form-control" value="{{ $aboutSettings['about_hero_title_en'] ?? $aboutSettings['about_hero_title'] ?? 'Connecting Talents with Limitless Opportunities' }}">
                        </div>
                        <div class="dual-lang-col">
                            <span class="lang-badge sw"><i class="bi bi-translate"></i> 🇹🇿 Kiswahili</span>
                            <input type="text" name="about_hero_title_sw" class="form-control" value="{{ $aboutSettings['about_hero_title_sw'] ?? 'Kuunganisha Vipaji na Fursa Zisizo na Kikomo' }}">
                        </div>
                    </div>
                    <small style="color: #64748b; font-size: 0.76rem; display: block; margin-top: 5px;">The headline displayed at the top of the About page for both languages.</small>
                </div>

                <div class="cms-field-group">
                    <label>Hero Detailed Subtitle / Platform Summary</label>
                    <div class="dual-lang-row">
                        <div class="dual-lang-col">
                            <span class="lang-badge en"><i class="bi bi-translate"></i> 🇬🇧 English</span>
                            <textarea name="about_hero_subtitle_en" rows="3" class="form-control">{{ $aboutSettings['about_hero_subtitle_en'] ?? $aboutSettings['about_hero_subtitle'] ?? 'ChapConnect is the next-generation creative talent directory and verified networking platform. We help musicians, dancers, comedians, models, MCs, visual artists, and performers showcase their artistry and connect directly with clients, promoters, event planners, and audiences worldwide.' }}</textarea>
                        </div>
                        <div class="dual-lang-col">
                            <span class="lang-badge sw"><i class="bi bi-translate"></i> 🇹🇿 Kiswahili</span>
                            <textarea name="about_hero_subtitle_sw" rows="3" class="form-control">{{ $aboutSettings['about_hero_subtitle_sw'] ?? 'ChapConnect ni jukwaa la kizazi kipya la orodha ya vipaji vya ubunifu na mtandao uliothibitishwa. Tunasaidia wanamuziki, wanadansi, wachekeshaji, wanamitindo, ma-MC, wasanii wa picha na watumbuizaji kuonyesha kazi zao na kuungana moja kwa moja na wateja, waandaaji wa matukio na hadhira ulimwenguni kote.' }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="dual-lang-row">
                    <div class="cms-field-group">
                        <label>Explore Button Text</label>
                        <div class="dual-lang-row">
                            <div class="dual-lang-col">
                                <span class="lang-badge en">🇬🇧 EN</span>
                                <input type="text" name="about_hero_btn_explore_en" class="form-control" value="{{ $aboutSettings['about_hero_btn_explore_en'] ?? $aboutSettings['about_hero_btn_explore'] ?? 'Explore Talents' }}">
                            </div>
                            <div class="dual-lang-col">
                                <span class="lang-badge sw">🇹🇿 SW</span>
                                <input type="text" name="about_hero_btn_explore_sw" class="form-control" value="{{ $aboutSettings['about_hero_btn_explore_sw'] ?? 'Gundua Vipaji' }}">
                            </div>
                        </div>
                    </div>
                    <div class="cms-field-group">
                        <label>Join / Register Button Text</label>
                        <div class="dual-lang-row">
                            <div class="dual-lang-col">
                                <span class="lang-badge en">🇬🇧 EN</span>
                                <input type="text" name="about_hero_btn_join_en" class="form-control" value="{{ $aboutSettings['about_hero_btn_join_en'] ?? $aboutSettings['about_hero_btn_join'] ?? 'Join as a Talent' }}">
                            </div>
                            <div class="dual-lang-col">
                                <span class="lang-badge sw">🇹🇿 SW</span>
                                <input type="text" name="about_hero_btn_join_sw" class="form-control" value="{{ $aboutSettings['about_hero_btn_join_sw'] ?? 'Jiunge kama Kipaji' }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SUBTAB 2: WHO WE ARE & STORY -->
        <div id="subtab-story" class="about-subtab-pane" style="display: none;">
            <div class="cms-card">
                <div class="cms-card-header">
                    <h3><i class="bi bi-book-half" style="color: #0066fe;"></i> Narrative Story &amp; Who We Are (Bilingual)</h3>
                    <button type="submit" class="vbtn" style="background: linear-gradient(135deg, #0066fe 0%, #2563eb 100%); color: #fff; border: none; border-radius: 10px; font-weight: 700; padding: 8px 18px; font-size: 0.85rem; cursor: pointer;">
                        <i class="bi bi-save"></i> Save Changes
                    </button>
                </div>

                <div class="cms-field-group">
                    <label>Story Badge / Category Pill</label>
                    <div class="dual-lang-row">
                        <div class="dual-lang-col">
                            <span class="lang-badge en">🇬🇧 English</span>
                            <input type="text" name="about_story_badge_en" class="form-control" value="{{ $aboutSettings['about_story_badge_en'] ?? $aboutSettings['about_story_badge'] ?? 'Who We Are' }}">
                        </div>
                        <div class="dual-lang-col">
                            <span class="lang-badge sw">🇹🇿 Kiswahili</span>
                            <input type="text" name="about_story_badge_sw" class="form-control" value="{{ $aboutSettings['about_story_badge_sw'] ?? 'Sisi ni Nani' }}">
                        </div>
                    </div>
                </div>

                <div class="cms-field-group">
                    <label>Story Main Title</label>
                    <div class="dual-lang-row">
                        <div class="dual-lang-col">
                            <span class="lang-badge en">🇬🇧 English</span>
                            <input type="text" name="about_story_title_en" class="form-control" value="{{ $aboutSettings['about_story_title_en'] ?? $aboutSettings['about_story_title'] ?? 'Building the Future of Talent Discovery & Direct Booking' }}">
                        </div>
                        <div class="dual-lang-col">
                            <span class="lang-badge sw">🇹🇿 Kiswahili</span>
                            <input type="text" name="about_story_title_sw" class="form-control" value="{{ $aboutSettings['about_story_title_sw'] ?? 'Kujenga Mustakabali wa Ugunduzi wa Vipaji na Uhifadhi wa Moja kwa Moja' }}">
                        </div>
                    </div>
                </div>

                <div class="cms-field-group">
                    <label>First Paragraph (The Challenge)</label>
                    <div class="dual-lang-row">
                        <div class="dual-lang-col">
                            <span class="lang-badge en">🇬🇧 English</span>
                            <textarea name="about_story_p1_en" rows="3" class="form-control">{{ $aboutSettings['about_story_p1_en'] ?? $aboutSettings['about_story_p1'] ?? 'In East Africa and beyond, millions of remarkably talented people go unnoticed simply because they lack professional digital presence and direct access to clients, brands, event managers, and entertainment agencies.' }}</textarea>
                        </div>
                        <div class="dual-lang-col">
                            <span class="lang-badge sw">🇹🇿 Kiswahili</span>
                            <textarea name="about_story_p1_sw" rows="3" class="form-control">{{ $aboutSettings['about_story_p1_sw'] ?? 'Katika Afrika Mashariki na kwingineko, mamilioni ya watu wenye vipaji vya ajabu hawatambuliwi kwa sababu tu wanakosa uwepo wa kitaalamu wa kidijitali na ufikiaji wa moja kwa moja kwa wateja, chapa, mameneja wa matukio na mashirika ya burudani.' }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="cms-field-group">
                    <label>Second Paragraph (The Solution)</label>
                    <div class="dual-lang-row">
                        <div class="dual-lang-col">
                            <span class="lang-badge en">🇬🇧 English</span>
                            <textarea name="about_story_p2_en" rows="3" class="form-control">{{ $aboutSettings['about_story_p2_en'] ?? $aboutSettings['about_story_p2'] ?? 'ChapConnect was created to solve this challenge. We provide every creative individual—from budding artists to seasoned professionals—with an all-in-one digital portfolio where they can present verified bios, high-resolution photo galleries, performance videos, and direct contact details.' }}</textarea>
                        </div>
                        <div class="dual-lang-col">
                            <span class="lang-badge sw">🇹🇿 Kiswahili</span>
                            <textarea name="about_story_p2_sw" rows="3" class="form-control">{{ $aboutSettings['about_story_p2_sw'] ?? 'ChapConnect iliundwa kutatua changamoto hii. Tunampatia kila mtu mbunifu—kuanzia wasanii wanaochipukia hadi wataalamu waliobobea—wasifu kamili wa kidijitali ambapo wanaweza kuonyesha wasifu uliothibitishwa, picha za ubora wa juu, video za maonyesho na nambari za moja kwa moja za mawasiliano.' }}</textarea>
                        </div>
                    </div>
                </div>

                <h4 style="font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 20px 0 12px;">Story Bullet Highlights (Bilingual)</h4>
                @for($i = 1; $i <= 4; $i++)
                <div class="cms-field-group">
                    <label>Highlight {{ $i }}</label>
                    <div class="dual-lang-row">
                        <div class="dual-lang-col">
                            <span class="lang-badge en">🇬🇧 EN</span>
                            <input type="text" name="about_highlight_{{ $i }}_en" class="form-control" value="{{ $aboutSettings['about_highlight_' . $i . '_en'] ?? $aboutSettings['about_highlight_' . $i] ?? '' }}">
                        </div>
                        <div class="dual-lang-col">
                            <span class="lang-badge sw">🇹🇿 SW</span>
                            <input type="text" name="about_highlight_{{ $i }}_sw" class="form-control" value="{{ $aboutSettings['about_highlight_' . $i . '_sw'] ?? '' }}">
                        </div>
                    </div>
                </div>
                @endfor

                <h4 style="font-size: 0.95rem; font-weight: 700; color: #1e293b; margin: 20px 0 12px;">Brand Card Subtitle &amp; Badges (Bilingual)</h4>
                <div class="cms-field-group">
                    <label>Brand Card Subtitle</label>
                    <div class="dual-lang-row">
                        <div class="dual-lang-col">
                            <span class="lang-badge en">🇬🇧 EN</span>
                            <input type="text" name="about_visual_brand_subtitle_en" class="form-control" value="{{ $aboutSettings['about_visual_brand_subtitle_en'] ?? $aboutSettings['about_visual_brand_subtitle'] ?? 'Official Creative Network • Tanzania' }}">
                        </div>
                        <div class="dual-lang-col">
                            <span class="lang-badge sw">🇹🇿 SW</span>
                            <input type="text" name="about_visual_brand_subtitle_sw" class="form-control" value="{{ $aboutSettings['about_visual_brand_subtitle_sw'] ?? 'Mtandao Rasmi wa Wabunifu • Tanzania' }}">
                        </div>
                    </div>
                </div>
                @for($b = 1; $b <= 4; $b++)
                <div class="cms-field-group">
                    <label>Visual Badge {{ $b }}</label>
                    <div class="dual-lang-row">
                        <div class="dual-lang-col">
                            <span class="lang-badge en">🇬🇧 EN</span>
                            <input type="text" name="about_visual_badge_{{ $b }}_en" class="form-control" value="{{ $aboutSettings['about_visual_badge_' . $b . '_en'] ?? $aboutSettings['about_visual_badge_' . $b] ?? '' }}">
                        </div>
                        <div class="dual-lang-col">
                            <span class="lang-badge sw">🇹🇿 SW</span>
                            <input type="text" name="about_visual_badge_{{ $b }}_sw" class="form-control" value="{{ $aboutSettings['about_visual_badge_' . $b . '_sw'] ?? '' }}">
                        </div>
                    </div>
                </div>
                @endfor
            </div>
        </div>

        <!-- SUBTAB 3: MISSION & VISION -->
        <div id="subtab-mission" class="about-subtab-pane" style="display: none;">
            <div class="cms-card">
                <div class="cms-card-header">
                    <h3><i class="bi bi-rocket-takeoff-fill" style="color: #0066fe;"></i> Mission &amp; Vision Statements (Bilingual)</h3>
                    <button type="submit" class="vbtn" style="background: linear-gradient(135deg, #0066fe 0%, #2563eb 100%); color: #fff; border: none; border-radius: 10px; font-weight: 700; padding: 8px 18px; font-size: 0.85rem; cursor: pointer;">
                        <i class="bi bi-save"></i> Save Changes
                    </button>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px; margin-bottom: 20px;">
                    <h4 style="font-size: 1rem; font-weight: 800; color: #0066fe; margin: 0 0 14px; display: flex; align-items: center; gap: 6px;">
                        <i class="bi bi-rocket-takeoff-fill"></i> Mission Statement
                    </h4>
                    <div class="cms-field-group">
                        <label>Mission Title</label>
                        <div class="dual-lang-row">
                            <div class="dual-lang-col">
                                <span class="lang-badge en">🇬🇧 EN</span>
                                <input type="text" name="about_mission_title_en" class="form-control" value="{{ $aboutSettings['about_mission_title_en'] ?? $aboutSettings['about_mission_title'] ?? 'Our Mission' }}">
                            </div>
                            <div class="dual-lang-col">
                                <span class="lang-badge sw">🇹🇿 SW</span>
                                <input type="text" name="about_mission_title_sw" class="form-control" value="{{ $aboutSettings['about_mission_title_sw'] ?? 'Dhamira Yetu' }}">
                            </div>
                        </div>
                    </div>
                    <div class="cms-field-group">
                        <label>Mission Content</label>
                        <div class="dual-lang-row">
                            <div class="dual-lang-col">
                                <span class="lang-badge en">🇬🇧 EN</span>
                                <textarea name="about_mission_text_en" rows="4" class="form-control">{{ $aboutSettings['about_mission_text_en'] ?? $aboutSettings['about_mission_text'] ?? 'To empower every creative individual across East Africa with a world-class digital platform to showcase their gifts, gain authentic visibility, and monetize their skills through friction-free, direct connections with clients and audiences worldwide.' }}</textarea>
                            </div>
                            <div class="dual-lang-col">
                                <span class="lang-badge sw">🇹🇿 SW</span>
                                <textarea name="about_mission_text_sw" rows="4" class="form-control">{{ $aboutSettings['about_mission_text_sw'] ?? 'Kuwezesha kila mtu mbunifu kote Afrika Mashariki na jukwaa la kiwango cha kimataifa la kuonyesha vipawa vyao, kupata uonekanaji halisi na kujiingizia kipato kupitia miunganisho ya moja kwa moja isiyo na usumbufu na wateja na hadhira ulimwenguni kote.' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px;">
                    <h4 style="font-size: 1rem; font-weight: 800; color: #0284c7; margin: 0 0 14px; display: flex; align-items: center; gap: 6px;">
                        <i class="bi bi-eye-fill"></i> Vision Statement
                    </h4>
                    <div class="cms-field-group">
                        <label>Vision Title</label>
                        <div class="dual-lang-row">
                            <div class="dual-lang-col">
                                <span class="lang-badge en">🇬🇧 EN</span>
                                <input type="text" name="about_vision_title_en" class="form-control" value="{{ $aboutSettings['about_vision_title_en'] ?? $aboutSettings['about_vision_title'] ?? 'Our Vision' }}">
                            </div>
                            <div class="dual-lang-col">
                                <span class="lang-badge sw">🇹🇿 SW</span>
                                <input type="text" name="about_vision_title_sw" class="form-control" value="{{ $aboutSettings['about_vision_title_sw'] ?? 'Maono Yetu' }}">
                            </div>
                        </div>
                    </div>
                    <div class="cms-field-group">
                        <label>Vision Content</label>
                        <div class="dual-lang-row">
                            <div class="dual-lang-col">
                                <span class="lang-badge en">🇬🇧 EN</span>
                                <textarea name="about_vision_text_en" rows="4" class="form-control">{{ $aboutSettings['about_vision_text_en'] ?? $aboutSettings['about_vision_text'] ?? 'To become the most comprehensive, respected, and vibrant digital ecosystem for creative discovery, cross-border talent booking, and cultural empowerment throughout Africa.' }}</textarea>
                            </div>
                            <div class="dual-lang-col">
                                <span class="lang-badge sw">🇹🇿 SW</span>
                                <textarea name="about_vision_text_sw" rows="4" class="form-control">{{ $aboutSettings['about_vision_text_sw'] ?? 'Kuwa mfumo thabiti, unaoheshimika na mahiri zaidi wa kidijitali kwa ugunduzi wa ubunifu, uhifadhi wa vipaji mipakani na uwezeshaji wa kitamaduni barani Afrika kote.' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SUBTAB 4: CORE PILLARS (WHY CHOOSE US) -->
        <div id="subtab-pillars" class="about-subtab-pane" style="display: none;">
            <div class="cms-card">
                <div class="cms-card-header">
                    <h3><i class="bi bi-grid-fill" style="color: #0066fe;"></i> Core Value Pillars (Bilingual)</h3>
                    <button type="submit" class="vbtn" style="background: linear-gradient(135deg, #0066fe 0%, #2563eb 100%); color: #fff; border: none; border-radius: 10px; font-weight: 700; padding: 8px 18px; font-size: 0.85rem; cursor: pointer;">
                        <i class="bi bi-save"></i> Save Changes
                    </button>
                </div>

                <div class="cms-field-group">
                    <label>Section Pill</label>
                    <div class="dual-lang-row">
                        <div class="dual-lang-col">
                            <span class="lang-badge en">🇬🇧 EN</span>
                            <input type="text" name="about_pillars_badge_en" class="form-control" value="{{ $aboutSettings['about_pillars_badge_en'] ?? $aboutSettings['about_pillars_badge'] ?? 'Why Choose ChapConnect' }}">
                        </div>
                        <div class="dual-lang-col">
                            <span class="lang-badge sw">🇹🇿 SW</span>
                            <input type="text" name="about_pillars_badge_sw" class="form-control" value="{{ $aboutSettings['about_pillars_badge_sw'] ?? 'Kwanini Uchague ChapConnect' }}">
                        </div>
                    </div>
                </div>

                <div class="cms-field-group">
                    <label>Section Title</label>
                    <div class="dual-lang-row">
                        <div class="dual-lang-col">
                            <span class="lang-badge en">🇬🇧 EN</span>
                            <input type="text" name="about_pillars_title_en" class="form-control" value="{{ $aboutSettings['about_pillars_title_en'] ?? $aboutSettings['about_pillars_title'] ?? 'Designed for Creators & Clients Alike' }}">
                        </div>
                        <div class="dual-lang-col">
                            <span class="lang-badge sw">🇹🇿 SW</span>
                            <input type="text" name="about_pillars_title_sw" class="form-control" value="{{ $aboutSettings['about_pillars_title_sw'] ?? 'Imebuniwa kwa Ajili ya Wasanii na Wateja kwa Pamoja' }}">
                        </div>
                    </div>
                </div>

                <div class="cms-field-group">
                    <label>Section Subtitle</label>
                    <div class="dual-lang-row">
                        <div class="dual-lang-col">
                            <span class="lang-badge en">🇬🇧 EN</span>
                            <input type="text" name="about_pillars_subtitle_en" class="form-control" value="{{ $aboutSettings['about_pillars_subtitle_en'] ?? $aboutSettings['about_pillars_subtitle'] ?? 'Everything you need to discover exceptional talent or build a thriving career in creative arts and entertainment.' }}">
                        </div>
                        <div class="dual-lang-col">
                            <span class="lang-badge sw">🇹🇿 SW</span>
                            <input type="text" name="about_pillars_subtitle_sw" class="form-control" value="{{ $aboutSettings['about_pillars_subtitle_sw'] ?? 'Kila kitu unachohitaji ili kugundua vipaji maalum au kujenga taaluma yenye mafanikio katika sanaa ya ubunifu na burudani.' }}">
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px;">
                    @for($p = 1; $p <= 4; $p++)
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px;">
                        <h5 style="margin: 0 0 10px; font-size: 0.88rem; font-weight: 800; color: #0f172a;">Pillar {{ $p }} (Bilingual)</h5>
                        <div class="cms-field-group">
                            <label>Title</label>
                            <div class="dual-lang-row">
                                <div class="dual-lang-col">
                                    <span class="lang-badge en">🇬🇧 EN</span>
                                    <input type="text" name="about_pillar_{{ $p }}_title_en" class="form-control" value="{{ $aboutSettings['about_pillar_' . $p . '_title_en'] ?? $aboutSettings['about_pillar_' . $p . '_title'] ?? '' }}">
                                </div>
                                <div class="dual-lang-col">
                                    <span class="lang-badge sw">🇹🇿 SW</span>
                                    <input type="text" name="about_pillar_{{ $p }}_title_sw" class="form-control" value="{{ $aboutSettings['about_pillar_' . $p . '_title_sw'] ?? '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="cms-field-group">
                            <label>Description</label>
                            <div class="dual-lang-row">
                                <div class="dual-lang-col">
                                    <span class="lang-badge en">🇬🇧 EN</span>
                                    <textarea name="about_pillar_{{ $p }}_desc_en" rows="3" class="form-control">{{ $aboutSettings['about_pillar_' . $p . '_desc_en'] ?? $aboutSettings['about_pillar_' . $p . '_desc'] ?? '' }}</textarea>
                                </div>
                                <div class="dual-lang-col">
                                    <span class="lang-badge sw">🇹🇿 SW</span>
                                    <textarea name="about_pillar_{{ $p }}_desc_sw" rows="3" class="form-control">{{ $aboutSettings['about_pillar_' . $p . '_desc_sw'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endfor
                </div>
            </div>
        </div>

        <!-- SUBTAB 5: WORKFLOW STEPS -->
        <div id="subtab-workflow" class="about-subtab-pane" style="display: none;">
            <div class="cms-card">
                <div class="cms-card-header">
                    <h3><i class="bi bi-diagram-3-fill" style="color: #0066fe;"></i> How It Works Workflow Steps (Bilingual)</h3>
                    <button type="submit" class="vbtn" style="background: linear-gradient(135deg, #0066fe 0%, #2563eb 100%); color: #fff; border: none; border-radius: 10px; font-weight: 700; padding: 8px 18px; font-size: 0.85rem; cursor: pointer;">
                        <i class="bi bi-save"></i> Save Changes
                    </button>
                </div>

                <div class="cms-field-group">
                    <label>Section Pill</label>
                    <div class="dual-lang-row">
                        <div class="dual-lang-col">
                            <span class="lang-badge en">🇬🇧 EN</span>
                            <input type="text" name="about_workflow_badge_en" class="form-control" value="{{ $aboutSettings['about_workflow_badge_en'] ?? $aboutSettings['about_workflow_badge'] ?? 'How ChapConnect Works' }}">
                        </div>
                        <div class="dual-lang-col">
                            <span class="lang-badge sw">🇹🇿 SW</span>
                            <input type="text" name="about_workflow_badge_sw" class="form-control" value="{{ $aboutSettings['about_workflow_badge_sw'] ?? 'Jinsi ChapConnect Inavyofanya Kazi' }}">
                        </div>
                    </div>
                </div>

                <div class="cms-field-group">
                    <label>Section Title</label>
                    <div class="dual-lang-row">
                        <div class="dual-lang-col">
                            <span class="lang-badge en">🇬🇧 EN</span>
                            <input type="text" name="about_workflow_title_en" class="form-control" value="{{ $aboutSettings['about_workflow_title_en'] ?? $aboutSettings['about_workflow_title'] ?? 'Simple 3-Step Journey to Success' }}">
                        </div>
                        <div class="dual-lang-col">
                            <span class="lang-badge sw">🇹🇿 SW</span>
                            <input type="text" name="about_workflow_title_sw" class="form-control" value="{{ $aboutSettings['about_workflow_title_sw'] ?? 'Hatua 3 Rahisi Kuelekea Mafanikio' }}">
                        </div>
                    </div>
                </div>

                <div class="cms-field-group">
                    <label>Section Subtitle</label>
                    <div class="dual-lang-row">
                        <div class="dual-lang-col">
                            <span class="lang-badge en">🇬🇧 EN</span>
                            <input type="text" name="about_workflow_subtitle_en" class="form-control" value="{{ $aboutSettings['about_workflow_subtitle_en'] ?? $aboutSettings['about_workflow_subtitle'] ?? 'Whether you are offering a talent or looking to hire one, ChapConnect makes the process seamless and transparent.' }}">
                        </div>
                        <div class="dual-lang-col">
                            <span class="lang-badge sw">🇹🇿 SW</span>
                            <input type="text" name="about_workflow_subtitle_sw" class="form-control" value="{{ $aboutSettings['about_workflow_subtitle_sw'] ?? 'Iwe unatoa kipaji au unatafuta kuajiri mmoja, ChapConnect inafanya mchakato kuwa mwepesi na wazi.' }}">
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <!-- For Talents -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px;">
                        <h4 style="font-size: 0.95rem; font-weight: 800; color: #0066fe; margin: 0 0 14px;">
                            <i class="bi bi-palette-fill"></i> Steps for Talents &amp; Creators
                        </h4>
                        @for($s = 1; $s <= 3; $s++)
                        <div class="cms-field-group">
                            <label>Talent Step {{ $s }} Title</label>
                            <div class="dual-lang-row">
                                <div class="dual-lang-col">
                                    <span class="lang-badge en">🇬🇧 EN</span>
                                    <input type="text" name="about_step_talent_{{ $s }}_title_en" class="form-control" value="{{ $aboutSettings['about_step_talent_' . $s . '_title_en'] ?? $aboutSettings['about_step_talent_' . $s . '_title'] ?? '' }}">
                                </div>
                                <div class="dual-lang-col">
                                    <span class="lang-badge sw">🇹🇿 SW</span>
                                    <input type="text" name="about_step_talent_{{ $s }}_title_sw" class="form-control" value="{{ $aboutSettings['about_step_talent_' . $s . '_title_sw'] ?? '' }}">
                                </div>
                            </div>
                            <label style="margin-top: 6px;">Talent Step {{ $s }} Description</label>
                            <div class="dual-lang-row">
                                <div class="dual-lang-col">
                                    <span class="lang-badge en">🇬🇧 EN</span>
                                    <textarea name="about_step_talent_{{ $s }}_text_en" rows="2" class="form-control">{{ $aboutSettings['about_step_talent_' . $s . '_text_en'] ?? $aboutSettings['about_step_talent_' . $s . '_text'] ?? '' }}</textarea>
                                </div>
                                <div class="dual-lang-col">
                                    <span class="lang-badge sw">🇹🇿 SW</span>
                                    <textarea name="about_step_talent_{{ $s }}_text_sw" rows="2" class="form-control">{{ $aboutSettings['about_step_talent_' . $s . '_text_sw'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>

                    <!-- For Clients -->
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px;">
                        <h4 style="font-size: 0.95rem; font-weight: 800; color: #0284c7; margin: 0 0 14px;">
                            <i class="bi bi-briefcase-fill"></i> Steps for Clients &amp; Organizers
                        </h4>
                        @for($c = 1; $c <= 3; $c++)
                        <div class="cms-field-group">
                            <label>Client Step {{ $c }} Title</label>
                            <div class="dual-lang-row">
                                <div class="dual-lang-col">
                                    <span class="lang-badge en">🇬🇧 EN</span>
                                    <input type="text" name="about_step_client_{{ $c }}_title_en" class="form-control" value="{{ $aboutSettings['about_step_client_' . $c . '_title_en'] ?? $aboutSettings['about_step_client_' . $c . '_title'] ?? '' }}">
                                </div>
                                <div class="dual-lang-col">
                                    <span class="lang-badge sw">🇹🇿 SW</span>
                                    <input type="text" name="about_step_client_{{ $c }}_title_sw" class="form-control" value="{{ $aboutSettings['about_step_client_' . $c . '_title_sw'] ?? '' }}">
                                </div>
                            </div>
                            <label style="margin-top: 6px;">Client Step {{ $c }} Description</label>
                            <div class="dual-lang-row">
                                <div class="dual-lang-col">
                                    <span class="lang-badge en">🇬🇧 EN</span>
                                    <textarea name="about_step_client_{{ $c }}_text_en" rows="2" class="form-control">{{ $aboutSettings['about_step_client_' . $c . '_text_en'] ?? $aboutSettings['about_step_client_' . $c . '_text'] ?? '' }}</textarea>
                                </div>
                                <div class="dual-lang-col">
                                    <span class="lang-badge sw">🇹🇿 SW</span>
                                    <textarea name="about_step_client_{{ $c }}_text_sw" rows="2" class="form-control">{{ $aboutSettings['about_step_client_' . $c . '_text_sw'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <!-- SUBTAB 6: OFFICE, GOOGLE MAP, SUPPORT & CTA -->
        <div id="subtab-office" class="about-subtab-pane" style="display: none;">
            <div class="cms-card">
                <div class="cms-card-header">
                    <h3><i class="bi bi-geo-alt-fill" style="color: #0066fe;"></i> Office Location, Map, Contacts &amp; CTA (Bilingual)</h3>
                    <button type="submit" class="vbtn" style="background: linear-gradient(135deg, #0066fe 0%, #2563eb 100%); color: #fff; border: none; border-radius: 10px; font-weight: 700; padding: 8px 18px; font-size: 0.85rem; cursor: pointer;">
                        <i class="bi bi-save"></i> Save Changes
                    </button>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div>
                        <h4 style="font-size: 0.95rem; font-weight: 800; color: #1e293b; margin: 0 0 12px;">Physical Headquarters &amp; Google Maps</h4>
                        <div class="cms-field-group">
                            <label>Physical Address (Used for Google Maps Embed &amp; Directions)</label>
                            <input type="text" name="office_address" class="form-control" value="{{ $aboutSettings['office_address'] ?? 'Dar es Salaam, Tanzania' }}">
                        </div>
                        <div class="cms-field-group">
                            <label>City &amp; Region</label>
                            <input type="text" name="office_city" class="form-control" value="{{ $aboutSettings['office_city'] ?? 'Dar es Salaam, Tanzania' }}">
                        </div>
                        <div class="cms-field-group">
                            <label>Landmark &amp; Building Details (Bilingual)</label>
                            <div class="dual-lang-row">
                                <div class="dual-lang-col">
                                    <span class="lang-badge en">🇬🇧 EN</span>
                                    <input type="text" name="office_landmark_en" class="form-control" value="{{ $aboutSettings['office_landmark_en'] ?? $aboutSettings['office_landmark'] ?? 'Near Makumbusho Bus Terminal & Village Museum' }}">
                                </div>
                                <div class="dual-lang-col">
                                    <span class="lang-badge sw">🇹🇿 SW</span>
                                    <input type="text" name="office_landmark_sw" class="form-control" value="{{ $aboutSettings['office_landmark_sw'] ?? 'Karibu na Kituo cha Mabasi cha Makumbusho na Kijiji cha Makumbusho' }}">
                                </div>
                            </div>
                        </div>
                        <div class="cms-field-group">
                            <label>Working Hours (Bilingual)</label>
                            <div class="dual-lang-row">
                                <div class="dual-lang-col">
                                    <span class="lang-badge en">🇬🇧 EN</span>
                                    <input type="text" name="office_hours_en" class="form-control" value="{{ $aboutSettings['office_hours_en'] ?? $aboutSettings['office_hours'] ?? 'Monday - Saturday: 8:00 AM - 6:00 PM (EAT)' }}">
                                </div>
                                <div class="dual-lang-col">
                                    <span class="lang-badge sw">🇹🇿 SW</span>
                                    <input type="text" name="office_hours_sw" class="form-control" value="{{ $aboutSettings['office_hours_sw'] ?? 'Jumatatu - Jumamosi: Saa 2:00 Asubuhi - Saa 12:00 Jioni (EAT)' }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 style="font-size: 0.95rem; font-weight: 800; color: #1e293b; margin: 0 0 12px;">Support Channels &amp; CTA Banner</h4>
                        <div class="cms-field-group">
                            <label>Support Phone Number</label>
                            <input type="text" name="support_phone" class="form-control" value="{{ $aboutSettings['support_phone'] ?? '0710383352' }}">
                        </div>
                        <div class="cms-field-group">
                            <label>WhatsApp Number</label>
                            <input type="text" name="whatsapp_number" class="form-control" value="{{ $aboutSettings['whatsapp_number'] ?? '0710383352' }}">
                        </div>
                        <div class="cms-field-group">
                            <label>Support Email Address</label>
                            <input type="email" name="support_email" class="form-control" value="{{ $aboutSettings['support_email'] ?? 'support@chapconnect.com' }}">
                        </div>
                        <div class="cms-field-group">
                            <label>Support Card Badge &amp; Title (Bilingual)</label>
                            <div class="dual-lang-row">
                                <div class="dual-lang-col">
                                    <span class="lang-badge en">🇬🇧 EN</span>
                                    <input type="text" name="about_support_title_en" class="form-control" value="{{ $aboutSettings['about_support_title_en'] ?? $aboutSettings['about_support_title'] ?? 'Get in Touch with Our Team' }}">
                                </div>
                                <div class="dual-lang-col">
                                    <span class="lang-badge sw">🇹🇿 SW</span>
                                    <input type="text" name="about_support_title_sw" class="form-control" value="{{ $aboutSettings['about_support_title_sw'] ?? 'Unahitaji Msaada au Una Maswali?' }}">
                                </div>
                            </div>
                        </div>
                        <div class="cms-field-group">
                            <label>Bottom CTA Banner Title (Bilingual)</label>
                            <div class="dual-lang-row">
                                <div class="dual-lang-col">
                                    <span class="lang-badge en">🇬🇧 EN</span>
                                    <input type="text" name="about_cta_title_en" class="form-control" value="{{ $aboutSettings['about_cta_title_en'] ?? $aboutSettings['about_cta_title'] ?? 'Ready to Showcase Your Talent?' }}">
                                </div>
                                <div class="dual-lang-col">
                                    <span class="lang-badge sw">🇹🇿 SW</span>
                                    <input type="text" name="about_cta_title_sw" class="form-control" value="{{ $aboutSettings['about_cta_title_sw'] ?? 'Uko Tayari Kuonyesha Kipaji Chako?' }}">
                                </div>
                            </div>
                        </div>
                        <div class="cms-field-group">
                            <label>Bottom CTA Banner Subtitle (Bilingual)</label>
                            <div class="dual-lang-row">
                                <div class="dual-lang-col">
                                    <span class="lang-badge en">🇬🇧 EN</span>
                                    <input type="text" name="about_cta_subtitle_en" class="form-control" value="{{ $aboutSettings['about_cta_subtitle_en'] ?? $aboutSettings['about_cta_subtitle'] ?? 'Join thousands of creatives and build your verified digital portfolio today.' }}">
                                </div>
                                <div class="dual-lang-col">
                                    <span class="lang-badge sw">🇹🇿 SW</span>
                                    <input type="text" name="about_cta_subtitle_sw" class="form-control" value="{{ $aboutSettings['about_cta_subtitle_sw'] ?? 'Jiunge na maelfu ya wabunifu na ujenge wasifu wako uliothibitishwa wa kidijitali leo.' }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- SUBTAB 7: OFFICIAL ANNOUNCEMENTS & POSTS (ADMIN & CUSTOMER CARE) -->
    <div id="subtab-announcements" class="about-subtab-pane" style="display: none;">
        <!-- Card 1: Create New Post Form -->
        <div class="cms-card">
            <div class="cms-card-header">
                <h3><i class="bi bi-pencil-square" style="color: #0066fe;"></i> Post Official Announcement / Update to About Page</h3>
                <span style="font-size: 0.78rem; color: #64748b;">Posting as: <strong>{{ auth()->user()->name }}</strong> ({{ auth()->user()->role === 'admin' ? 'Admin' : 'Customer Care' }})</span>
            </div>

            <form action="{{ route('staff.about.posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="cms-field-group">
                    <label>Article Title * (Bilingual)</label>
                    <div class="dual-lang-row">
                        <div class="dual-lang-col">
                            <span class="lang-badge en"><i class="bi bi-translate"></i> 🇬🇧 English Title *</span>
                            <input type="text" name="title" class="form-control" required placeholder="e.g. ChapConnect Launches Verified Badge System for Performing Artists">
                        </div>
                        <div class="dual-lang-col">
                            <span class="lang-badge sw"><i class="bi bi-translate"></i> 🇹🇿 Kiswahili Title (Optional)</span>
                            <input type="text" name="title_sw" class="form-control" placeholder="mf. ChapConnect Yazindua Mfumo wa Beji Zilizothibitishwa kwa Wasanii">
                        </div>
                    </div>
                </div>

                <div class="cms-field-group">
                    <label>Badge / Category Tag (Bilingual)</label>
                    <div class="dual-lang-row">
                        <div class="dual-lang-col">
                            <span class="lang-badge en">🇬🇧 English Badge</span>
                            <input type="text" name="badge" class="form-control" placeholder="e.g. Announcement, Milestone, Press Release" value="Announcement">
                        </div>
                        <div class="dual-lang-col">
                            <span class="lang-badge sw">🇹🇿 Kiswahili Badge</span>
                            <input type="text" name="badge_sw" class="form-control" placeholder="mf. Tangazo, Habari, Taarifa Rasmi" value="Tangazo">
                        </div>
                    </div>
                </div>

                <div class="cms-field-group">
                    <label>Featured Image / Banner (Optional, Auto-compressed to WebP)</label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <small style="color: #64748b; font-size: 0.76rem;">Supported formats: JPG, PNG, WEBP. High quality images will be compressed automatically.</small>
                </div>

                <div class="cms-field-group">
                    <label>Article Content / Details * (Bilingual)</label>
                    <div class="dual-lang-row">
                        <div class="dual-lang-col">
                            <span class="lang-badge en"><i class="bi bi-translate"></i> 🇬🇧 English Content *</span>
                            <textarea name="content" rows="6" class="form-control" required placeholder="Write the full announcement or update details in English..."></textarea>
                        </div>
                        <div class="dual-lang-col">
                            <span class="lang-badge sw"><i class="bi bi-translate"></i> 🇹🇿 Kiswahili Content (Optional)</span>
                            <textarea name="content_sw" rows="6" class="form-control" placeholder="Andika maelezo kamili ya tangazo kwa Kiswahili hapa..."></textarea>
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button type="submit" class="vbtn" style="background: linear-gradient(135deg, #0066fe 0%, #2563eb 100%); color: #fff; border: none; border-radius: 10px; font-weight: 700; padding: 10px 24px; font-size: 0.9rem; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 4px 14px rgba(0, 102, 254, 0.3);">
                        <i class="bi bi-send-fill"></i> Publish to About Page
                    </button>
                </div>
            </form>
        </div>

        <!-- Card 2: List of Existing Announcements -->
        <div class="cms-card">
            <div class="cms-card-header">
                <h3><i class="bi bi-collection-fill" style="color: #0066fe;"></i> Published Announcements &amp; Articles</h3>
                <span style="font-size: 0.8rem; color: #64748b; font-weight: 600;">Total: {{ isset($aboutPosts) ? count($aboutPosts) : 0 }}</span>
            </div>

            @if(isset($aboutPosts) && count($aboutPosts) > 0)
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.86rem; text-align: left;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 2px solid #e2e8f0; color: #475569; font-size: 0.78rem; text-transform: uppercase;">
                            <th style="padding: 12px 14px;">Image</th>
                            <th style="padding: 12px 14px;">Title &amp; Languages</th>
                            <th style="padding: 12px 14px;">Posted By</th>
                            <th style="padding: 12px 14px;">Date</th>
                            <th style="padding: 12px 14px;">Status</th>
                            <th style="padding: 12px 14px; text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($aboutPosts as $post)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 12px 14px;">
                                @if($post->image_path)
                                <img src="{{ asset($post->image_path) }}" alt="{{ $post->title }}" style="width: 60px; height: 42px; object-fit: cover; border-radius: 6px; border: 1px solid #e2e8f0;">
                                @else
                                <div style="width: 60px; height: 42px; border-radius: 6px; background: #e0f2fe; color: #0066fe; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
                                    <i class="bi bi-newspaper"></i>
                                </div>
                                @endif
                            </td>
                            <td style="padding: 12px 14px; max-width: 320px;">
                                <div style="display: flex; gap: 4px; margin-bottom: 4px; flex-wrap: wrap;">
                                    <span style="background: #e0f2fe; color: #0369a1; font-size: 0.68rem; font-weight: 700; padding: 1px 6px; border-radius: 6px;">
                                        🇬🇧 {{ $post->badge }}
                                    </span>
                                    @if($post->badge_sw)
                                    <span style="background: #dcfce7; color: #15803d; font-size: 0.68rem; font-weight: 700; padding: 1px 6px; border-radius: 6px;">
                                        🇹🇿 {{ $post->badge_sw }}
                                    </span>
                                    @endif
                                </div>
                                <div style="font-weight: 700; color: #0f172a; line-height: 1.3;">🇬🇧 {{ $post->title }}</div>
                                @if($post->title_sw)
                                <div style="font-weight: 600; color: #059669; font-size: 0.8rem; line-height: 1.3; margin-top: 2px;">🇹🇿 {{ $post->title_sw }}</div>
                                @endif
                            </td>
                            <td style="padding: 12px 14px; font-weight: 600; color: #334155;">
                                <div>{{ $post->author_name }}</div>
                                <small style="color: #64748b; font-size: 0.74rem;">{{ $post->author_role }}</small>
                            </td>
                            <td style="padding: 12px 14px; color: #64748b; font-size: 0.82rem; white-space: nowrap;">
                                {{ $post->created_at->format('M d, Y') }}
                            </td>
                            <td style="padding: 12px 14px;">
                                @if($post->is_published)
                                <span style="background: rgba(16, 185, 129, 0.15); color: #059669; font-size: 0.72rem; font-weight: 800; padding: 3px 9px; border-radius: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                    <span style="width: 5px; height: 5px; border-radius: 50%; background: #059669;"></span> Published
                                </span>
                                @else
                                <span style="background: rgba(100, 116, 139, 0.15); color: #64748b; font-size: 0.72rem; font-weight: 800; padding: 3px 9px; border-radius: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                    <span style="width: 5px; height: 5px; border-radius: 50%; background: #64748b;"></span> Hidden
                                </span>
                                @endif
                            </td>
                            <td style="padding: 12px 14px; text-align: right; white-space: nowrap;">
                                <div style="display: inline-flex; gap: 6px;">
                                    <!-- Toggle Visibility Form -->
                                    <form action="{{ route('staff.about.posts.toggle', $post->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="vbtn" title="{{ $post->is_published ? 'Hide Post' : 'Publish Post' }}" style="background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; border-radius: 8px; padding: 6px 10px; font-size: 0.78rem; cursor: pointer;">
                                            <i class="bi bi-{{ $post->is_published ? 'eye-slash' : 'eye' }}"></i>
                                        </button>
                                    </form>

                                    <!-- Delete Form -->
                                    <form action="{{ route('staff.about.posts.delete', $post->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this announcement?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="vbtn" title="Delete Post" style="background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.25); border-radius: 8px; padding: 6px 10px; font-size: 0.78rem; cursor: pointer;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div style="text-align: center; padding: 35px 20px; color: #64748b;">
                <i class="bi bi-newspaper" style="font-size: 2.2rem; color: #cbd5e1; display: block; margin-bottom: 10px;"></i>
                <h4 style="margin: 0 0 6px 0; color: #1e293b; font-weight: 700;">No Announcements Posted Yet</h4>
                <p style="margin: 0; font-size: 0.85rem;">Use the form above to publish official updates or press releases to the About Us page in English and Swahili.</p>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    function switchAboutSubtab(subtabId, btn) {
        document.querySelectorAll('.about-subtab-pane').forEach(function(pane) {
            pane.style.display = 'none';
        });
        const target = document.getElementById('subtab-' + subtabId);
        if (target) {
            target.style.display = 'block';
        }
        document.querySelectorAll('.about-subtab-btn').forEach(function(b) {
            b.classList.remove('active');
        });
        if (btn) {
            btn.classList.add('active');
        }
    }
</script>
