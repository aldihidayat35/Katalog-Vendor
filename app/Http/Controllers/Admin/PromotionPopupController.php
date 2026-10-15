<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PromotionPopupRequest;
use App\Models\Setting;
use App\Services\ImageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PromotionPopupController extends Controller
{
    /**
     * Display the promotional popup management view.
     */
    public function index(): View
    {
        $popup = [
            'enabled' => (bool) setting('popup_enabled', '1'),
            'title' => setting('popup_title', 'Promo Spesial Musim Undangan'),
            'image' => setting('popup_image'),
            'image_url' => popup_image_url(),
            'link_type' => setting('popup_link_type', 'catalog'),
            'custom_url' => setting('popup_custom_url', ''),
            'whatsapp_message' => setting('popup_whatsapp_message', 'Halo Admin Galeria Invitation, saya melihat promo di website dan ingin konsultasi promo ini.'),
            'delay_seconds' => (float) setting('popup_delay_seconds', '1.5'),
            'target_url' => popup_target_url(),
        ];

        return view('admin.popup.index', compact('popup'));
    }

    /**
     * Update the promotional popup settings.
     */
    public function update(PromotionPopupRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Setting::set('popup_enabled', $request->boolean('popup_enabled') ? '1' : '0');
        Setting::set('popup_title', $data['popup_title']);
        Setting::set('popup_link_type', $data['popup_link_type']);
        Setting::set('popup_custom_url', $data['popup_custom_url'] ?? null);
        Setting::set('popup_whatsapp_message', $data['popup_whatsapp_message'] ?? null);
        Setting::set('popup_delay_seconds', (string) $data['popup_delay_seconds']);

        // Handle Image Upload / Removal
        if ($request->hasFile('popup_image')) {
            $oldImage = setting('popup_image');
            if ($oldImage && ! str_starts_with($oldImage, 'images/') && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }

            $imagePath = ImageService::storeAsWebp($request->file('popup_image'), 'settings', 1200);
            Setting::set('popup_image', $imagePath);
        } elseif ($request->boolean('remove_image')) {
            $oldImage = setting('popup_image');
            if ($oldImage && ! str_starts_with($oldImage, 'images/') && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }

            Setting::set('popup_image', null);
        }

        return redirect()->route('admin.popup.index')
            ->with('success', 'Pengaturan popup promosi landing page berhasil diperbarui.');
    }
}
