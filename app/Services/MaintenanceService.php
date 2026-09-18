<?php

namespace App\Services;

use App\Models\SystemSetting;
use Carbon\Carbon;

class MaintenanceService
{
    /**
     * Check if master maintenance toggle is enabled.
     */
    public static function isEnabled(): bool
    {
        return SystemSetting::get('maintenance_enabled', '0') === '1';
    }

    /**
     * Check if login feature flag is enabled for restriction.
     */
    public static function isLoginRestrictedFlag(): bool
    {
        return SystemSetting::get('maintenance_restrict_login', '0') === '1';
    }

    /**
     * Check if registration feature flag is enabled for restriction.
     */
    public static function isRegisterRestrictedFlag(): bool
    {
        return SystemSetting::get('maintenance_restrict_register', '0') === '1';
    }

    /**
     * Check if connect feature flag is enabled for restriction.
     */
    public static function isConnectRestrictedFlag(): bool
    {
        return SystemSetting::get('maintenance_restrict_connect', '0') === '1';
    }

    /**
     * Get start date/time Carbon instance or null.
     */
    public static function getStartAt(): ?Carbon
    {
        $val = trim((string) SystemSetting::get('maintenance_start_at', ''));
        if ($val === '') {
            return null;
        }
        try {
            return Carbon::parse($val, config('app.timezone'));
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get end date/time Carbon instance or null.
     */
    public static function getEndAt(): ?Carbon
    {
        $val = trim((string) SystemSetting::get('maintenance_end_at', ''));
        if ($val === '') {
            return null;
        }
        try {
            return Carbon::parse($val, config('app.timezone'));
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get custom maintenance message or fallback default based on current or requested locale.
     */
    public static function getMessage(?string $locale = null): string
    {
        $currentLocale = strtolower($locale ?: app()->getLocale());

        $defaultSw = 'Kwa sasa huduma hii imefungwa kwa muda kutokana na maboresho ya mfumo. Tafadhali jaribu tena baada ya muda wa maboresho kukamilika.';
        $defaultEn = 'This service is temporarily restricted due to system maintenance. Please try again after maintenance completes.';

        if ($currentLocale === 'en') {
            $enMsg = trim((string) SystemSetting::get('maintenance_message_en', ''));
            if (!empty($enMsg)) {
                return $enMsg;
            }
            return $defaultEn;
        }

        $swMsg = trim((string) SystemSetting::get('maintenance_message_sw', ''));
        if (empty($swMsg)) {
            $swMsg = trim((string) SystemSetting::get('maintenance_message', ''));
        }

        return !empty($swMsg) ? $swMsg : $defaultSw;
    }

    /**
     * Determine if current server time is within configured start_at and end_at schedule.
     */
    public static function isWithinSchedule(): bool
    {
        $start = self::getStartAt();
        $end = self::getEndAt();

        // If no schedule is configured, maintenance applies whenever master is enabled
        if (!$start && !$end) {
            return true;
        }

        $now = Carbon::now(config('app.timezone'));

        if ($start && $end) {
            return $now->greaterThanOrEqualTo($start) && $now->lessThanOrEqualTo($end);
        }

        if ($start && !$end) {
            return $now->greaterThanOrEqualTo($start);
        }

        if (!$start && $end) {
            return $now->lessThanOrEqualTo($end);
        }

        return false;
    }

    /**
     * Determine maintenance overall system status:
     * - 'DISABLED' (Master switch OFF)
     * - 'SCHEDULED' (Master switch ON, start time is in the future)
     * - 'ACTIVE' (Master switch ON, current time is within schedule)
     * - 'EXPIRED' (Master switch ON, end time has passed)
     */
    public static function getStatus(): string
    {
        if (!self::isEnabled()) {
            return 'DISABLED';
        }

        $start = self::getStartAt();
        $end = self::getEndAt();
        $now = Carbon::now(config('app.timezone'));

        if ($start && $now->lessThan($start)) {
            return 'SCHEDULED';
        }

        if ($end && $now->greaterThan($end)) {
            return 'EXPIRED';
        }

        return 'ACTIVE';
    }

    /**
     * Check if a specific feature is currently restricted.
     * Features: 'login', 'register', 'connect'
     */
    public static function isFeatureRestricted(string $feature): bool
    {
        if (!self::isEnabled()) {
            return false;
        }

        if (!self::isWithinSchedule()) {
            return false;
        }

        switch (strtolower($feature)) {
            case 'login':
                return self::isLoginRestrictedFlag();
            case 'register':
            case 'registration':
                return self::isRegisterRestrictedFlag();
            case 'connect':
            case 'ask_to_connect':
                return self::isConnectRestrictedFlag();
            default:
                return false;
        }
    }

    /**
     * Check if marquee banner on homepage is explicitly enabled or auto-enabled by maintenance/restrictions.
     */
    public static function isBannerVisible(): bool
    {
        $bannerSetting = SystemSetting::get('maintenance_banner_enabled', null);

        // If explicitly set, respect setting ('1' = show, '0' = hide)
        if ($bannerSetting !== null && $bannerSetting !== '') {
            return $bannerSetting === '1';
        }

        // Fallback: show if master maintenance or any restriction is active
        return self::isEnabled() || self::isLoginRestrictedFlag() || self::isRegisterRestrictedFlag() || self::isConnectRestrictedFlag();
    }

    /**
     * Get banner category type ('info', 'warning', 'danger', 'primary', 'success').
     */
    public static function getBannerCategory(): string
    {
        $category = trim(strtolower((string) SystemSetting::get('maintenance_banner_category', '')));

        $validCategories = ['info', 'warning', 'danger', 'primary', 'success'];
        if (in_array($category, $validCategories, true)) {
            return $category;
        }

        // Default: if restrictions or maintenance is active, default to 'danger', else 'info'
        if (self::isEnabled() || self::isLoginRestrictedFlag() || self::isRegisterRestrictedFlag() || self::isConnectRestrictedFlag()) {
            return 'danger';
        }

        return 'info';
    }

    /**
     * Get badge icon class based on category.
     */
    public static function getBannerIcon(?string $category = null): string
    {
        $cat = $category ?: self::getBannerCategory();
        switch ($cat) {
            case 'info':
                return 'bi bi-info-circle-fill';
            case 'warning':
                return 'bi bi-exclamation-triangle-fill';
            case 'primary':
                return 'bi bi-megaphone-fill';
            case 'success':
                return 'bi bi-check-circle-fill';
            case 'danger':
            default:
                return 'bi bi-exclamation-octagon-fill';
        }
    }

    /**
     * Get banner marquee emoji based on category.
     */
    public static function getBannerEmoji(?string $category = null): string
    {
        $cat = $category ?: self::getBannerCategory();
        switch ($cat) {
            case 'info':
                return 'ℹ️';
            case 'warning':
                return '⚠️';
            case 'primary':
                return '📢';
            case 'success':
                return '✅';
            case 'danger':
            default:
                return '🚨';
        }
    }

    /**
     * Get banner badge titles (desktop & mobile).
     */
    public static function getBannerTitle(?string $locale = null): array
    {
        $currentLocale = strtolower($locale ?: app()->getLocale());
        $cat = self::getBannerCategory();

        $customSw = trim((string) SystemSetting::get('maintenance_banner_title_sw', ''));
        $customEn = trim((string) SystemSetting::get('maintenance_banner_title_en', ''));

        if ($currentLocale === 'en' && !empty($customEn)) {
            return [
                'desktop' => $customEn,
                'mobile' => $customEn,
            ];
        }

        if ($currentLocale !== 'en' && !empty($customSw)) {
            return [
                'desktop' => $customSw,
                'mobile' => $customSw,
            ];
        }

        // Default badge labels per category
        $defaults = [
            'info' => [
                'sw' => ['desktop' => 'MAELEKEZO MUHIMU', 'mobile' => 'MAELEKEZO'],
                'en' => ['desktop' => 'INFORMATION NOTICE', 'mobile' => 'INFO'],
            ],
            'warning' => [
                'sw' => ['desktop' => 'TAHADHARI YA MFUMO', 'mobile' => 'ILANI'],
                'en' => ['desktop' => 'IMPORTANT WARNING', 'mobile' => 'WARNING'],
            ],
            'danger' => [
                'sw' => ['desktop' => 'ILANI YA MABORESHO', 'mobile' => 'MABORESHO'],
                'en' => ['desktop' => 'SYSTEM ALERT', 'mobile' => 'ALERT'],
            ],
            'primary' => [
                'sw' => ['desktop' => 'TANGAZO RASMI', 'mobile' => 'TANGAZO'],
                'en' => ['desktop' => 'GENERAL NOTICE', 'mobile' => 'NOTICE'],
            ],
            'success' => [
                'sw' => ['desktop' => 'SASISHO LA MFUMO', 'mobile' => 'SASISHO'],
                'en' => ['desktop' => 'SYSTEM UPDATE', 'mobile' => 'UPDATE'],
            ],
        ];

        $langKey = $currentLocale === 'en' ? 'en' : 'sw';
        return $defaults[$cat][$langKey] ?? $defaults['danger'][$langKey];
    }

    /**
     * Helper array of all current maintenance details.
     */
    public static function getDetails(): array
    {
        $start = self::getStartAt();
        $end = self::getEndAt();

        return [
            'enabled' => self::isEnabled(),
            'status' => self::getStatus(),
            'is_active' => self::getStatus() === 'ACTIVE',
            'restrict_login' => self::isLoginRestrictedFlag(),
            'restrict_register' => self::isRegisterRestrictedFlag(),
            'restrict_connect' => self::isConnectRestrictedFlag(),
            'start_at' => $start ? $start->format('Y-m-d\TH:i') : '',
            'end_at' => $end ? $end->format('Y-m-d\TH:i') : '',
            'start_at_formatted' => $start ? $start->format('d M Y, H:i') : 'N/A',
            'end_at_formatted' => $end ? $end->format('d M Y, H:i') : 'N/A',
            'message' => self::getMessage(),
            'message_sw' => SystemSetting::get('maintenance_message_sw', '') ?: SystemSetting::get('maintenance_message', ''),
            'message_en' => SystemSetting::get('maintenance_message_en', ''),
            'banner_enabled' => SystemSetting::get('maintenance_banner_enabled', '1') === '1',
            'banner_category' => self::getBannerCategory(),
            'banner_title_sw' => SystemSetting::get('maintenance_banner_title_sw', ''),
            'banner_title_en' => SystemSetting::get('maintenance_banner_title_en', ''),
        ];
    }
}
