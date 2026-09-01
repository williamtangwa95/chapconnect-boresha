<?php

namespace App\Helpers;

class PhoneHelper
{
    /**
     * Clean phone number by removing spaces, dashes, parentheses, dots.
     */
    public static function clean(?string $phone): string
    {
        if (!$phone) {
            return '';
        }
        return preg_replace('/[^\d+]/', '', trim($phone));
    }

    /**
     * Check if the given string is a valid Tanzanian mobile phone number.
     * Allowed formats:
     * - 06XXXXXXXX or 07XXXXXXXX (10 digits starting with 06 or 07)
     * - +2556XXXXXXXX or +2557XXXXXXXX (13 chars starting with +255 followed by 6 or 7 and 8 digits)
     * - 2556XXXXXXXX or 2557XXXXXXXX (12 digits starting with 255 followed by 6 or 7 and 8 digits)
     */
    public static function isValidTanzanianPhone(?string $phone): bool
    {
        $clean = self::clean($phone);
        if (empty($clean)) {
            return false;
        }

        // Matches +255[67]XXXXXXXX, 255[67]XXXXXXXX, or 0[67]XXXXXXXX
        return (bool) preg_match('/^(?:\+255|255|0)([67]\d{8})$/', $clean);
    }

    /**
     * Extract the core 9-digit subscriber number (e.g., '678429492' or '712345678')
     */
    public static function extractSubscriberNumber(?string $phone): ?string
    {
        $clean = self::clean($phone);
        if (preg_match('/^(?:\+255|255|0)([67]\d{8})$/', $clean, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Normalize phone number to standard local 10-digit format (06XXXXXXXX / 07XXXXXXXX).
     */
    public static function normalizeToLocal(?string $phone): ?string
    {
        $subscriber = self::extractSubscriberNumber($phone);
        if ($subscriber) {
            return '0' . $subscriber;
        }
        return $phone ? trim($phone) : null;
    }

    /**
     * Normalize phone number to international format with plus (+2556XXXXXXXX / +2557XXXXXXXX).
     */
    public static function normalizeToInternational(?string $phone): ?string
    {
        $subscriber = self::extractSubscriberNumber($phone);
        if ($subscriber) {
            return '+255' . $subscriber;
        }
        return $phone ? trim($phone) : null;
    }

    /**
     * Get all possible representation formats for a phone number.
     * Useful for database queries (WHERE IN) during login, password recovery, and uniqueness checks.
     *
     * Example for '0678429492' / '+255678429492' / '255678429492':
     * Returns:
     * - '0678429492'
     * - '+255678429492'
     * - '255678429492'
     * - '+255 678 429 492'
     * - '+255 678429492'
     * - '0678 429 492'
     * - raw input
     */
    public static function getPossibleFormats(?string $phone): array
    {
        if (!$phone) {
            return [];
        }

        $raw = trim($phone);
        $clean = self::clean($phone);
        $subscriber = self::extractSubscriberNumber($phone);

        $formats = [$raw, $clean];

        if ($subscriber) {
            $local = '0' . $subscriber;
            $intlPlus = '+255' . $subscriber;
            $intlNoPlus = '255' . $subscriber;

            $p1 = substr($subscriber, 0, 3);
            $p2 = substr($subscriber, 3, 3);
            $p3 = substr($subscriber, 6, 3);

            $spacedIntl = "+255 {$p1} {$p2} {$p3}";
            $spacedLocal = "0{$p1} {$p2} {$p3}";
            $spacedIntlSimple = "+255 {$subscriber}";

            $formats = array_merge($formats, [
                $local,
                $intlPlus,
                $intlNoPlus,
                $spacedIntl,
                $spacedLocal,
                $spacedIntlSimple,
            ]);
        }

        return array_values(array_unique(array_filter($formats)));
    }
}
