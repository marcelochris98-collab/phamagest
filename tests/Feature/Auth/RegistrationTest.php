<?php

namespace Tests\Feature\Auth;

use App\Models\Entreprise;
use App\Models\User;
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

        $response->assertOk()
            ->assertDontSee('Justificatifs de l’entreprise')
            ->assertDontSee('type="file"', false)
            ->assertDontSee('Numéro d’enregistrement')
            ->assertDontSee('Numéro d’autorisation');
    }

    public function test_new_users_can_register_without_legal_identifiers_or_documents(): void
    {
        $this->travelTo(now()->startOfSecond());
        Notification::fake();

        $response = $this->post('/register', [
            'entreprise' => 'Pharmacie Test',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));

        $entreprise = Entreprise::where('nom', 'Pharmacie Test')->firstOrFail();
        $user = User::where('email', 'test@example.com')->firstOrFail();

        $this->assertTrue($user->entreprises()->whereKey($entreprise->id)->exists());
        app(PermissionRegistrar::class)->setPermissionsTeamId($entreprise->id);
        $this->assertTrue($user->hasRole('administrateur'));
        $this->assertFalse($user->hasVerifiedEmail());
        $this->assertSame('approved', $entreprise->verification_status);
        $this->assertSame('trial', $entreprise->subscription_status);
        $this->assertTrue($entreprise->trial_ends_at->equalTo(now()->addMonth()));
        $this->assertNull($entreprise->numero_enregistrement);
        $this->assertNull($entreprise->numero_autorisation);
        $this->assertDatabaseCount('entreprise_documents', 0);
        $this->get(route('dashboard'))->assertOk();
        $this->get(route('admin.users.index'))->assertOk();
        Notification::assertNothingSent();
    }
}
