<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PromotionPopupTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin@galeria.com',
            'role' => 'admin',
        ]);

        $this->customer = User::factory()->create([
            'role' => 'customer',
        ]);
    }

    public function test_guest_cannot_access_promotion_popup_settings(): void
    {
        $response = $this->get(route('admin.popup.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_customer_cannot_access_promotion_popup_settings(): void
    {
        $response = $this->actingAs($this->customer)->get(route('admin.popup.index'));
        $response->assertForbidden();
    }

    public function test_admin_can_view_promotion_popup_settings(): void
    {
        $response = $this->actingAs($this->admin)->get(route('admin.popup.index'));
        $response->assertOk();
        $response->assertSee('Pengaturan Popup Promosi');
        $response->assertSee('Pratinjau Tampilan Pengunjung');
    }

    public function test_admin_can_update_promotion_popup_settings(): void
    {
        $response = $this->actingAs($this->admin)->put(route('admin.popup.update'), [
            'popup_enabled' => '1',
            'popup_title' => 'Flash Sale Undangan Cetak 2026',
            'popup_link_type' => 'custom',
            'popup_custom_url' => 'https://galeria.com/promo-khusus',
            'popup_delay_seconds' => '2',
        ]);

        $response->assertRedirect(route('admin.popup.index'));
        $response->assertSessionHas('success');

        $this->assertEquals('1', Setting::get('popup_enabled'));
        $this->assertEquals('Flash Sale Undangan Cetak 2026', Setting::get('popup_title'));
        $this->assertEquals('custom', Setting::get('popup_link_type'));
        $this->assertEquals('https://galeria.com/promo-khusus', Setting::get('popup_custom_url'));
        $this->assertEquals('https://galeria.com/promo-khusus', popup_target_url());
        $this->assertEquals(2.0, (float) Setting::get('popup_delay_seconds'));
        $this->assertTrue(popup_is_enabled());
    }

    public function test_admin_can_upload_custom_popup_image(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('promo_flyer.jpg', 600, 800);

        $response = $this->actingAs($this->admin)->put(route('admin.popup.update'), [
            'popup_enabled' => '1',
            'popup_title' => 'Promo Spesial Custom Flyer',
            'popup_link_type' => 'whatsapp',
            'popup_whatsapp_message' => 'Halo Admin, saya mau diskon!',
            'popup_delay_seconds' => '1.5',
            'popup_image' => $file,
        ]);

        $response->assertRedirect(route('admin.popup.index'));

        $savedPath = Setting::get('popup_image');
        $this->assertNotNull($savedPath);
        Storage::disk('public')->assertExists($savedPath);
        $this->assertStringContainsString('wa.me', popup_target_url());
        $this->assertStringContainsString('Halo+Admin%2C+saya+mau+diskon%21', popup_target_url());
    }

    public function test_landing_page_renders_popup_when_enabled(): void
    {
        Setting::set('popup_enabled', '1');
        Setting::set('popup_title', 'Promo Akbar Galeria');

        $response = $this->get(route('home'));
        $response->assertOk();
        $response->assertSee('id="promoPopupOverlay"', false);
        $response->assertSee('Promo Akbar Galeria');
        $response->assertSee('Jangan tampilkan lagi hari ini');
    }

    public function test_landing_page_hides_popup_when_disabled_unless_preview(): void
    {
        Setting::set('popup_enabled', '0');

        $response = $this->get(route('home'));
        $response->assertOk();
        $response->assertDontSee('id="promoPopupOverlay"', false);

        // Preview query param forces popup render for admin verification
        $previewResponse = $this->get(route('home', ['preview_popup' => 1]));
        $previewResponse->assertOk();
        $previewResponse->assertSee('id="promoPopupOverlay"', false);
    }
}
