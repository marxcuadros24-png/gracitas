<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use DatabaseTransactions;

    protected function createUnverifiedUser(): User
    {
        return User::factory()->create([
            'email_verified_at' => null,
            'rol' => 'cliente',
            'activo' => 1,
        ]);
    }

    protected function createVerifiedUser(): User
    {
        return User::factory()->create([
            'email_verified_at' => now(),
            'rol' => 'cliente',
            'activo' => 1,
        ]);
    }

    public function test_guest_can_access_home_page(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
    }

    public function test_unverified_user_cannot_access_home_and_is_redirected(): void
    {
        $user = $this->createUnverifiedUser();

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertRedirect(route('verification.notice'));
    }

    public function test_unverified_user_cannot_access_reservas_and_is_redirected(): void
    {
        $user = $this->createUnverifiedUser();

        $response = $this->actingAs($user)->get(route('cliente.reservas'));

        $response->assertRedirect(route('verification.notice'));
    }

    public function test_unverified_user_cannot_access_profile_and_is_redirected(): void
    {
        $user = $this->createUnverifiedUser();

        $response = $this->actingAs($user)->get(route('profile'));

        $response->assertRedirect(route('verification.notice'));
    }

    public function test_unverified_user_can_view_verification_notice(): void
    {
        $user = $this->createUnverifiedUser();

        $response = $this->actingAs($user)->get(route('verification.notice'));

        $response->assertStatus(200);
        $response->assertSee($user->email);
        $response->assertSee('Verificación de Correo Requerida');
    }

    public function test_unverified_user_can_update_email_address(): void
    {
        Notification::fake();

        $user = $this->createUnverifiedUser();
        $newEmail = 'nuevo_correo_'.uniqid().'@ejemplo.com';

        $response = $this->actingAs($user)->post(route('verification.update-email'), [
            'email' => $newEmail,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->assertEquals($newEmail, $user->fresh()->email);
    }

    public function test_unverified_user_can_logout(): void
    {
        $user = $this->createUnverifiedUser();

        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('home'));
        $this->assertGuest();
    }

    public function test_unverified_propietario_cannot_access_dashboard_and_is_redirected(): void
    {
        $propietario = User::factory()->create([
            'email_verified_at' => null,
            'rol' => 'propietario',
            'activo' => 1,
        ]);

        $response = $this->actingAs($propietario)->get(route('propietario.dashboard'));

        $response->assertRedirect(route('verification.notice'));
    }

    public function test_unverified_admin_cannot_access_dashboard_and_is_redirected(): void
    {
        $admin = User::factory()->create([
            'email_verified_at' => null,
            'rol' => 'admin',
            'activo' => 1,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));

        $response->assertRedirect(route('verification.notice'));
    }

    public function test_registration_redirects_user_directly_to_verification_notice(): void
    {
        Notification::fake();

        $response = $this->post(route('register'), [
            'name' => 'Nuevo Usuario Test',
            'email' => 'nuevo_'.uniqid().'@gracitas.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'rol' => 'cliente',
        ]);

        $response->assertRedirect(route('verification.notice'));
        $this->assertAuthenticated();
        $this->assertFalse(auth()->user()->hasVerifiedEmail());
    }

    public function test_login_as_unverified_user_redirects_to_verification_notice(): void
    {
        $user = User::factory()->create([
            'email' => 'login_unverified_'.uniqid().'@gracitas.com',
            'password' => Hash::make('password123'),
            'email_verified_at' => null,
            'rol' => 'cliente',
            'activo' => 1,
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('verification.notice'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_verified_user_can_access_reservas_normally(): void
    {
        $user = $this->createVerifiedUser();

        $response = $this->actingAs($user)->get(route('cliente.reservas'));

        $response->assertStatus(200);
    }

    public function test_registration_rejects_disposable_email_domain(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Usuario Desechable',
            'email' => 'test_'.uniqid().'@yopmail.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'rol' => 'cliente',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_registration_rejects_nonexistent_email_domain(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Usuario Fake Domain',
            'email' => 'test_'.uniqid().'@dominioquetotalmentenoexisteniunpocomx123987.com',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
            'rol' => 'cliente',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_login_page_renders_with_formal_structure(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee('Acceso al Sistema');
        $response->assertSee('Correo Electrónico Institucional o Personal');
        $response->assertSee('Contraseña de Seguridad');
    }

    public function test_register_page_renders_with_formal_structure(): void
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
        $response->assertSee('Registro Oficial de Cuenta');
        $response->assertSee('Cliente / Deportista');
        $response->assertSee('Propietario / Complejo');
    }
}
