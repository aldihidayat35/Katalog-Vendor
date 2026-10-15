<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageOptimizationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $this->category = Category::factory()->create([
            'name' => 'Rustic Minimalist',
            'slug' => 'rustic-minimalist',
        ]);
    }

    public function test_product_upload_accepts_large_image_up_to_10mb_and_converts_to_webp(): void
    {
        Storage::fake('public');

        // Create a large image (e.g. 2400x1600, ~5MB)
        $largeImage = UploadedFile::fake()->image('wedding-invitation-hq.jpg', 2400, 1600)->size(5000);

        $payload = [
            'category_id' => $this->category->id,
            'name' => 'Royal Sapphire Suite',
            'slug' => 'royal-sapphire-suite',
            'type' => 'printed',
            'description' => 'Paket undangan dengan foil emas dan amplop vellum.',
            'specifications' => 'Kertas Jasmine 260 gsm foil emas',
            'price' => 15000,
            'min_order' => 100,
            'stock_status' => 'ready',
            'images' => [$largeImage],
            'primary_image_index' => 0,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), $payload);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        $product = Product::where('slug', 'royal-sapphire-suite')->first();
        $this->assertNotNull($product);
        $this->assertCount(1, $product->images);

        $savedImagePath = $product->images->first()->image_path;

        // Must be saved in .webp format
        $this->assertStringEndsWith('.webp', $savedImagePath);
        Storage::disk('public')->assertExists($savedImagePath);

        // Verify image was resized proportionally (max width 1200px)
        $contents = Storage::disk('public')->get($savedImagePath);
        $gdImage = imagecreatefromstring($contents);
        $this->assertNotFalse($gdImage);
        $this->assertEquals(1200, imagesx($gdImage));
        $this->assertEquals(800, imagesy($gdImage)); // 1600 * (1200 / 2400)
        imagedestroy($gdImage);
    }

    public function test_product_upload_rejects_files_exceeding_10mb(): void
    {
        Storage::fake('public');

        // File size exceeds 10MB (11MB = 11264 KB)
        $oversizedImage = UploadedFile::fake()->image('massive-raw-photo.jpg', 1000, 1000)->size(11264);

        $payload = [
            'category_id' => $this->category->id,
            'name' => 'Oversized Product',
            'slug' => 'oversized-product',
            'type' => 'printed',
            'description' => 'Testing oversized upload.',
            'specifications' => 'Kertas Jasmine 260 gsm',
            'price' => 10000,
            'min_order' => 50,
            'stock_status' => 'ready',
            'images' => [$oversizedImage],
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), $payload);

        $response->assertSessionHasErrors('images.0');
        $this->assertEquals(
            'Ukuran gambar maksimal 10 MB per file.',
            session('errors')->first('images.0')
        );
    }

    public function test_banner_upload_resizes_to_1600px_and_converts_to_webp(): void
    {
        Storage::fake('public');

        $bannerImage = UploadedFile::fake()->image('wide-banner.jpg', 3200, 1200)->size(4000);

        $payload = [
            'title' => 'Promo Diskon Pernikahan',
            'subtitle' => 'Dapatkan cashback untuk order hari ini',
            'image' => $bannerImage,
            'sort_order' => 1,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.banners.store'), $payload);

        $response->assertRedirect(route('admin.banners.index'));
        $this->assertDatabaseHas('banners', [
            'title' => 'Promo Diskon Pernikahan',
        ]);

        $savedPath = \App\Models\Banner::first()->image_path;
        $this->assertStringEndsWith('.webp', $savedPath);
        Storage::disk('public')->assertExists($savedPath);

        $contents = Storage::disk('public')->get($savedPath);
        $gdImage = imagecreatefromstring($contents);
        $this->assertEquals(1600, imagesx($gdImage));
        $this->assertEquals(600, imagesy($gdImage)); // 1200 * (1600 / 3200)
        imagedestroy($gdImage);
    }

    public function test_category_upload_resizes_to_1000px_and_converts_to_webp(): void
    {
        Storage::fake('public');

        $catImage = UploadedFile::fake()->image('square-category.png', 2000, 2000)->size(3000);

        $payload = [
            'name' => 'Modern Minimalist Luxury',
            'slug' => 'modern-minimalist-luxury',
            'image' => $catImage,
            'sort_order' => 2,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.categories.store'), $payload);

        $response->assertRedirect(route('admin.categories.index'));

        $category = Category::where('slug', 'modern-minimalist-luxury')->first();
        $this->assertStringEndsWith('.webp', $category->image_path);
        Storage::disk('public')->assertExists($category->image_path);

        $contents = Storage::disk('public')->get($category->image_path);
        $gdImage = imagecreatefromstring($contents);
        $this->assertEquals(1000, imagesx($gdImage));
        $this->assertEquals(1000, imagesy($gdImage));
        imagedestroy($gdImage);
    }

    public function test_image_service_does_not_upscale_smaller_images(): void
    {
        Storage::fake('public');

        $smallImage = UploadedFile::fake()->image('small-thumbnail.jpg', 400, 300)->size(200);

        $storedPath = ImageService::storeAsWebp($smallImage, 'products', 1200);

        $this->assertStringEndsWith('.webp', $storedPath);
        Storage::disk('public')->assertExists($storedPath);

        $contents = Storage::disk('public')->get($storedPath);
        $gdImage = imagecreatefromstring($contents);
        $this->assertEquals(400, imagesx($gdImage));
        $this->assertEquals(300, imagesy($gdImage));
        imagedestroy($gdImage);
    }
}
