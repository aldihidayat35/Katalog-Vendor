<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/admin/users');
        $response->assertRedirect('/login');
    }

    public function test_regular_member_cannot_access_user_management(): void
    {
        $member = User::factory()->create(['role' => 'member']);

        $response = $this->actingAs($member)->get('/admin/users');
        $response->assertStatus(403);
    }

    public function test_admin_can_view_users_index_with_tabs_and_search(): void
    {
        $admin = User::factory()->admin()->create(['name' => 'Super Admin', 'email' => 'super@example.com']);
        $member1 = User::factory()->create(['name' => 'Ahmad Dani', 'email' => 'ahmad@example.com', 'role' => 'member']);
        $member2 = User::factory()->create(['name' => 'Citra Lestari', 'email' => 'citra@example.com', 'role' => 'member']);

        // 1. All users
        $response = $this->actingAs($admin)->get('/admin/users');
        $response->assertStatus(200)
            ->assertSee('Manajemen Pengguna')
            ->assertSee('Super Admin')
            ->assertSee('Ahmad Dani')
            ->assertSee('Citra Lestari');

        // 2. Filter admin only
        $response = $this->actingAs($admin)->get('/admin/users?role=admin');
        $response->assertStatus(200)
            ->assertSee('Super Admin')
            ->assertDontSee('Citra Lestari');

        // 3. Search query
        $response = $this->actingAs($admin)->get('/admin/users?q=Ahmad');
        $response->assertStatus(200)
            ->assertSee('Ahmad Dani')
            ->assertDontSee('Citra Lestari');
    }

    public function test_admin_can_create_new_member_user(): void
    {
        $admin = User::factory()->admin()->create();

        $userData = [
            'name' => 'Fajar Pratama',
            'email' => 'fajar@example.com',
            'username' => 'fajar_p',
            'password' => 'secret12345',
            'role' => 'member',
            'phone' => '081234567890',
            'instagram' => '@fajar',
            'address' => 'Jl. Merdeka No. 10, Pekanbaru',
            'is_active' => '1',
        ];

        $response = $this->actingAs($admin)->post('/admin/users', $userData);
        $response->assertRedirect('/admin/users');

        $this->assertDatabaseHas('users', [
            'email' => 'fajar@example.com',
            'username' => 'fajar_p',
            'role' => 'member',
            'is_active' => true,
        ]);

        $createdUser = User::where('email', 'fajar@example.com')->first();
        $this->assertTrue(Hash::check('secret12345', $createdUser->password));
    }

    public function test_admin_can_create_new_admin_user(): void
    {
        $admin = User::factory()->admin()->create();

        $userData = [
            'name' => 'Staff Admin 2',
            'email' => 'staff@example.com',
            'password' => 'adminpassword',
            'role' => 'admin',
            'is_active' => '1',
        ];

        $response = $this->actingAs($admin)->post('/admin/users', $userData);
        $response->assertRedirect('/admin/users');

        $this->assertDatabaseHas('users', [
            'email' => 'staff@example.com',
            'role' => 'admin',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_update_user_details_and_role(): void
    {
        $admin = User::factory()->admin()->create();
        $targetUser = User::factory()->create([
            'name' => 'Nama Lama',
            'role' => 'member',
            'is_active' => true,
        ]);

        $updateData = [
            'name' => 'Nama Baru Diperbarui',
            'email' => $targetUser->email,
            'role' => 'admin', // Promote to admin
            'phone' => '089988776655',
            'is_active' => '1',
        ];

        $response = $this->actingAs($admin)->put("/admin/users/{$targetUser->id}", $updateData);
        $response->assertRedirect('/admin/users');

        $this->assertDatabaseHas('users', [
            'id' => $targetUser->id,
            'name' => 'Nama Baru Diperbarui',
            'role' => 'admin',
            'phone' => '089988776655',
        ]);
    }

    public function test_admin_can_update_user_password_when_provided(): void
    {
        $admin = User::factory()->admin()->create();
        $targetUser = User::factory()->create([
            'password' => Hash::make('oldpassword'),
        ]);

        // 1. Update without password -> password remains untouched
        $this->actingAs($admin)->put("/admin/users/{$targetUser->id}", [
            'name' => $targetUser->name,
            'email' => $targetUser->email,
            'role' => $targetUser->role,
            'password' => '', // blank
            'is_active' => '1',
        ])->assertRedirect('/admin/users');

        $targetUser->refresh();
        $this->assertTrue(Hash::check('oldpassword', $targetUser->password));

        // 2. Update with new password -> password is reset
        $this->actingAs($admin)->put("/admin/users/{$targetUser->id}", [
            'name' => $targetUser->name,
            'email' => $targetUser->email,
            'role' => $targetUser->role,
            'password' => 'newpassword123',
            'is_active' => '1',
        ])->assertRedirect('/admin/users');

        $targetUser->refresh();
        $this->assertTrue(Hash::check('newpassword123', $targetUser->password));
    }

    public function test_admin_can_toggle_user_active_status(): void
    {
        $admin = User::factory()->admin()->create();
        $member = User::factory()->create(['is_active' => true]);

        // Toggle to suspended
        $response = $this->actingAs($admin)->patch("/admin/users/{$member->id}/toggle-status");
        $response->assertSessionHas('success');

        $member->refresh();
        $this->assertFalse($member->is_active);

        // Toggle back to active
        $response = $this->actingAs($admin)->patch("/admin/users/{$member->id}/toggle-status");
        $response->assertSessionHas('success');

        $member->refresh();
        $this->assertTrue($member->is_active);
    }

    public function test_suspended_user_cannot_login(): void
    {
        $suspendedUser = User::factory()->create([
            'email' => 'banned@example.com',
            'password' => Hash::make('password123'),
            'is_active' => false,
        ]);

        $response = $this->post('/login', [
            'email' => 'banned@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_admin_cannot_demote_or_suspend_themselves(): void
    {
        $admin = User::factory()->admin()->create(['is_active' => true]);

        // Trying to demote self
        $response = $this->actingAs($admin)->put("/admin/users/{$admin->id}", [
            'name' => $admin->name,
            'email' => $admin->email,
            'role' => 'member',
            'is_active' => '1',
        ]);
        $response->assertSessionHas('error');

        $admin->refresh();
        $this->assertEquals('admin', $admin->role);

        // Trying to suspend self via toggle
        $response = $this->actingAs($admin)->patch("/admin/users/{$admin->id}/toggle-status");
        $response->assertSessionHas('error');

        $admin->refresh();
        $this->assertTrue($admin->is_active);
    }

    public function test_admin_cannot_delete_themselves(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->delete("/admin/users/{$admin->id}");
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_cannot_delete_user_with_existing_orders(): void
    {
        $admin = User::factory()->admin()->create();
        $member = User::factory()->create();

        Order::factory()->create([
            'user_id' => $member->id,
            'customer_email' => $member->email,
        ]);

        $response = $this->actingAs($admin)->delete("/admin/users/{$member->id}");
        $response->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $member->id]);
    }

    public function test_admin_can_delete_user_without_orders(): void
    {
        $admin = User::factory()->admin()->create();
        $member = User::factory()->create();

        $response = $this->actingAs($admin)->delete("/admin/users/{$member->id}");
        $response->assertRedirect('/admin/users');
        $response->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $member->id]);
    }

    public function test_admin_can_export_users_csv(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->create(['name' => 'Member Export Test']);

        $response = $this->actingAs($admin)->get('/admin/users/export');

        $response->assertStatus(200);
        $this->assertStringContainsString('text/csv', $response->headers->get('content-type'));
    }

    public function test_legacy_member_routes_work_seamlessly(): void
    {
        $admin = User::factory()->admin()->create();
        $member = User::factory()->create(['name' => 'Member Legacy Test']);

        $this->actingAs($admin)->get('/admin/members')
            ->assertStatus(200)
            ->assertSee('Member Legacy Test');

        $this->actingAs($admin)->get("/admin/members/{$member->id}")
            ->assertStatus(200)
            ->assertSee('Member Legacy Test');
    }
}
