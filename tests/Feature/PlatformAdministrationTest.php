<?php

namespace Tests\Feature;

use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class PlatformAdministrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_platform_administrators_can_review_company_applications(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('platform.companies.index'))
            ->assertForbidden();

        $user->forceFill(['is_platform_admin' => true])->save();

        $this->get(route('platform.companies.index'))->assertOk();
    }

    public function test_approving_a_company_starts_a_trial_without_documents(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_platform_admin' => true])->save();
        $entreprise = $this->createPendingCompany();

        $this->actingAs($admin)
            ->post(route('platform.companies.approve', $entreprise), [
                'trial_days' => 14,
                'note' => 'Dossier vérifié manuellement.',
            ])
            ->assertRedirect();

        $entreprise->refresh();
        $this->assertSame('approved', $entreprise->verification_status);
        $this->assertSame('trial', $entreprise->subscription_status);
        $this->assertTrue($entreprise->trial_ends_at->isFuture());
        $this->assertSame($admin->id, $entreprise->verified_by);
        $this->assertDatabaseCount('entreprise_documents', 0);
        $this->get(route('platform.companies.index'))
            ->assertOk()
            ->assertSee('Aucun justificatif fourni')
            ->assertSee('Historique des actions plateforme')
            ->assertSee($admin->email);
    }

    public function test_rejected_company_can_resubmit_without_documents(): void
    {
        $entreprise = $this->createPendingCompany();
        $entreprise->update(['verification_status' => 'rejected']);
        $user = $entreprise->users()->firstOrFail();

        $this->actingAs($user)
            ->post(route('entreprise.verification.resubmit'), [
            ])
            ->assertRedirect(route('entreprise.verification.status'));

        $this->assertSame('pending', $entreprise->fresh()->verification_status);
        $this->assertNull($entreprise->fresh()->numero_enregistrement);
        $this->assertNull($entreprise->fresh()->numero_autorisation);
        $this->assertDatabaseCount('entreprise_documents', 0);
    }

    public function test_only_platform_administrators_can_download_private_company_documents(): void
    {
        Storage::fake('local');
        $entreprise = $this->createPendingCompany(withDocuments: true);
        $document = $entreprise->documents()->firstOrFail();
        Storage::disk('local')->put($document->path, 'private document');
        $user = User::factory()->create();

        $this->get(route('platform.documents.download', $document))
            ->assertRedirect(route('login'));

        $this->actingAs($user)
            ->get(route('platform.documents.download', $document))
            ->assertForbidden();

        $admin = User::factory()->create();
        $admin->forceFill(['is_platform_admin' => true])->save();

        $this->actingAs($admin)
            ->get(route('platform.documents.download', $document))
            ->assertOk()
            ->assertStreamedContent('private document');
    }

    public function test_unapproved_or_expired_companies_cannot_open_business_routes(): void
    {
        $entreprise = $this->createPendingCompany();
        $user = $entreprise->users()->firstOrFail();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertRedirect(route('entreprise.verification.status'));

        $entreprise->update([
            'verification_status' => 'approved',
            'subscription_status' => 'trial',
            'trial_ends_at' => now()->subDay(),
        ]);

        $this->get('/dashboard')
            ->assertRedirect(route('entreprise.subscription.status'));
    }

    public function test_platform_admin_promotion_requires_an_existing_user_and_confirmation(): void
    {
        $user = User::factory()->create();

        $this->artisan('platform:admin', ['email' => $user->email])
            ->expectsConfirmation("Grant platform administrator access to {$user->email}?", 'no')
            ->assertSuccessful();

        $this->assertFalse($user->fresh()->isPlatformAdmin());

        $this->artisan('platform:admin', ['email' => $user->email])
            ->expectsConfirmation("Grant platform administrator access to {$user->email}?", 'yes')
            ->assertSuccessful();

        $this->assertTrue($user->fresh()->isPlatformAdmin());
    }

    private function createPendingCompany(bool $withDocuments = false): Entreprise
    {
        $entreprise = Entreprise::create([
            'nom' => 'Entreprise Test',
            'slug' => 'entreprise-test',
            'verification_status' => 'pending',
            'subscription_status' => 'pending',
        ]);

        $user = User::factory()->create(['email_verified_at' => now()]);
        $entreprise->users()->attach($user);

        app(PermissionRegistrar::class)->setPermissionsTeamId($entreprise->id);
        $role = Role::create([
            'name' => 'administrateur',
            'guard_name' => 'web',
            'team_id' => $entreprise->id,
        ]);
        $user->assignRole($role);

        if ($withDocuments) {
            foreach (['enregistrement', 'autorisation'] as $type) {
                $entreprise->documents()->create([
                    'uploaded_by' => $user->id,
                    'type' => $type,
                    'path' => "private/entreprise-documents/{$entreprise->id}/{$type}.pdf",
                    'original_name' => "{$type}.pdf",
                    'mime_type' => 'application/pdf',
                    'size' => 100,
                ]);
            }
        }

        return $entreprise;
    }
}
