@extends('layouts.app')

@section('title', 'ChapConnect - System Maintenance')

@section('content')
<main class="main" style="min-height: 85vh; display: flex; align-items: center; justify-content: center; padding: 40px 20px;">
    <div style="width: 100%; max-width: 580px;">
        <div style="background: linear-gradient(135deg, rgba(30, 41, 59, 0.95) 0%, rgba(15, 23, 42, 0.98) 100%); border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 24px; padding: 40px 30px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); backdrop-filter: blur(16px); color: #ffffff; text-align: center; position: relative; overflow: hidden;">
            
            <!-- Glowing Background Accent Light -->
            <div style="position: absolute; top: -60px; left: 50%; transform: translateX(-50%); width: 220px; height: 220px; background: radial-gradient(circle, rgba(99, 102, 241, 0.35) 0%, rgba(56, 189, 248, 0) 70%); filter: blur(30px); pointer-events: none;"></div>

            <!-- Maintenance Wrench/Gear Icon Badge -->
            <div style="width: 80px; height: 80px; margin: 0 auto 24px; border-radius: 50%; background: linear-gradient(135deg, rgba(99, 102, 241, 0.2), rgba(56, 189, 248, 0.2)); border: 2px solid rgba(99, 102, 241, 0.4); display: flex; align-items: center; justify-content: center; box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);">
                <i class="bi bi-tools" style="font-size: 2.2rem; color: #38bdf8; animation: rotateGear 12s infinite linear;"></i>
            </div>

            <!-- Status Pill Badge -->
            <div style="display: inline-flex; align-items: center; gap: 8px; background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.4); color: #fbbf24; padding: 6px 16px; border-radius: 20px; font-size: 0.82rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 18px;">
                <span style="width: 8px; height: 8px; border-radius: 50%; background: #f59e0b; display: inline-block; box-shadow: 0 0 10px #f59e0b; animation: pulseDot 1.5s infinite;"></span>
                {{ __('HUDUMA HAPATIKANI KWA MUDA') }}
            </div>

            <!-- Title -->
            <h2 style="font-size: 1.75rem; font-weight: 800; color: #ffffff; margin-bottom: 12px; letter-spacing: -0.5px;">
                @if(isset($feature) && strtolower($feature) === 'login')
                    {{ __('Access Control & Login Maintenance') }}
                @elseif(isset($feature) && (strtolower($feature) === 'register' || strtolower($feature) === 'registration'))
                    {{ __('Registration Maintenance') }}
                @elseif(isset($feature) && (strtolower($feature) === 'connect' || strtolower($feature) === 'ask_to_connect'))
                    {{ __('Ask to Connect Service Maintenance') }}
                @else
                    {{ __('System Maintenance in Progress') }}
                @endif
            </h2>

            <!-- Admin Custom Maintenance Message -->
            <div style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 16px; padding: 20px; margin: 20px 0; text-align: left;">
                <div style="display: flex; align-items: flex-start; gap: 12px;">
                    <i class="bi bi-info-circle-fill" style="font-size: 1.3rem; color: #38bdf8; margin-top: 2px;"></i>
                    <div style="font-size: 0.95rem; line-height: 1.6; color: #e2e8f0;">
                        {{ $message ?? MaintenanceService::getMessage() }}
                    </div>
                </div>
            </div>

            <!-- Time Schedule Details Box -->
            @php
                $details = $details ?? MaintenanceService::getDetails();
            @endphp
            @if(!empty($details['start_at_formatted']) && $details['start_at_formatted'] !== 'N/A')
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 24px;">
                <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.08); padding: 14px; border-radius: 12px; text-align: center;">
                    <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; margin-bottom: 4px;">
                        <i class="bi bi-clock-history" style="color: #38bdf8;"></i> {{ __('Muda wa Anza (Start)') }}
                    </div>
                    <div style="font-size: 0.92rem; font-weight: 800; color: #ffffff;">
                        {{ $details['start_at_formatted'] }}
                    </div>
                </div>
                <div style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.08); padding: 14px; border-radius: 12px; text-align: center;">
                    <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; margin-bottom: 4px;">
                        <i class="bi bi-check2-circle" style="color: #34d399;"></i> {{ __('Muda wa Mwisho (End)') }}
                    </div>
                    <div style="font-size: 0.92rem; font-weight: 800; color: #ffffff;">
                        {{ $details['end_at_formatted'] }}
                    </div>
                </div>
            </div>
            @endif

            <!-- Back to Home CTA -->
            <div style="display: flex; flex-direction: column; gap: 12px; margin-top: 25px;">
                <a href="{{ route('home') }}" style="display: inline-flex; align-items: center; justify-content: center; gap: 8px; background: linear-gradient(135deg, #6366f1 0%, #38bdf8 100%); color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 30px; font-weight: 800; font-size: 0.95rem; box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4); transition: transform 0.2s;">
                    <i class="bi bi-house-door-fill"></i> {{ __('Rudi ChapConnect Directory') }}
                </a>
            </div>

        </div>
    </div>
</main>

<style>
    @keyframes pulseDot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(1.2); }
    }
    @keyframes rotateGear {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endsection
