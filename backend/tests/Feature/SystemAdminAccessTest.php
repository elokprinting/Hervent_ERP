<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class SystemAdminAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login_from_system_admin(): void
    {
        $this->get(route('admin.system.index'))
            ->assertRedirect(route('login'));
    }

    public function test_non_admin_users_cannot_open_system_admin(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.system.index'))
            ->assertForbidden();
    }

    public function test_system_admin_must_confirm_password_before_opening_area(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_system_admin' => true])->save();

        $this->actingAs($admin)
            ->get(route('admin.system.index'))
            ->assertRedirect(route('password.confirm'));
    }

    public function test_system_admin_can_open_area_after_confirming_password(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_system_admin' => true])->save();

        $this->actingAs($admin)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->get(route('admin.system.index'))
            ->assertOk()
            ->assertSee('Admin Sistem');
    }

    public function test_system_admin_bypasses_other_gate_checks(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_system_admin' => true])->save();

        $this->assertTrue(Gate::forUser($admin)->allows('manage-system-settings'));
    }

    public function test_system_admin_privilege_cannot_be_mass_assigned(): void
    {
        $user = User::factory()->create();
        $user->fill(['is_system_admin' => true])->save();

        $this->assertFalse($user->fresh()->is_system_admin);
    }

    public function test_password_confirmation_rejects_an_incorrect_password(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_system_admin' => true])->save();

        $this->actingAs($admin)
            ->from(route('password.confirm'))
            ->post(route('password.confirm.store'), ['password' => 'wrong-password'])
            ->assertSessionHasErrors('password');
    }

    public function test_password_confirmation_accepts_the_current_password_and_redirects_to_admin(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_system_admin' => true])->save();

        $this->actingAs($admin)
            ->post(route('password.confirm.store'), ['password' => 'password'])
            ->assertRedirect(route('admin.system.index'))
            ->assertSessionHas('auth.password_confirmed_at');
    }

    public function test_login_rejects_invalid_credentials(): void
    {
        $user = User::factory()->create();

        $this->from(route('login'))
            ->post(route('login.store'), [
                'email' => $user->email,
                'password' => 'wrong-password',
            ])
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_regenerates_the_authenticated_session(): void
    {
        $user = User::factory()->create(['password' => 'correct-password']);

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'correct-password',
        ])
            ->assertRedirect('/')
            ->assertSessionHas('_token');

        $this->assertAuthenticatedAs($user);
    }
}
