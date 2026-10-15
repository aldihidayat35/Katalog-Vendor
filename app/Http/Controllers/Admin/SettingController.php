<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingRequest;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
            'site_logo' => setting('site_logo'),
            'site_favicon' => setting('site_favicon'),
            'whatsapp_number' => setting('whatsapp_number', '6281234567890'),
            'site_name' => setting('site_name', 'Katalog Undangan'),
            'site_tagline' => setting('site_tagline', 'Spesialis Undangan Pernikahan Cetak & Digital Eksklusif'),
            'contact_email' => setting('contact_email', 'halo@katalogundangan.com'),
            'instagram_url' => setting('instagram_url', 'https://instagram.com/galeria.invitation'),
            'consultation_message' => setting('consultation_message', 'Halo Admin Galeria Invitation, saya tertarik untuk konsultasi desain undangan pernikahan saya. Mohon informasi katalog dan ketersediaan jadwal ya.'),
            'shipping_methods' => $shippingMethods,
            'google_site_verification' => setting('google_site_verification', ''),
            'seo_meta_keywords' => setting('seo_meta_keywords', 'undangan pernikahan, vendor undangan pernikahan, cetak undangan mewah, undangan elegan, undangan pernikahan eksklusif, undangan online, cetak undangan pernikahan indonesia'),
            'seo_meta_description' => setting('seo_meta_description', 'Vendor percetakan dan spesialis undangan pernikahan mewah, elegan, dan eksklusif. Menyediakan undangan cetak fisik berkualitas premium serta undangan digital interaktif untuk pengiriman ke seluruh Indonesia.'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update application settings.
     */
    public function update(SettingRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Handle Site Logo Upload / Removal
        if ($request->hasFile('site_logo')) {
            $oldLogo = setting('site_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            $logoPath = $request->file('site_logo')->store('settings', 'public');
            Setting::set('site_logo', $logoPath);
        } elseif ($request->boolean('remove_logo')) {
            $oldLogo = setting('site_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            Setting::set('site_logo', null);
        }

        // Handle Site Favicon Upload / Removal
        if ($request->hasFile('site_favicon')) {
            $oldFavicon = setting('site_favicon');
            if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                Storage::disk('public')->delete($oldFavicon);
            }
            $faviconPath = $request->file('site_favicon')->store('settings', 'public');
            Setting::set('site_favicon', $faviconPath);
        } elseif ($request->boolean('remove_favicon')) {
            $oldFavicon = setting('site_favicon');
            if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                Storage::disk('public')->delete($oldFavicon);
            }
            Setting::set('site_favicon', null);
        }

        // Exclude file and action flags from general settings data loop
        unset($data['site_logo'], $data['site_favicon'], $data['remove_logo'], $data['remove_favicon']);

        // Normalize WhatsApp phone number into 62...
        $data['whatsapp_number'] = clean_phone_number($data['whatsapp_number']);

        // Process shipping methods JSON array
        $methods = $request->input('shipping_methods', []);
        $cleanMethods = [];
        if (is_array($methods)) {
            foreach ($methods as $m) {
                if (! empty($m['name'])) {
                    $cleanMethods[] = [
                        'id' => ! empty($m['id']) ? Str::slug($m['id']) : Str::slug($m['name']),
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
