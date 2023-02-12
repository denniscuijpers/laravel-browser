<?php

declare(strict_types=1);

namespace DennisCuijpers\Browser;

use DateTimeZone;

class Browser
{
    public const HEADER_DEVICE_ID     = 'X-Device-Id';
    public const HEADER_REQUEST_ID    = 'X-Request-Id';
    public const HEADER_CF_IP_ADDRESS = 'Cf-Connecting-Ip';
    public const HEADER_CF_COUNTRY    = 'Cf-Ipcountry';
    public const COOKIE_DEVICE_ID     = 'device_id';
    public const COOKIE_LOCALE        = 'locale';
    public const COOKIE_COUNTRY       = 'country';
    public const COOKIE_TIMEZONE      = 'timezone';
    public const COOKIE_TTL           = 31536000;

    private const DEFAULT_DEVICE_ID  = '00000000-0000-0000-0000-000000000000';
    private const DEFAULT_REQUEST_ID = '00000000-0000-0000-0000-000000000000';
    private const DEFAULT_IP_ADDRESS = '0.0.0.0';
    private const DEFAULT_USER_AGENT = 'None';
    private const DEFAULT_COUNTRY    = 'nl';
    private const DEFAULT_LOCALE     = 'en';
    private const DEFAULT_TIMEZONE   = 'UTC';

    private static bool $cloudflare;
    private static array $locales;
    private static array $countries;
    private static array $timezones;
    private static string $deviceId;
    private static string $requestId;
    private static string $ipAddress;
    private static string $userAgent;
    private static string $country;
    private static string $locale;
    private static string $timezone;

    public static function cloudflare(): bool
    {
        return self::$cloudflare;
    }

    public static function locales(): array
    {
        return self::$locales;
    }

    public static function countries(): array
    {
        return self::$countries;
    }

    public static function timezones(): array
    {
        return self::$timezones;
    }

    public static function init(): void
    {
        self::$cloudflare = config('browser.cloudflare');
        self::$countries  = config('browser.countries');
        self::$locales    = config('browser.locales');
        self::$timezones  = DateTimeZone::listIdentifiers();
        self::$deviceId   = self::DEFAULT_DEVICE_ID;
        self::$requestId  = self::DEFAULT_REQUEST_ID;
        self::$ipAddress  = self::DEFAULT_IP_ADDRESS;
        self::$userAgent  = self::DEFAULT_USER_AGENT;
        self::$country    = self::DEFAULT_COUNTRY;
        self::$locale     = self::DEFAULT_LOCALE;
        self::$timezone   = self::DEFAULT_TIMEZONE;
    }

    public static function setDeviceId(string $deviceId): void
    {
        if (!empty($deviceId)) {
            self::$deviceId = $deviceId;
        }
    }

    public static function setRequestId(string $requestId): void
    {
        if (!empty($requestId)) {
            self::$requestId = $requestId;
        }
    }

    public static function setIpAddress(string $ipAddress): void
    {
        if (filter_var($ipAddress, FILTER_VALIDATE_IP) !== false) {
            self::$ipAddress = $ipAddress;
        }
    }

    public static function setUserAgent(string $userAgent): void
    {
        if (!empty($userAgent)) {
            self::$userAgent = $userAgent;
        }
    }

    public static function setLocale(string $locale): void
    {
        $locale = str_replace('-', '_', $locale);

        if (($pos = strpos($locale, '_')) !== false) {
            $locale = sprintf(
                '%s_%s',
                strtolower(substr($locale, 0, $pos)),
                strtoupper(substr($locale, $pos + 1))
            );
        } else {
            $locale = strtolower($locale);
        }

        if (in_array($locale, self::locales(), true)) {
            self::$locale = $locale;

            app('translator')->setLocale($locale);
        }
    }

    public static function setCountry(string $country): void
    {
        $country = strtolower($country);

        if (in_array($country, self::countries(), true)) {
            self::$country = $country;
        }
    }

    public static function setTimezone(string $timezone): void
    {
        if (in_array($timezone, self::timezones(), true)) {
            self::$timezone = $timezone;
        }
    }

    public static function deviceId(): string
    {
        return self::$deviceId;
    }

    public static function requestId(): string
    {
        return self::$requestId;
    }

    public static function ipAddress(): string
    {
        return self::$ipAddress;
    }

    public static function userAgent(): string
    {
        return self::$userAgent;
    }

    public static function locale(): string
    {
        return self::$locale;
    }

    public static function country(): string
    {
        return self::$country;
    }

    public static function timezone(): string
    {
        return self::$timezone;
    }
}
