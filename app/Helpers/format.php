<?php

use App\Models\Setting;

if (! function_exists('rupiah')) {
    /**
     * Format number to Rupiah currency string.
     * Example: 1250000 -> "Rp 1.250.000"
     */
    function rupiah(mixed $angka): string
    {
        $numeric = (float) ($angka ?? 0);

        return 'Rp '.number_format($numeric, 0, ',', '.');
    }
}

if (! function_exists('clean_phone_number')) {
    /**
     * Clean phone number into WhatsApp international format (62xxxxxxxx).
     */
    function clean_phone_number(?string $phone): string
    {
        if (empty($phone)) {
            return '';
        }

        // Remove non-digit characters
        $cleaned = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($cleaned, '0')) {
            $cleaned = '62'.substr($cleaned, 1);
        } elseif (str_starts_with($cleaned, '8')) {
            $cleaned = '62'.$cleaned;
        }

        return $cleaned;
    }
}

if (! function_exists('wa_link')) {
    /**
     * Generate WhatsApp direct link with message.
     */
    function wa_link(string $pesan, ?string $nomor = null): string
    {
        if (empty($nomor)) {
            $nomor = setting('whatsapp_number')
                ?? config('app.whatsapp_admin_number')
                ?? env('WHATSAPP_ADMIN_NUMBER', '6281234567890');
        }

        $cleanNomor = clean_phone_number($nomor);

        return 'https://wa.me/'.$cleanNomor.'?text='.urlencode($pesan);
    }
}

if (! function_exists('setting')) {
    /**
     * Retrieve cached application setting from settings table.
     */
    function setting(string $key, mixed $default = null): mixed
    {
        try {
            return Setting::get($key, $default);
        } catch (Throwable $e) {
            return $default;
        }
    }
}

if (! function_exists('site_logo_url')) {
    /**
     * Get site logo URL from settings or return fallback.
     */
    function site_logo_url(?string $default = null): ?string
    {
        $path = setting('site_logo');
        if (! empty($path)) {
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return $path;
            }

            return asset('storage/'.$path);
        }

        return $default;
    }
}

if (! function_exists('site_favicon_url')) {
    /**
     * Get site favicon URL from settings or return default.
     */
    function site_favicon_url(?string $default = null): string
    {
        $path = setting('site_favicon');
        if (! empty($path)) {
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return $path;
            }

            return asset('storage/'.$path);
        }

        return $default ?? asset('favicon.ico');
    }
}

if (! function_exists('popup_image_url')) {
    /**
     * Get promotional popup poster image URL.
     */
    function popup_image_url(?string $default = null): ?string
    {
        $path = setting('popup_image');
        if (! empty($path)) {
            if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                return $path;
            }
            if (str_starts_with($path, 'images/')) {
                return asset($path);
            }

            return asset('storage/'.$path);
        }

        return $default ?? asset('images/landing/promo-poster.svg');
    }
}

if (! function_exists('popup_target_url')) {
    /**
     * Get promotional popup target destination URL.
     */
    function popup_target_url(): string
    {
        $type = setting('popup_link_type', 'catalog');

        if ($type === 'whatsapp') {
            $msg = setting('popup_whatsapp_message', 'Halo Admin Galeria Invitation, saya tertarik dengan promo spesial di website!');

            return wa_link($msg);
        }

        if ($type === 'custom') {
            $custom = setting('popup_custom_url');

            return ! empty($custom) ? $custom : route('catalog.index');
        }

        return route('catalog.index');
    }
}

if (! function_exists('popup_is_enabled')) {
    /**
     * Determine if promotional popup is enabled.
     */
    function popup_is_enabled(): bool
    {
        return (bool) setting('popup_enabled', '1');
    }
}
