<?php

namespace Tests\Feature\Auth;

use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register(): void
    {
        Notification::fake();

        $response = $this->post('/register', [
            'entreprise' => 'Pharmacie Test',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('verification.notice'));

        $entreprise = Entreprise::where('nom', 'Pharmacie Test')->firstOrFail();
        $user = User::where('email', 'test@example.com')->firstOrFail();

        $this->assertTrue($user->entreprises()->whereKey($entreprise->id)->exists());
        app(PermissionRegistrar::class)->setPermissionsTeamId($entreprise->id);
        $this->assertTrue($user->hasRole('administrateur'));
        $this->assertFalse($user->hasVerifiedEmail());
        Notification::assertSentTo($user, VerifyEmail::class);
    }
}
