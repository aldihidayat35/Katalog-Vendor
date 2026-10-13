<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_redirect_to_google_is_blocked_when_feature_disabled(): void
    {
        config(['services.google.enabled' => false]);

        $response = $this->get(route('auth.google.redirect'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
    }

    public function test_google_login_button_is_hidden_when_feature_disabled(): void
    {
        config(['services.google.enabled' => false]);

        $this->get(route('login'))
            ->assertOk()
            ->assertDontSee('Lanjutkan dengan Google');

        $this->get(route('register'))
            ->assertOk()
            ->assertDontSee('Lanjutkan dengan Google');
    }

    public function test_google_login_button_is_visible_when_feature_enabled(): void
    {
        config(['services.google.enabled' => true]);

        $this->get(route('login'))
            ->assertOk()
            ->assertSee('Lanjutkan dengan Google');

        $this->get(route('register'))
            ->assertOk()
            ->assertSee('Lanjutkan dengan Google');
    }

    public function test_redirect_to_google_fails_cleanly_when_credentials_missing(): void
    {
        config(['services.google.enabled' => true]);
        config(['services.google.client_id' => null]);
        config(['services.google.client_secret' => null]);

        $response = $this->get(route('auth.google.redirect'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
    }

    public function test_google_callback_creates_new_user_with_username_and_avatar(): void
    {
        config(['services.google.enabled' => true]);
        config(['services.google.client_id' => 'dummy-client-id']);
        config(['services.google.client_secret' => 'dummy-client-secret']);

        $abstractUser = Mockery::mock(SocialiteUser::class);
        $abstractUser->shouldReceive('getId')->andReturn('google-id-12345');
        $abstractUser->shouldReceive('getEmail')->andReturn('johndoe@gmail.com');
        $abstractUser->shouldReceive('getName')->andReturn('John Doe');
        $abstractUser->shouldReceive('getAvatar')->andReturn('https://lh3.googleusercontent.com/johndoe.jpg');

        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('catalog.index'));
        $response->assertSessionHas('success');

        $this->assertAuthenticated();

        $user = User::where('email', 'johndoe@gmail.com')->first();
        $this->assertNotNull($user);
        $this->assertSame('John Doe', $user->name);
        $this->assertSame('johndoe', $user->username);
        $this->assertSame('https://lh3.googleusercontent.com/johndoe.jpg', $user->avatar_url);
        $this->assertSame('google-id-12345', $user->google_id);
    }

    public function test_google_callback_auto_links_existing_user_by_email(): void
    {
        config(['services.google.enabled' => true]);
        config(['services.google.client_id' => 'dummy-client-id']);
        config(['services.google.client_secret' => 'dummy-client-secret']);

        $existing = User::factory()->create([
            'email' => 'jane@example.com',
            'name' => 'Jane Example',
            'username' => 'jane_ex',
            'google_id' => null,
            'avatar_url' => null,
        ]);

        $abstractUser = Mockery::mock(SocialiteUser::class);
        $abstractUser->shouldReceive('getId')->andReturn('google-id-999');
        $abstractUser->shouldReceive('getEmail')->andReturn('jane@example.com');
        $abstractUser->shouldReceive('getName')->andReturn('Jane Example');
        $abstractUser->shouldReceive('getAvatar')->andReturn('https://lh3.googleusercontent.com/jane.jpg');

        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('catalog.index'));
        $this->assertAuthenticatedAs($existing);

        $existing->refresh();
        $this->assertSame('google-id-999', $existing->google_id);
        $this->assertSame('https://lh3.googleusercontent.com/jane.jpg', $existing->avatar_url);
        $this->assertSame('jane_ex', $existing->username);
    }

    public function test_google_callback_generates_unique_username_when_prefix_already_taken(): void
    {
        config(['services.google.enabled' => true]);
        config(['services.google.client_id' => 'dummy-client-id']);
        config(['services.google.client_secret' => 'dummy-client-secret']);

        User::factory()->create([
            'username' => 'alex',
            'email' => 'alex_first@example.com',
        ]);

        $abstractUser = Mockery::mock(SocialiteUser::class);
        $abstractUser->shouldReceive('getId')->andReturn('google-id-777');
        $abstractUser->shouldReceive('getEmail')->andReturn('alex@gmail.com');
        $abstractUser->shouldReceive('getName')->andReturn('Alex New');
        $abstractUser->shouldReceive('getAvatar')->andReturn('https://lh3.googleusercontent.com/alex.jpg');

        $provider = Mockery::mock('Laravel\Socialite\Two\GoogleProvider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('catalog.index'));

        $newUser = User::where('email', 'alex@gmail.com')->first();
        $this->assertNotNull($newUser);
        $this->assertNotSame('alex', $newUser->username);
        $this->assertStringStartsWith('alex_', $newUser->username);
    }
}
