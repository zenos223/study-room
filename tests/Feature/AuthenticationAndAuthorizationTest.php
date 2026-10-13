<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationAndAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_displays_the_studyspace_directory_and_auth_links(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Available study rooms')
            ->assertSee(route('login'))
            ->assertSee(route('register'));
    }

    public function test_user_can_register_and_is_not_granted_admin_access(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Study User',
            'student_id' => 'ST-2026-001',
            'department' => 'Computer Science',
            'email' => 'study@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'agreement' => '1',
            'role' => User::ROLE_ADMIN,
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'study@example.com',
            'role' => User::ROLE_USER,
        ]);
    }

    public function test_registered_user_can_log_in_and_log_out(): void
    {
        $user = User::factory()->create();

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);

        $this->post(route('logout'))
            ->assertRedirect(route('home'));

        $this->assertGuest();
    }

    public function test_guest_is_redirected_to_login_from_admin_page(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_regular_user_is_forbidden_from_admin_page(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_admin_can_access_admin_page(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['role' => User::ROLE_ADMIN])->save();

        $this->actingAs($admin)
            ->get(route('admin.rooms.index'))
            ->assertOk()
            ->assertSee('Manage spaces');
    }

    public function test_regular_user_cannot_access_admin_room_management(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.rooms.index'))
            ->assertForbidden();
    }

    public function test_make_user_admin_command_promotes_an_existing_user(): void
    {
        $user = User::factory()->create();

        $this->artisan('user:make-admin', ['email' => $user->email])
            ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'role' => User::ROLE_ADMIN,
        ]);
    }
}
