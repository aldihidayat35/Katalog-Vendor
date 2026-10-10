<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Display the application settings form.
     */
    public function index(): View
    {
        $shippingMethodsJson = setting('shipping_methods', '[]');
        $shippingMethods = is_string($shippingMethodsJson) ? json_decode($shippingMethodsJson, true) : (array) $shippingMethodsJson;
        if (! is_array($shippingMethods) || empty($shippingMethods)) {
            $shippingMethods = [
                ['id' => 'jne', 'name' => 'JNE Reguler', 'desc' => 'Ekspedisi reguler ke seluruh Indonesia'],
                ['id' => 'jnt', 'name' => 'J&T Express', 'desc' => 'Pengiriman cepat dengan nomor resi akurat'],
                ['id' => 'instant', 'name' => 'Gojek / Grab (Instan)', 'desc' => 'Khusus Jabodetabek setelah pesanan cetak selesai'],
                ['id' => 'pickup', 'name' => 'Ambil di Tempat (Self-Pickup)', 'desc' => 'Ambil langsung di workshop Galeria'],
            ];
        }

        $settings = [
            'whatsapp_number' => setting('whatsapp_number', '6281234567890'),
            'site_name' => setting('site_name', 'Katalog Undangan'),
            'site_tagline' => setting('site_tagline', 'Spesialis Undangan Pernikahan Cetak & Digital Eksklusif'),
            'contact_email' => setting('contact_email', 'halo@katalogundangan.com'),
            'instagram_url' => setting('instagram_url', 'https://instagram.com/galeria.invitation'),
            'consultation_message' => setting('consultation_message', 'Halo Admin Galeria Invitation, saya tertarik untuk konsultasi desain undangan pernikahan saya. Mohon informasi katalog dan ketersediaan jadwal ya.'),
            'shipping_methods' => $shippingMethods,
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update application settings.
     */
    public function update(SettingRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Normalize WhatsApp phone number into 62...
        $data['whatsapp_number'] = clean_phone_number($data['whatsapp_number']);

        // Process shipping methods JSON array
        $methods = $request->input('shipping_methods', []);
        $cleanMethods = [];
        if (is_array($methods)) {
            foreach ($methods as $m) {
                if (! empty($m['name'])) {
                    $cleanMethods[] = [
                        'id' => ! empty($m['id']) ? \Illuminate\Support\Str::slug($m['id']) : \Illuminate\Support\Str::slug($m['name']),
                        'name' => trim($m['name']),
                        'desc' => trim($m['desc'] ?? ''),
                    ];
                }
            }
        }
        $data['shipping_methods'] = json_encode($cleanMethods, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        // Save each setting and purge its cache
        foreach ($data as $key => $value) {
            Setting::set($key, $value);
            Cache::forget("setting_{$key}");
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan situs berhasil diperbarui dan diterapkan.');
    }
}
