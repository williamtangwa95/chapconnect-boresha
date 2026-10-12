@extends('layouts.app')

@section('title', __('About Us') . ' - ' . \App\Models\SystemSetting::get('site_title', 'ChapConnect'))

@section('styles')
<style>
    /* ========================================================
       ChapConnect Professional & Elegant Light Theme - About Us
       Refined typography and fully responsive for all screen sizes
       ======================================================== */
    .about-page-wrapper {
        display: block !important;
        max-width: 1550px !important;
        width: 98% !important;
        padding: 20px 0 50px !important;
        margin: 0 auto !important;
        box-sizing: border-box;
    }

    /* 1. Hero Section - Refined, Balanced & Elegant */
    .about-hero {
        position: relative;
        background: linear-gradient(145deg, #ffffff 0%, #f0f7ff 55%, #e0f2fe 100%);
        border: 1px solid #bae6fd;
        border-radius: 22px;
        padding: 44px 28px 36px;
        color: #0f172a;
        box-shadow: 0 10px 28px -8px rgba(0, 102, 254, 0.08), 0 1px 3px rgba(0, 0, 0, 0.04);
        overflow: hidden;
        margin-bottom: 32px;
        text-align: center;
    }

    .about-hero::before {
        content: "";
        position: absolute;
        top: -50px;
        right: -50px;
        width: 260px;
        height: 260px;
        background: radial-gradient(circle, rgba(0, 102, 254, 0.07) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
    }

    .about-badge-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #ffffff;
        border: 1px solid #bfdbfe;
        color: #0066fe;
        font-size: 0.74rem;
        font-weight: 700;
        padding: 5px 14px;
        border-radius: 50px;
        margin-bottom: 16px;
        box-shadow: 0 2px 8px rgba(0, 102, 254, 0.07);
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }

    .about-hero-title {
        font-size: clamp(1.45rem, 2.8vw, 2.15rem);
        font-weight: 800;
        line-height: 1.25;
        margin-bottom: 14px;
        color: #0f172a;
        letter-spacing: -0.3px;
    }

    .about-hero-title .accent-text {
        color: #0066fe;
        background: linear-gradient(135deg, #0066fe 0%, #2563eb 60%, #0284c7 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .about-hero-subtitle {
        max-width: 720px;
        margin: 0 auto 26px;
        font-size: 0.93rem;
        line-height: 1.65;
        color: #475569;
        font-weight: 400;
    }

    .about-hero-actions {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 34px;
    }

    .about-btn-primary {
        background: linear-gradient(135deg, #0066fe 0%, #2563eb 100%);
        color: #ffffff !important;
        font-weight: 700;
        padding: 10px 22px;
        border-radius: 50px;
        text-decoration: none;
        font-size: 0.86rem;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        box-shadow: 0 6px 18px rgba(0, 102, 254, 0.28);
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        cursor: pointer;
    }

    .about-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(0, 102, 254, 0.4);
        background: linear-gradient(135deg, #0052cc 0%, #1d4ed8 100%);
    }

    .about-btn-secondary {
        background: #ffffff;
        color: #1e293b !important;
        font-weight: 700;
        padding: 10px 20px;
        border-radius: 50px;
        text-decoration: none;
        font-size: 0.86rem;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border: 1.5px solid #cbd5e1;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        transition: all 0.25s ease;
        cursor: pointer;
    }

    .about-btn-secondary:hover {
        background: #f8fafc;
        border-color: #94a3b8;
        transform: translateY(-2px);
    }

    .about-btn-support {
        background: #eff6ff;
        color: #0369a1 !important;
        font-weight: 700;
        padding: 10px 20px;
        border-radius: 50px;
        text-decoration: none;
        font-size: 0.86rem;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border: 1.5px solid #bae6fd;
        transition: all 0.25s ease;
        cursor: pointer;
    }

    .about-btn-support:hover {
        background: #e0f2fe;
        transform: translateY(-2px);
    }

    /* Live Stats Grid - Compact, Clean & Professional */
    .about-stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        width: 100%;
        max-width: 100%;
        margin: 0 auto;
    }

    .about-stat-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 18px 14px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
        transition: all 0.25s ease;
        text-align: center;
    }

    .about-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 22px rgba(0, 102, 254, 0.09);
        border-color: #93c5fd;
    }

    .about-stat-icon-badge {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        margin: 0 auto 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
    }

    .about-stat-card:nth-child(1) .about-stat-icon-badge {
        background: #fef3c7;
        color: #d97706;
    }
    .about-stat-card:nth-child(2) .about-stat-icon-badge {
        background: #dbeafe;
        color: #1d4ed8;
    }
    .about-stat-card:nth-child(3) .about-stat-icon-badge {
        background: #f3e8ff;
        color: #7e22ce;
    }
    .about-stat-card:nth-child(4) .about-stat-icon-badge {
        background: #d1fae5;
        color: #047857;
    }

    .about-stat-number {
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
        margin-bottom: 3px;
        letter-spacing: -0.3px;
    }

    .about-stat-label {
        font-size: 0.72rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    /* Section Headers */
    .about-section-header {
        text-align: center;
        margin-bottom: 26px;
    }

    .about-section-pill {
        display: inline-block;
        background: #eff6ff;
        color: #0066fe;
        font-size: 0.72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        padding: 4px 12px;
        border-radius: 30px;
        margin-bottom: 8px;
        border: 1px solid #bfdbfe;
    }

    .about-section-title {
        font-size: clamp(1.25rem, 2.3vw, 1.6rem);
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -0.3px;
        line-height: 1.25;
        margin-bottom: 8px;
    }

    .about-section-subtitle {
        font-size: 0.88rem;
        color: #64748b;
        max-width: 620px;
        margin: 0 auto;
        line-height: 1.55;
    }

    /* 2. Story Grid */
    .about-story-grid {
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        gap: 26px;
        align-items: center;
        background: #ffffff;
        border-radius: 20px;
        padding: 34px 30px;
        margin-bottom: 34px;
        box-shadow: 0 6px 20px -4px rgba(0, 0, 0, 0.04);
        border: 1px solid #e2e8f0;
    }

    .about-story-text h3 {
        font-size: 1.35rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 14px;
        line-height: 1.3;
    }

    .about-story-text p {
        font-size: 0.88rem;
        line-height: 1.68;
        color: #475569;
        margin-bottom: 12px;
    }

    .about-story-highlights {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-top: 18px;
    }

    .about-highlight-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.82rem;
        font-weight: 700;
        color: #1e293b;
    }

    .about-highlight-item i {
        font-size: 1rem;
        color: #059669;
        background: #d1fae5;
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* Story Visual Box */
    .about-story-visual {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 18px;
        padding: 24px 20px;
        color: #0f172a;
        border: 1px solid #e2e8f0;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.04);
    }

    .about-visual-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 18px;
        border-bottom: 1px solid #e2e8f0;
        padding-bottom: 14px;
    }

    .about-visual-logo {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        object-fit: cover;
        border: 1.5px solid #e2e8f0;
    }

    .about-visual-brand h4 {
        margin: 0;
        font-size: 1.05rem;
        font-weight: 800;
        color: #0f172a;
    }

    .about-visual-brand span {
        font-size: 0.76rem;
        color: #0066fe;
        font-weight: 700;
    }

    .about-visual-badge-list {
        display: flex;
        flex-direction: column;
        gap: 9px;
    }

    .about-visual-badge-item {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 10px 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.82rem;
        font-weight: 700;
        color: #1e293b;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.02);
    }

    .about-visual-badge-item i {
        font-size: 1.1rem;
    }

    /* 3. Mission & Vision Cards */
    .about-mv-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 36px;
    }

    .about-mv-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 26px 22px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 16px -4px rgba(0, 0, 0, 0.03);
        position: relative;
        overflow: hidden;
        transition: all 0.25s ease;
    }

    .about-mv-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px -6px rgba(0, 102, 254, 0.1);
        border-color: #93c5fd;
    }

    .about-mv-card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
    }

    .about-mv-mission::before {
        background: linear-gradient(90deg, #0066fe, #38bdf8);
    }

    .about-mv-vision::before {
        background: linear-gradient(90deg, #10b981, #06b6d4);
    }

    .about-mv-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        margin-bottom: 14px;
    }

    .about-mv-mission .about-mv-icon {
        background: #eff6ff;
        color: #0066fe;
    }

    .about-mv-vision .about-mv-icon {
        background: #ecfdf5;
        color: #059669;
    }

    .about-mv-card h3 {
        font-size: 1.2rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
    }

    .about-mv-card p {
        font-size: 0.88rem;
        color: #475569;
        line-height: 1.65;
        margin: 0;
    }

    /* 4. Feature Pillars */
    .about-pillars-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 16px;
        margin-bottom: 36px;
    }

    .about-pillar-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 22px 18px;
        border: 1px solid #e2e8f0;
        transition: all 0.25s ease;
        box-shadow: 0 2px 10px -2px rgba(0, 0, 0, 0.03);
    }

    .about-pillar-card:hover {
        transform: translateY(-3px);
        border-color: #93c5fd;
        box-shadow: 0 8px 20px -4px rgba(0, 102, 254, 0.08);
    }

    .about-pillar-icon-box {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-bottom: 14px;
    }

    .about-pillar-card:nth-child(1) .about-pillar-icon-box {
        background: #ecfdf5;
        color: #059669;
    }
    .about-pillar-card:nth-child(2) .about-pillar-icon-box {
        background: #eff6ff;
        color: #0066fe;
    }
    .about-pillar-card:nth-child(3) .about-pillar-icon-box {
        background: #fdf2f8;
        color: #db2777;
    }
    .about-pillar-card:nth-child(4) .about-pillar-icon-box {
        background: #fffbeb;
        color: #d97706;
    }

    .about-pillar-card h4 {
        font-size: 1.02rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 6px;
    }

    .about-pillar-card p {
        font-size: 0.84rem;
        color: #64748b;
        line-height: 1.55;
        margin: 0;
    }

    /* 5. Workflow Steps (How It Works) */
    .about-workflow-section {
        background: #ffffff;
        border-radius: 20px;
        padding: 32px 26px;
        margin-bottom: 36px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 6px 20px -4px rgba(0, 0, 0, 0.03);
    }

    .about-workflow-columns {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 22px;
        margin-top: 22px;
    }

    .about-workflow-col {
        background: #f8fafc;
        border-radius: 16px;
        padding: 22px 18px;
        border: 1px solid #e2e8f0;
    }

    .about-workflow-col-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1.5px solid #e2e8f0;
    }

    .about-workflow-col-header i {
        font-size: 1.25rem;
        color: #0066fe;
    }

    .about-workflow-col-header h3 {
        font-size: 1.08rem;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }

    .about-step-list {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .about-step-item {
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .about-step-number {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        background: #0066fe;
        color: #ffffff;
        font-weight: 800;
        font-size: 0.76rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        margin-top: 1px;
    }

    .about-step-content h5 {
        font-size: 0.88rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 2px 0;
    }

    .about-step-content p {
        font-size: 0.8rem;
        color: #64748b;
        margin: 0;
        line-height: 1.5;
    }

    /* 6. Featured Talents Spotlight */
    .about-talents-section {
        margin-bottom: 36px;
    }

    .about-talents-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
        gap: 16px;
    }

    .about-talent-card {
        background: #ffffff;
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.03);
        transition: all 0.25s ease;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
    }

    .about-talent-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 22px rgba(0, 102, 254, 0.1);
        border-color: #93c5fd;
    }

    .about-talent-cover {
        height: 110px;
        position: relative;
        background: linear-gradient(135deg, #0066fe 0%, #1e40af 100%);
        overflow: hidden;
    }

    .about-talent-cover img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .about-talent-avatar-box {
        position: absolute;
        bottom: -22px;
        left: 16px;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        border: 2.5px solid #ffffff;
        overflow: hidden;
        background: #ffffff;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
    }

    .about-talent-avatar-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .about-talent-info {
        padding: 28px 16px 16px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .about-talent-name {
        font-size: 0.96rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 2px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .about-talent-category {
        font-size: 0.74rem;
        font-weight: 700;
        color: #0066fe;
        text-transform: uppercase;
        margin-bottom: 8px;
        letter-spacing: 0.3px;
    }

    .about-talent-meta {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: auto;
        padding-top: 10px;
        border-top: 1px solid #f1f5f9;
        font-size: 0.74rem;
        color: #64748b;
        font-weight: 600;
    }

    /* 7. Office Location & Google Map Section */
    .about-office-section {
        margin-bottom: 36px;
    }

    .about-office-grid {
        display: grid;
        grid-template-columns: 1fr 1.3fr;
        gap: 22px;
        align-items: stretch;
    }

    .about-office-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 26px 22px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 6px 20px -4px rgba(0, 0, 0, 0.03);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .about-office-header {
        margin-bottom: 18px;
    }

    .about-office-header h3 {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 6px;
        line-height: 1.25;
    }

    .about-office-header p {
        font-size: 0.84rem;
        color: #64748b;
        margin: 0;
        line-height: 1.5;
    }

    .about-office-details {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 20px;
    }

    .about-office-item {
        display: flex;
        gap: 12px;
        align-items: flex-start;
        padding: 10px 14px;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }

    .about-office-item-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: #eff6ff;
        color: #0066fe;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .about-office-item-content h5 {
        font-size: 0.72rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin: 0 0 2px 0;
    }

    .about-office-item-content p {
        font-size: 0.86rem;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        line-height: 1.35;
    }

    .about-office-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 8px;
    }

    .about-office-btn-map {
        background: linear-gradient(135deg, #0066fe 0%, #2563eb 100%);
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.82rem;
        padding: 8px 15px;
        border-radius: 10px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.25s ease;
        box-shadow: 0 3px 10px rgba(0, 102, 254, 0.22);
    }

    .about-office-btn-map:hover {
        transform: translateY(-2px);
    }

    .about-office-btn-call {
        background: #ffffff;
        color: #1e293b !important;
        font-weight: 700;
        font-size: 0.82rem;
        padding: 8px 14px;
        border-radius: 10px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid #cbd5e1;
        transition: all 0.25s ease;
    }

    .about-office-btn-call:hover {
        background: #f8fafc;
        transform: translateY(-2px);
    }

    /* Google Map Wrapper */
    .about-map-wrapper {
        background: #ffffff;
        border-radius: 20px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        box-shadow: 0 6px 20px -4px rgba(0, 0, 0, 0.04);
        position: relative;
        min-height: 340px;
        display: flex;
        flex-direction: column;
    }

    .about-map-header-bar {
        background: #f8fafc;
        color: #0f172a;
        padding: 10px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.8rem;
        font-weight: 700;
        border-bottom: 1px solid #e2e8f0;
    }

    .about-map-header-bar span {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .about-map-header-bar .live-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #10b981;
        box-shadow: 0 0 6px #10b981;
    }

    .about-map-frame {
        width: 100%;
        flex-grow: 1;
        min-height: 280px;
        border: 0;
        display: block;
    }

    .about-map-footer-bar {
        background: #ffffff;
        padding: 9px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px solid #f1f5f9;
        font-size: 0.78rem;
        color: #64748b;
    }

    /* 8. Support Desk & Channels */
    .about-support-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 30px 26px;
        margin-bottom: 36px;
        box-shadow: 0 6px 20px -4px rgba(0, 0, 0, 0.03);
    }

    .about-support-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 26px;
        align-items: center;
    }

    .about-support-channels {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .about-channel-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 16px;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        text-decoration: none;
        color: #1e293b;
        font-weight: 700;
        font-size: 0.86rem;
        transition: all 0.25s ease;
    }

    .about-channel-link:hover {
        transform: translateX(3px);
        border-color: #93c5fd;
        background: #ffffff;
        color: #0066fe;
    }

    .about-channel-link i {
        font-size: 1.2rem;
    }

    /* 9. Final CTA Banner */
    .about-cta-banner {
        background: linear-gradient(135deg, #0066fe 0%, #1e40af 100%);
        border-radius: 20px;
        padding: 40px 24px;
        color: #ffffff;
        text-align: center;
        box-shadow: 0 14px 30px -8px rgba(0, 102, 254, 0.3);
        position: relative;
        overflow: hidden;
    }

    .about-cta-title {
        font-size: clamp(1.35rem, 2.8vw, 1.85rem);
        font-weight: 800;
        margin-bottom: 10px;
        color: #ffffff;
        letter-spacing: -0.3px;
    }

    .about-cta-subtitle {
        font-size: 0.88rem;
        color: #e0f2fe;
        max-width: 580px;
        margin: 0 auto 22px;
        line-height: 1.55;
    }

    .about-cta-btn-white {
        background: #ffffff;
        color: #0066fe !important;
        font-weight: 800;
        padding: 10px 22px;
        border-radius: 50px;
        text-decoration: none;
        font-size: 0.86rem;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        transition: all 0.25s ease;
    }

    .about-cta-btn-white:hover {
        transform: translateY(-2px);
        background: #f8fafc;
    }

    .about-cta-btn-outline {
        background: rgba(255, 255, 255, 0.14);
        color: #ffffff !important;
        font-weight: 700;
        padding: 10px 20px;
        border-radius: 50px;
        text-decoration: none;
        font-size: 0.86rem;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        border: 1.5px solid rgba(255, 255, 255, 0.4);
        transition: all 0.25s ease;
    }

    .about-cta-btn-outline:hover {
        background: rgba(255, 255, 255, 0.22);
        transform: translateY(-2px);
    }

    /* Announcements & Official Posts Section */
    .about-announcements-section {
        margin-bottom: 36px;
    }

    .about-announcements-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .about-announcement-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
        display: flex;
        flex-direction: column;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }

    .about-announcement-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0, 102, 254, 0.08);
        border-color: #bfdbfe;
    }

    .about-announcement-img {
        width: 100%;
        height: 190px;
        object-fit: cover;
        background: #f1f5f9;
        display: block;
    }

    .about-announcement-body {
        padding: 20px 20px 18px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }

    .about-announcement-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 0.74rem;
        color: #64748b;
    }

    .about-announcement-badge {
        background: #eff6ff;
        color: #0066fe;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.72rem;
        border: 1px solid #dbeafe;
    }

    .about-announcement-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 10px;
        line-height: 1.35;
    }

    .about-announcement-content {
        font-size: 0.85rem;
        color: #475569;
        line-height: 1.6;
        margin-bottom: 16px;
        flex-grow: 1;
    }

    .about-announcement-footer {
        padding-top: 12px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.74rem;
        color: #64748b;
    }

    /* 10. Modern Footer */
    .about-footer {
        margin-top: 40px;
        padding-top: 28px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 14px;
        font-size: 0.82rem;
        color: #64748b;
    }

    .about-footer-links {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .about-footer-links a {
        color: #475569;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s ease;
    }

    .about-footer-links a:hover {
        color: #0066fe;
    }

    /* ========================================================
       RESPONSIVE RULES FOR SMALL DEVICES & MOBILE (<= 768px)
       ======================================================== */
    @media (max-width: 900px) {
        .about-story-grid,
        .about-support-grid,
        .about-office-grid {
            grid-template-columns: 1fr;
        }

        .about-stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .about-page-wrapper {
            display: block !important;
            max-width: 1550px !important;
            width: 98% !important;
            padding: 12px 0 40px !important;
            margin: 0 auto !important;
        }

        .about-hero {
            padding: 28px 16px 22px;
            border-radius: 18px;
            margin-bottom: 22px;
        }

        .about-hero-title {
            font-size: 1.35rem;
            line-height: 1.3;
            margin-bottom: 10px;
        }

        .about-hero-subtitle {
            font-size: 0.84rem;
            line-height: 1.5;
            margin-bottom: 18px;
        }

        .about-hero-actions {
            flex-direction: column;
            gap: 8px;
            width: 100%;
            margin-bottom: 24px;
        }

        .about-btn-primary,
        .about-btn-secondary,
        .about-btn-support {
            width: 100%;
            max-width: 100%;
            justify-content: center;
            padding: 9px 16px;
            font-size: 0.82rem;
        }

        .about-stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .about-stat-card {
            padding: 12px 10px;
            border-radius: 12px;
        }

        .about-stat-icon-badge {
            width: 32px;
            height: 32px;
            font-size: 1rem;
            margin-bottom: 6px;
            border-radius: 8px;
        }

        .about-stat-number {
            font-size: 1.3rem;
        }

        .about-stat-label {
            font-size: 0.68rem;
        }

        .about-story-grid {
            padding: 20px 16px;
            border-radius: 16px;
            margin-bottom: 22px;
            gap: 18px;
        }

        .about-story-text h3 {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .about-story-text p {
            font-size: 0.84rem;
            line-height: 1.55;
        }

        .about-story-highlights {
            grid-template-columns: 1fr;
            gap: 8px;
        }

        .about-story-visual {
            padding: 16px 14px;
            border-radius: 14px;
        }

        .about-mv-grid {
            gap: 12px;
            margin-bottom: 24px;
        }

        .about-mv-card {
            padding: 18px 16px;
            border-radius: 14px;
        }

        .about-mv-card h3 {
            font-size: 1.1rem;
        }

        .about-mv-card p {
            font-size: 0.84rem;
        }

        .about-pillars-grid {
            gap: 10px;
            margin-bottom: 24px;
        }

        .about-pillar-card {
            padding: 16px 14px;
            border-radius: 14px;
        }

        .about-workflow-section {
            padding: 20px 14px;
            border-radius: 16px;
            margin-bottom: 24px;
        }

        .about-workflow-columns {
            gap: 14px;
            margin-top: 14px;
        }

        .about-workflow-col {
            padding: 16px 12px;
            border-radius: 14px;
        }

        .about-office-grid {
            gap: 16px;
        }

        .about-office-card {
            padding: 20px 16px;
            border-radius: 16px;
        }

        .about-office-header h3 {
            font-size: 1.15rem;
        }

        .about-map-wrapper {
            min-height: 270px;
            border-radius: 16px;
        }

        .about-map-frame {
            min-height: 230px;
        }

        .about-support-card {
            padding: 20px 16px;
            border-radius: 16px;
            margin-bottom: 24px;
        }

        .about-support-card h3 {
            font-size: 1.25rem !important;
        }

        .about-support-card p {
            font-size: 0.84rem !important;
        }

        .about-cta-banner {
            padding: 28px 16px;
            border-radius: 16px;
            margin-bottom: 20px;
        }

        .about-cta-title {
            font-size: 1.25rem;
        }

        .about-cta-subtitle {
            font-size: 0.82rem;
            margin-bottom: 16px;
        }

        .about-footer {
            flex-direction: column;
            text-align: center;
            gap: 12px;
            font-size: 0.76rem;
        }

        .about-footer-links {
            justify-content: center;
            gap: 10px;
        }
    }
</style>
@endsection

@section('content')
@php
    $cms = function($key, $default = '') use ($aboutSettings) {
        $loc = app()->getLocale();
        if ($loc === 'sw' && !empty($aboutSettings[$key . '_sw'])) {
            return $aboutSettings[$key . '_sw'];
        }
        if ($loc === 'en' && !empty($aboutSettings[$key . '_en'])) {
            return $aboutSettings[$key . '_en'];
        }
        $val = $aboutSettings[$key] ?? $default;
        return __($val);
    };
@endphp
<main class="about-page-wrapper" id="aboutPageMain">

    <!-- 1. Hero Section - Dynamic Database Backed -->
    <section class="about-hero" id="aboutHeroSection" aria-label="About Hero">
        <div class="about-badge-pill">
            <i class="bi bi-patch-check-fill" style="color: #0066fe;"></i>
            {{ $cms('about_hero_badge', 'Empowering Creators, Performers & Visionaries Across East Africa') }}
        </div>

        <h1 class="about-hero-title">
            @php
                $heroTitle = $cms('about_hero_title', 'Connecting Talents with Limitless Opportunities');
            @endphp
            @if($heroTitle === 'Connecting Talents with Limitless Opportunities' || $heroTitle === __('Connecting Talents with Limitless Opportunities'))
                {{ __('Connecting Talents with') }} <span class="accent-text">{{ __('Limitless Opportunities') }}</span>
            @elseif($heroTitle === 'Kuunganisha Vipaji na Fursa Zisizo na Kikomo')
                Kuunganisha Vipaji na <span class="accent-text">Fursa Zisizo na Kikomo</span>
            @else
                {{ $heroTitle }}
            @endif
        </h1>

        <p class="about-hero-subtitle">
            {{ $cms('about_hero_subtitle', 'ChapConnect is the next-generation creative talent directory and verified networking platform. We help musicians, dancers, comedians, models, MCs, visual artists, and performers showcase their artistry and connect directly with clients, promoters, event planners, and audiences worldwide.') }}
        </p>

        <div class="about-hero-actions">
            <a href="{{ route('home') }}" class="about-btn-primary" id="exploreTalentsBtn">
                <i class="bi bi-compass-fill"></i>
                {{ $cms('about_hero_btn_explore', 'Explore Talents') }}
            </a>
            @guest
            <a href="{{ route('register') }}" class="about-btn-secondary" id="joinTalentHeroBtn">
                <i class="bi bi-person-plus-fill" style="color: #0066fe;"></i>
                {{ $cms('about_hero_btn_join', 'Join as a Talent') }}
            </a>
            @endguest
            <button type="button" onclick="openGlobalSupportModal(); return false;" class="about-btn-support" id="contactSupportHeroBtn">
                <i class="bi bi-headset"></i>
                {{ __('Help desk & Support') }}
            </button>
            @auth
            @if(in_array(auth()->user()->role, ['admin', 'customer_care']))
            <a href="{{ auth()->user()->role === 'customer_care' ? route('customer-care.dashboard') . '#about' : route('admin.dashboard') . '#about' }}" class="about-btn-secondary" style="border-color: #38bdf8; background: #f0f9ff; color: #0284c7 !important;">
                <i class="bi bi-pencil-square" style="color: #0284c7;"></i> {{ __('Manage Page Content') }}
            </a>
            @endif
            @endauth
        </div>

        <!-- Live Metrics Counter Strip -->
        <div class="about-stats-grid" id="aboutStatsGrid">
            <div class="about-stat-card">
                <div class="about-stat-icon-badge">
                    <i class="bi bi-star-fill"></i>
                </div>
                <div class="about-stat-number">{{ number_format($totalTalents) }}+</div>
                <div class="about-stat-label">{{ __('Active Talents') }}</div>
            </div>

            <div class="about-stat-card">
                <div class="about-stat-icon-badge">
                    <i class="bi bi-grid-fill"></i>
                </div>
                <div class="about-stat-number">{{ $totalCategories }}+</div>
                <div class="about-stat-label">{{ __('Creative Categories') }}</div>
            </div>

            <div class="about-stat-card">
                <div class="about-stat-icon-badge">
                    <i class="bi bi-camera-reels-fill"></i>
                </div>
                <div class="about-stat-number">{{ number_format($totalMedia) }}+</div>
                <div class="about-stat-label">{{ __('Media Showcases') }}</div>
            </div>

            <div class="about-stat-card">
                <div class="about-stat-icon-badge">
                    <i class="bi bi-lightning-charge-fill"></i>
                </div>
                <div class="about-stat-number">100%</div>
                <div class="about-stat-label">{{ __('Direct Connections') }}</div>
            </div>
        </div>
    </section>

    <!-- 2. Narrative / Who We Are -->
    <section class="about-story-grid" id="aboutStorySection">
        <div class="about-story-text">
            <span class="about-section-pill">{{ $cms('about_story_badge', 'Who We Are') }}</span>
            <h3>{{ $cms('about_story_title', 'Building the Future of Talent Discovery & Direct Booking') }}</h3>
            <p>
                {{ $cms('about_story_p1', 'In East Africa and beyond, millions of remarkably talented people go unnoticed simply because they lack professional digital presence and direct access to clients, brands, event managers, and entertainment agencies.') }}
            </p>
            <p>
                {{ $cms('about_story_p2', 'ChapConnect was created to solve this challenge. We provide every creative individual—from budding artists to seasoned professionals—with an all-in-one digital portfolio where they can present verified bios, high-resolution photo galleries, performance videos, and direct contact details.') }}
            </p>
            <div class="about-story-highlights">
                <div class="about-highlight-item">
                    <i class="bi bi-check2"></i>
                    <span>{{ $cms('about_highlight_1', 'Zero middleman commission') }}</span>
                </div>
                <div class="about-highlight-item">
                    <i class="bi bi-check2"></i>
                    <span>{{ $cms('about_highlight_2', 'Verified contact credentials') }}</span>
                </div>
                <div class="about-highlight-item">
                    <i class="bi bi-check2"></i>
                    <span>{{ $cms('about_highlight_3', 'High-definition media playback') }}</span>
                </div>
                <div class="about-highlight-item">
                    <i class="bi bi-check2"></i>
                    <span>{{ $cms('about_highlight_4', 'Swahili & English interface') }}</span>
                </div>
            </div>
        </div>

        <!-- Corporate Brand Visual Card -->
        <div class="about-story-visual">
            <div class="about-visual-header">
                <img src="{{ asset(\App\Models\SystemSetting::get('site_logo', 'logo.png')) }}" alt="Logo" class="about-visual-logo" onerror="this.src='https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=100&auto=format&fit=crop'">
                <div class="about-visual-brand">
                    <h4>{{ \App\Models\SystemSetting::get('site_title', 'ChapConnect') }}</h4>
                    <span>{{ $cms('about_visual_brand_subtitle', 'Official Creative Network • Tanzania') }}</span>
                </div>
            </div>

            <div class="about-visual-badge-list">
                <div class="about-visual-badge-item">
                    <i class="bi bi-shield-fill-check" style="color: #059669;"></i>
                    <span>{{ $cms('about_visual_badge_1', 'Active Community Content Moderation') }}</span>
                </div>
                <div class="about-visual-badge-item">
                    <i class="bi bi-whatsapp" style="color: #10b981;"></i>
                    <span>{{ $cms('about_visual_badge_2', 'Instant WhatsApp & Telephone Direct Connect') }}</span>
                </div>
                <div class="about-visual-badge-item">
                    <i class="bi bi-globe2" style="color: #0066fe;"></i>
                    <span>{{ $cms('about_visual_badge_3', 'Global Visibility for Local Tanzanian Creators') }}</span>
                </div>
                <div class="about-visual-badge-item">
                    <i class="bi bi-headset" style="color: #0284c7;"></i>
                    <span>{{ $cms('about_visual_badge_4', 'Dedicated Helpdesk & Friendly Staff Care') }}</span>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Mission & Vision -->
    <section class="about-mv-grid" id="aboutMissionVisionSection">
        <div class="about-mv-card about-mv-mission">
            <div class="about-mv-icon">
                <i class="bi bi-rocket-takeoff-fill"></i>
            </div>
            <h3>{{ $cms('about_mission_title', 'Our Mission') }}</h3>
            <p>
                {{ $cms('about_mission_text', 'To empower every creative individual across East Africa with a world-class digital platform to showcase their gifts, gain authentic visibility, and monetize their skills through friction-free, direct connections with clients and audiences worldwide.') }}
            </p>
        </div>

        <div class="about-mv-card about-mv-vision">
            <div class="about-mv-icon">
                <i class="bi bi-eye-fill"></i>
            </div>
            <h3>{{ $cms('about_vision_title', 'Our Vision') }}</h3>
            <p>
                {{ $cms('about_vision_text', 'To become the most comprehensive, respected, and vibrant digital ecosystem for creative discovery, cross-border talent booking, and cultural empowerment throughout Africa.') }}
            </p>
        </div>
    </section>

    <!-- 4. Why Choose ChapConnect (Core Pillars) -->
    <section id="aboutWhyChooseSection">
        <div class="about-section-header">
            <span class="about-section-pill">{{ $cms('about_pillars_badge', 'Why Choose ChapConnect') }}</span>
            <h2 class="about-section-title">{{ $cms('about_pillars_title', 'Designed for Creators & Clients Alike') }}</h2>
            <p class="about-section-subtitle">
                {{ $cms('about_pillars_subtitle', 'Everything you need to discover exceptional talent or build a thriving career in creative arts and entertainment.') }}
            </p>
        </div>

        <div class="about-pillars-grid">
            <div class="about-pillar-card">
                <div class="about-pillar-icon-box">
                    <i class="bi bi-telephone-inbound-fill"></i>
                </div>
                <h4>{{ $cms('about_pillar_1_title', 'Direct Contact, Zero Middlemen') }}</h4>
                <p>
                    {{ $cms('about_pillar_1_desc', 'Clients and organizers contact talents directly via WhatsApp, phone call, or official inquiry with no hidden fees or excessive brokerage commissions.') }}
                </p>
            </div>

            <div class="about-pillar-card">
                <div class="about-pillar-icon-box">
                    <i class="bi bi-collection-play-fill"></i>
                </div>
                <h4>{{ $cms('about_pillar_2_title', 'Showcase Full Portfolio') }}</h4>
                <p>
                    {{ $cms('about_pillar_2_desc', 'Upload and manage high-definition photos, YouTube and direct performance videos, press announcements, and social handles in one elegant link.') }}
                </p>
            </div>

            <div class="about-pillar-card">
                <div class="about-pillar-icon-box">
                    <i class="bi bi-translate"></i>
                </div>
                <h4>{{ $cms('about_pillar_3_title', 'Bilingual & Inclusive') }}</h4>
                <p>
                    {{ $cms('about_pillar_3_desc', 'Full dual-language support in Kiswahili and English, ensuring equal accessibility and high engagement for local and international users.') }}
                </p>
            </div>

            <div class="about-pillar-card">
                <div class="about-pillar-icon-box">
                    <i class="bi bi-shield-check"></i>
                </div>
                <h4>{{ $cms('about_pillar_4_title', 'Safe & Moderated Platform') }}</h4>
                <p>
                    {{ $cms('about_pillar_4_desc', 'Continuous moderation, secure authentication, dedicated customer care ticketing, and verified user controls protect our vibrant creative community.') }}
                </p>
            </div>
        </div>
    </section>

    <!-- 5. How It Works -->
    <section class="about-workflow-section" id="aboutWorkflowSection">
        <div class="about-section-header" style="margin-bottom: 0;">
            <span class="about-section-pill">{{ $cms('about_workflow_badge', 'How ChapConnect Works') }}</span>
            <h2 class="about-section-title">{{ $cms('about_workflow_title', 'Simple 3-Step Journey to Success') }}</h2>
            <p class="about-section-subtitle">
                {{ $cms('about_workflow_subtitle', 'Whether you are offering a talent or looking to hire one, ChapConnect makes the process seamless and transparent.') }}
            </p>
        </div>

        <div class="about-workflow-columns">
            <!-- For Talents -->
            <div class="about-workflow-col">
                <div class="about-workflow-col-header">
                    <i class="bi bi-palette-fill"></i>
                    <h3>{{ __('For Talents & Creators') }}</h3>
                </div>
                <div class="about-step-list">
                    <div class="about-step-item">
                        <div class="about-step-number">1</div>
                        <div class="about-step-content">
                            <h5>{{ $cms('about_step_talent_1_title', '1. Create Your Profile') }}</h5>
                            <p>{{ $cms('about_step_talent_1_text', 'Register your account, choose your primary talent category, and write an engaging professional bio.') }}</p>
                        </div>
                    </div>
                    <div class="about-step-item">
                        <div class="about-step-number">2</div>
                        <div class="about-step-content">
                            <h5>{{ $cms('about_step_talent_2_title', '2. Upload Media & Work') }}</h5>
                            <p>{{ $cms('about_step_talent_2_text', 'Add your best photos, performance videos, recent achievements, and verified contact channels.') }}</p>
                        </div>
                    </div>
                    <div class="about-step-item">
                        <div class="about-step-number">3</div>
                        <div class="about-step-content">
                            <h5>{{ $cms('about_step_talent_3_title', '3. Get Booked & Discovered') }}</h5>
                            <p>{{ $cms('about_step_talent_3_text', 'Publish your portfolio live on ChapConnect, receive direct client requests, and expand your audience.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- For Clients / Organizers -->
            <div class="about-workflow-col">
                <div class="about-workflow-col-header">
                    <i class="bi bi-briefcase-fill" style="color: #0284c7;"></i>
                    <h3>{{ __('For Clients & Event Organizers') }}</h3>
                </div>
                <div class="about-step-list">
                    <div class="about-step-item">
                        <div class="about-step-number" style="background: #0284c7;">1</div>
                        <div class="about-step-content">
                            <h5>{{ $cms('about_step_client_1_title', '1. Search & Filter') }}</h5>
                            <p>{{ $cms('about_step_client_1_text', 'Browse talents by category (Music, Dance, MCs, Comedy, etc.), location, and keyword with instantaneous search.') }}</p>
                        </div>
                    </div>
                    <div class="about-step-item">
                        <div class="about-step-number" style="background: #0284c7;">2</div>
                        <div class="about-step-content">
                            <h5>{{ $cms('about_step_client_2_title', '2. Review Portfolios') }}</h5>
                            <p>{{ $cms('about_step_client_2_text', 'Watch video reels, view photo shoots, check community reviews, and verify real work before reaching out.') }}</p>
                        </div>
                    </div>
                    <div class="about-step-item">
                        <div class="about-step-number" style="background: #0284c7;">3</div>
                        <div class="about-step-content">
                            <h5>{{ $cms('about_step_client_3_title', '3. Contact Directly') }}</h5>
                            <p>{{ $cms('about_step_client_3_text', 'Connect instantly with the talent via WhatsApp or direct phone call to agree on terms and book events.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 5b. Official Platform Announcements & Updates (Posted by Admin or Customer Care) -->
    @if(!empty($aboutPosts) && count($aboutPosts) > 0)
    <section class="about-announcements-section" id="aboutAnnouncementsSection">
        <div class="about-section-header">
            <span class="about-section-pill"><i class="bi bi-newspaper"></i> {{ __('Official Updates') }}</span>
            <h2 class="about-section-title">{{ __('Announcements & Platform News') }}</h2>
            <p class="about-section-subtitle">
                {{ __('Official news, community milestones, and notices posted by ChapConnect Administration and Customer Care.') }}
            </p>
        </div>

        <div class="about-announcements-grid">
            @foreach($aboutPosts as $post)
            <article class="about-announcement-card" id="aboutPostCard-{{ $post->id }}">
                @if($post->image_path)
                <img src="{{ asset($post->image_path) }}" alt="{{ $post->localized_title }}" class="about-announcement-img" loading="lazy">
                @endif
                <div class="about-announcement-body">
                    <div class="about-announcement-meta">
                        <span class="about-announcement-badge">{{ $post->localized_badge }}</span>
                        <span><i class="bi bi-calendar3"></i> {{ $post->created_at->format('M d, Y') }}</span>
                    </div>
                    <h3 class="about-announcement-title">{{ $post->localized_title }}</h3>
                    <div class="about-announcement-content">
                        {!! nl2br(e($post->localized_content)) !!}
                    </div>
                    <div class="about-announcement-footer">
                        <span><i class="bi bi-person-badge-fill" style="color: #0066fe;"></i> {{ __($post->author_name) }}</span>
                        <span style="font-weight: 700; color: #0284c7;">{{ __($post->author_role) }}</span>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </section>
    @endif

    <!-- 6. Featured Talents Spotlight (if available) -->
    @if(isset($featuredTalents) && $featuredTalents->count() > 0)
    <section class="about-talents-section" id="aboutFeaturedTalentsSection">
        <div class="about-section-header">
            <span class="about-section-pill">{{ __('Featured Talents') }}</span>
            <h2 class="about-section-title">{{ __('Discover the next wave of rising stars and professional talents across East Africa.') }}</h2>
        </div>

        <div class="about-talents-grid">
            @foreach($featuredTalents as $talent)
            <a href="{{ route('profile', $talent->id) }}" class="about-talent-card" id="aboutTalentCard-{{ $talent->id }}">
                <div class="about-talent-cover">
                    <img src="{{ $talent->cover_image_url ?? $talent->avatar_url }}" alt="{{ $talent->name }}" onerror="this.src='https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=500&auto=format&fit=crop'">
                    <div class="about-talent-avatar-box">
                        <img src="{{ $talent->avatar_url }}" alt="{{ $talent->name }}">
                    </div>
                </div>
                <div class="about-talent-info">
                    <div class="about-talent-name">
                        {{ $talent->name }}
                        @if($talent->is_verified)
                        <i class="bi bi-patch-check-fill" style="color: #0066fe; font-size: 0.9rem;"></i>
                        @endif
                    </div>
                    <div class="about-talent-category">{{ $talent->category_label ?? $talent->category }}</div>
                    <div class="about-talent-meta">
                        <span><i class="bi bi-heart-fill" style="color: #ef4444;"></i> {{ $talent->likes_received_count }}</span>
                        <span><i class="bi bi-people-fill" style="color: #0066fe;"></i> {{ $talent->followers_received_count }}</span>
                        <span style="margin-left: auto; color: #0066fe; font-weight: 700;">{{ __('Preview Profile') }} &rarr;</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    <!-- 7. Office Location & Google Map Section -->
    <section class="about-office-section" id="aboutOfficeLocationSection">
        <div class="about-section-header">
            <span class="about-section-pill"><i class="bi bi-geo-alt-fill"></i> {{ __('Visit Our Office') }}</span>
            <h2 class="about-section-title">{{ __('Office Location & Google Map') }}</h2>
            <p class="about-section-subtitle">
                {{ __('Find our physical offices or reach out directly to arrange in-person talent inquiries and consultations.') }}
            </p>
        </div>

        <div class="about-office-grid">
            <!-- Office Contact & Location Card -->
            <div class="about-office-card">
                <div>
                    <div class="about-office-header">
                        <h3>{{ \App\Models\SystemSetting::get('site_title', 'ChapConnect') }} {{ __('Official Headquarters') }}</h3>
                        <p>{{ __('Welcome to visit our administration center for portfolio verifications, artist registrations, partnership inquiries, and business bookings.') }}</p>
                    </div>

                    <div class="about-office-details">
                        <div class="about-office-item">
                            <div class="about-office-item-icon">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div class="about-office-item-content">
                                <h5>{{ __('Office Address') }}</h5>
                                <p>{{ __($officeAddress ?? \App\Models\SystemSetting::get('office_address', 'Dar es Salaam, Tanzania')) }}</p>
                                <span style="font-size: 0.78rem; color: #64748b; font-weight: 500;">{{ __($officeLandmark ?? 'Posta / City Center, Dar es Salaam') }}</span>
                            </div>
                        </div>

                        <div class="about-office-item">
                            <div class="about-office-item-icon" style="background: #ecfdf5; color: #059669;">
                                <i class="bi bi-clock-history"></i>
                            </div>
                            <div class="about-office-item-content">
                                <h5>{{ __('Working Hours') }}</h5>
                                <p>{{ __('Monday - Friday') }}: 08:00 - 18:00</p>
                                <span style="font-size: 0.78rem; color: #64748b; font-weight: 500;">{{ __('Saturday') }}: 09:00 - 15:00 | {{ __('Sunday & Public Holidays') }}: {{ __('Online Support 24/7') }}</span>
                            </div>
                        </div>

                        <div class="about-office-item">
                            <div class="about-office-item-icon" style="background: #eff6ff; color: #0066fe;">
                                <i class="bi bi-telephone-fill"></i>
                            </div>
                            <div class="about-office-item-content">
                                <h5>{{ __('Direct Phone / Desk') }}</h5>
                                <p>{{ \App\Models\SystemSetting::get('support_phone', '0710383352') }}</p>
                                <span style="font-size: 0.78rem; color: #64748b; font-weight: 500;">{{ __('Email') }}: {{ \App\Models\SystemSetting::get('support_email', 'support@chapconnect.com') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="about-office-actions">
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($officeAddress ?? 'Dar es Salaam, Tanzania') }}" target="_blank" rel="noopener noreferrer" class="about-office-btn-map" id="openGoogleMapsBtn">
                        <i class="bi bi-box-arrow-up-right"></i> {{ __('Open in Google Maps') }}
                    </a>
                    @if(\App\Models\SystemSetting::get('support_phone'))
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', \App\Models\SystemSetting::get('support_phone')) }}" class="about-office-btn-call">
                        <i class="bi bi-telephone"></i> {{ __('Call Office') }}
                    </a>
                    @endif
                    @if(\App\Models\SystemSetting::get('whatsapp_number'))
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', \App\Models\SystemSetting::get('whatsapp_number')) }}" target="_blank" class="about-office-btn-call" style="background: #ecfdf5; color: #065f46 !important; border-color: #a7f3d0;">
                        <i class="bi bi-whatsapp" style="color: #10b981;"></i> WhatsApp
                    </a>
                    @endif
                </div>
            </div>

            <!-- Embedded Interactive Google Map -->
            <div class="about-map-wrapper">
                <div class="about-map-header-bar">
                    <span>
                        <span class="live-dot"></span>
                        <i class="bi bi-geo-alt"></i> {{ __('Google Maps Live View') }}
                    </span>
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($officeAddress ?? 'Dar es Salaam, Tanzania') }}" target="_blank" rel="noopener noreferrer" style="color: #0066fe; text-decoration: none; font-size: 0.78rem; display: inline-flex; align-items: center; gap: 4px; font-weight: 700;">
                        {{ __('Get Directions') }} <i class="bi bi-chevron-right"></i>
                    </a>
                </div>

                <iframe
                    class="about-map-frame"
                    title="ChapConnect Office Google Map"
                    src="https://maps.google.com/maps?q={{ urlencode($officeAddress ?? 'Dar es Salaam, Tanzania') }}&t=&z=14&ie=UTF8&iwloc=&output=embed"
                    loading="lazy"
                    allowfullscreen
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>

                <div class="about-map-footer-bar">
                    <span><i class="bi bi-pin-map-fill" style="color: #ef4444;"></i> {{ $officeAddress ?? 'Dar es Salaam, Tanzania' }}</span>
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($officeAddress ?? 'Dar es Salaam, Tanzania') }}" target="_blank" rel="noopener noreferrer" style="color: #0066fe; text-decoration: none; font-weight: 700;">
                        {{ __('Open in Google Maps') }} &nearr;
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- 8. Support Desk & Channels -->
    <section class="about-support-card" id="aboutSupportCard">
        <div class="about-support-grid">
            <div>
                <span class="about-section-pill">{{ $cms('about_support_badge', 'Customer Support & Helpdesk') }}</span>
                <h3 style="font-size: 1.4rem; font-weight: 800; color: #0f172a; margin-bottom: 10px;">{{ $cms('about_support_title', 'Get in Touch with Our Team') }}</h3>
                <p style="font-size: 0.88rem; color: #64748b; line-height: 1.65; margin-bottom: 18px;">
                    {{ $cms('about_support_text', 'Have questions or need assistance? Our customer care desk is always here to support you. Whether you want help publishing your profile or need assistance with event bookings, our staff is ready to assist.') }}
                </p>
                <button type="button" onclick="openGlobalSupportModal(); return false;" class="about-btn-primary">
                    <i class="bi bi-headset"></i> {{ __('Help desk & Support') }}
                </button>
            </div>

            <div class="about-support-channels">
                @if(!empty($aboutSettings['whatsapp_number'] ?? \App\Models\SystemSetting::get('whatsapp_number')))
                @php $waNum = $aboutSettings['whatsapp_number'] ?? \App\Models\SystemSetting::get('whatsapp_number'); @endphp
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $waNum) }}" target="_blank" class="about-channel-link">
                    <i class="bi bi-whatsapp" style="color: #25d366;"></i>
                    <div>
                        <div style="font-size: 0.72rem; color: #64748b; font-weight: 600;">{{ __('WhatsApp Support') }}</div>
                        <div>{{ $waNum }}</div>
                    </div>
                    <i class="bi bi-box-arrow-up-right" style="margin-left: auto; font-size: 0.8rem; color: #94a3b8;"></i>
                </a>
                @endif

                @if(!empty($aboutSettings['support_phone'] ?? \App\Models\SystemSetting::get('support_phone')))
                @php $spPhone = $aboutSettings['support_phone'] ?? \App\Models\SystemSetting::get('support_phone'); @endphp
                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $spPhone) }}" class="about-channel-link">
                    <i class="bi bi-telephone-fill" style="color: #0066fe;"></i>
                    <div>
                        <div style="font-size: 0.72rem; color: #64748b; font-weight: 600;">{{ __('Direct Phone Call') }}</div>
                        <div>{{ $spPhone }}</div>
                    </div>
                    <i class="bi bi-chevron-right" style="margin-left: auto; font-size: 0.8rem; color: #94a3b8;"></i>
                </a>
                @endif

                @if(!empty($aboutSettings['support_email'] ?? \App\Models\SystemSetting::get('support_email')))
                @php $spEmail = $aboutSettings['support_email'] ?? \App\Models\SystemSetting::get('support_email'); @endphp
                <a href="mailto:{{ $spEmail }}" class="about-channel-link">
                    <i class="bi bi-envelope-fill" style="color: #ef4444;"></i>
                    <div>
                        <div style="font-size: 0.72rem; color: #64748b; font-weight: 600;">{{ __('Official Support Email') }}</div>
                        <div>{{ $spEmail }}</div>
                    </div>
                    <i class="bi bi-chevron-right" style="margin-left: auto; font-size: 0.8rem; color: #94a3b8;"></i>
                </a>
                @endif
            </div>
        </div>
    </section>

    <!-- 9. Final Call-to-Action -->
    <section class="about-cta-banner" id="aboutCtaBanner">
        <h2 class="about-cta-title">{{ $cms('about_cta_title', 'Ready to Showcase Your Talent?') }}</h2>
        <p class="about-cta-subtitle">
            {{ $cms('about_cta_subtitle', 'Join thousands of creatives and build your verified digital portfolio today.') }}
        </p>
        <div style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
            @guest
            <a href="{{ route('register') }}" class="about-cta-btn-white" id="aboutCtaRegisterBtn">
                <i class="bi bi-person-plus-fill"></i> {{ __('Register Account') }}
            </a>
            @endguest
            <a href="{{ route('home') }}" class="about-cta-btn-outline" id="aboutCtaBrowseBtn">
                <i class="bi bi-grid-fill"></i> {{ __('Browse Categories...') }}
            </a>
        </div>
    </section>

    <!-- 10. Modern Footer -->
    <footer class="about-footer" id="aboutPageFooter">
        <div>
            &copy; {{ date('Y') }} <strong>{{ \App\Models\SystemSetting::get('site_title', 'ChapConnect') }}</strong>. {{ __('All rights reserved.') }}
        </div>
        <nav class="about-footer-links" aria-label="About Footer Navigation">
            <a href="{{ route('home') }}">{{ __('Home') }}</a>
            <a href="{{ route('about') }}">{{ __('About Us') }}</a>
            <a href="{{ route('app.download') }}"><i class="bi bi-download"></i> {{ __('Download App') ?? 'App' }}</a>
            @guest
            <a href="{{ route('login') }}">{{ __('Login') }}</a>
            <a href="{{ route('register') }}">{{ __('Register') }}</a>
            @endguest
            <a href="#" onclick="openGlobalSupportModal(); return false;"><i class="bi bi-headset"></i> {{ __('Help desk & Support') }}</a>
        </nav>
    </footer>

</main>
@endsection
