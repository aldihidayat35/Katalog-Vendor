<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Unauthenticated guest redirected to login when accessing admin dashboard.
     */
    public function test_guest_is_redirected_to_login_from_admin_route(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    /**
     * Regular member accessing admin dashboard receives 403 Forbidden.
     */
    public function test_regular_member_receives_403_on_admin_route(): void
    {
        $member = User::factory()->create([
            'role' => 'member',
        ]);

        $response = $this->actingAs($member)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    /**
     * Admin user can successfully access admin dashboard.
     */
    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Admin Panel');
    }
}
