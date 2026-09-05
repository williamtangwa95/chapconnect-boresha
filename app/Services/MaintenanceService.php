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
        ];
    }
}
