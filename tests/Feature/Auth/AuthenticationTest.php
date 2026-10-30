<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_admin_can_authenticate_with_email(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->post('/login', [
            'identifier' => $user->email,
            'password'   => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(RouteServiceProvider::ADMIN_HOME);
    }

    public function test_admin_can_authenticate_with_username(): void
    {
        $user = User::factory()->create(['username' => 'shop-admin']);
        $user->assignRole('admin');

        $response = $this->post('/login', [
            'identifier' => 'shop-admin',
            'password'   => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(RouteServiceProvider::ADMIN_HOME);
    }

    public function test_user_without_admin_permission_is_redirected_home(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'identifier' => $user->email,
            'password'   => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'identifier' => $user->email,
            'password'   => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('identifier');
    }

    public function test_login_requires_identifier(): void
    {
        $response = $this->post('/login', ['password' => 'password']);

        $this->assertGuest();
        $response->assertSessionHasErrors('identifier');
    }
}
