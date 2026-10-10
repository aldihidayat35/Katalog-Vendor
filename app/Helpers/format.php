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
        return 'Rp ' . number_format($numeric, 0, ',', '.');
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
            $cleaned = '62' . substr($cleaned, 1);
        } elseif (str_starts_with($cleaned, '8')) {
            $cleaned = '62' . $cleaned;
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

        return 'https://wa.me/' . $cleanNomor . '?text=' . urlencode($pesan);
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
        } catch (\Throwable $e) {
            return $default;
        }
    }
}
