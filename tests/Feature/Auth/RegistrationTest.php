<?php

namespace Tests\Feature\Auth;

use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
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
        Storage::fake('local');

        $response = $this->post('/register', [
            'entreprise' => 'Pharmacie Test',
            'numero_enregistrement' => 'RC-12345',
            'numero_autorisation' => 'AUT-67890',
            'document_enregistrement' => UploadedFile::fake()->image('registre.jpg'),
            'document_autorisation' => UploadedFile::fake()->image('autorisation.jpg'),
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertAuthenticated();
        $response->assertRedirect(route('entreprise.verification.status'));

        $entreprise = Entreprise::where('nom', 'Pharmacie Test')->firstOrFail();
        $user = User::where('email', 'test@example.com')->firstOrFail();

        $this->assertTrue($user->entreprises()->whereKey($entreprise->id)->exists());
        app(PermissionRegistrar::class)->setPermissionsTeamId($entreprise->id);
        $this->assertTrue($user->hasRole('administrateur'));
        $this->assertFalse($user->hasVerifiedEmail());
        Notification::assertSentTo($user, VerifyEmail::class);
        $this->assertSame('pending', $entreprise->verification_status);
        $this->assertDatabaseHas('entreprise_documents', [
            'entreprise_id' => $entreprise->id,
            'type' => 'enregistrement',
        ]);
        $this->assertDatabaseHas('entreprise_documents', [
            'entreprise_id' => $entreprise->id,
            'type' => 'autorisation',
        ]);
    }
}
