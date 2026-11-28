<?php

namespace Tests\Feature;

use App\Models\Entreprise;
use App\Models\Medicament;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Support\CurrentEntreprise;
use App\Support\Permissions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class SubscriptionManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_platform_admin_can_change_plan_limits_and_price(): void
    {
        $admin = User::factory()->create();
        $admin->forceFill(['is_platform_admin' => true])->save();
        $plan = SubscriptionPlan::where('slug', 'essentiel')->firstOrFail();

        $this->actingAs($admin)
            ->get(route('platform.plans.index'))
            ->assertOk()
            ->assertSee('Forfaits et limites')
            ->assertSee('FCFA');

        $this->actingAs($admin)
            ->patch(route('platform.plans.update', $plan), [
                'name' => 'Essentiel',
                'price_fcfa' => 15000,
                'user_limit' => 4,
                'product_limit' => 750,
                'kpis_enabled' => '1',
                'is_active' => '1',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('subscription_plans', [
            'id' => $plan->id,
            'price_fcfa' => 15000,
            'user_limit' => 4,
            'product_limit' => 750,
            'kpis_enabled' => true,
        ]);
        $this->assertDatabaseHas('platform_audit_logs', ['action' => 'subscription_plan.updated']);
    }

    public function test_company_admin_can_request_a_plan_without_activating_it(): void
    {
        [$entreprise, $admin] = $this->createCompany();
        $entreprise->update(['subscription_status' => 'trial', 'trial_ends_at' => now()->addMonth()]);

        $this->actingAs($admin)
            ->from(route('entreprise.subscription.status'))
            ->post(route('entreprise.subscription.request'), ['subscription_plan' => 'croissance'])
            ->assertRedirect(route('entreprise.subscription.status'));

        $entreprise->refresh();
        $this->assertSame('trial', $entreprise->subscription_status);
        $this->assertSame('croissance', $entreprise->subscription_requested_plan);
        $this->assertDatabaseHas('platform_audit_logs', [
            'entreprise_id' => $entreprise->id,
            'action' => 'subscription.requested',
        ]);

        $this->get(route('entreprise.subscription.status'))
            ->assertOk()
            ->assertSee('Croissance')
            ->assertSee('n’est pas encore disponible');
    }

    public function test_platform_admin_activates_a_requested_plan_with_a_required_expiry_date(): void
    {
        [$entreprise] = $this->createCompany();
        $entreprise->update([
            'subscription_status' => 'expired',
            'trial_ends_at' => now()->subDay(),
            'subscription_requested_plan' => 'croissance',
            'subscription_requested_at' => now(),
        ]);
        $platformAdmin = User::factory()->create();
        $platformAdmin->forceFill(['is_platform_admin' => true])->save();

        $this->actingAs($platformAdmin)
            ->patch(route('platform.companies.subscription', $entreprise), [
                'subscription_status' => 'active',
                'subscription_plan' => 'croissance',
                'subscription_ends_at' => now()->addMonth()->format('Y-m-d\TH:i'),
                'trial_ends_at' => $entreprise->trial_ends_at->format('Y-m-d\TH:i'),
            ])
            ->assertRedirect();

        $entreprise->refresh();
        $this->assertSame('active', $entreprise->subscription_status);
        $this->assertSame('croissance', $entreprise->subscription_plan);
        $this->assertNull($entreprise->subscription_requested_plan);
        $this->assertTrue($entreprise->subscription_ends_at->isFuture());
        $this->assertDatabaseHas('platform_audit_logs', [
            'entreprise_id' => $entreprise->id,
            'action' => 'subscription.updated',
        ]);
    }

    public function test_subscription_expires_automatically_and_requests_remain_available(): void
    {
        [$entreprise, $admin] = $this->createCompany();
        $entreprise->update([
            'subscription_status' => 'active',
            'subscription_ends_at' => now()->subSecond(),
        ]);

        $this->actingAs($admin)
            ->get(route('entreprise.subscription.status'))
            ->assertOk()
            ->assertSee('L’accès aux fonctionnalités de gestion est suspendu');

        $this->assertSame('expired', $entreprise->fresh()->subscription_status);
        $this->assertDatabaseHas('platform_audit_logs', [
            'entreprise_id' => $entreprise->id,
            'action' => 'subscription.expired',
        ]);
        $this->get(route('dashboard'))->assertRedirect(route('entreprise.subscription.status'));
        $this->get(route('entreprise.subscription.status'))->assertSee('Demander ce forfait');
    }

    public function test_company_receives_an_expiration_warning_within_seven_days(): void
    {
        [$entreprise, $admin] = $this->createCompany();
        $entreprise->update([
            'subscription_status' => 'trial',
            'trial_ends_at' => now()->addDays(6),
        ]);

        $this->actingAs($admin)
            ->get(route('entreprise.subscription.status'))
            ->assertOk()
            ->assertSee('expire dans 6 jour(s)')
            ->assertSee('Voir les forfaits');
    }

    public function test_plan_limits_block_new_users_and_products(): void
    {
        [$entreprise, $admin, $role] = $this->createCompany();
        $plan = SubscriptionPlan::where('slug', 'essentiel')->firstOrFail();
        $plan->update(['user_limit' => 1, 'product_limit' => 1]);
        $this->setUpCurrentEntreprise($entreprise);

        $this->actingAs($admin)
            ->post(route('admin.users.store'), [
                'name' => 'Nouvel utilisateur',
                'email' => 'nouveau@example.com',
                'password' => 'a-strong-password',
                'password_confirmation' => 'a-strong-password',
                'role' => 'administrateur',
            ])
            ->assertSessionHas('error', 'La limite d’utilisateurs de votre forfait est atteinte. Contactez PharmaGest pour changer de forfait.');

        Permission::firstOrCreate(['name' => 'medicaments.manage', 'guard_name' => 'web']);
        $role->givePermissionTo('medicaments.manage');
        Medicament::create(['nom' => 'Produit déjà au catalogue', 'quantite_stock' => 3, 'seuil_alerte' => 1]);

        $this->post(route('medicaments.store'), [
            'nom' => 'Produit supplémentaire',
            'quantite_stock' => 2,
            'seuil_alerte' => 1,
        ])
            ->assertSessionHas('error', 'La limite de produits de votre forfait est atteinte. Contactez PharmaGest pour changer de forfait.');

        $this->assertSame(1, Medicament::count());
    }

    public function test_kpi_access_depends_on_the_active_plan(): void
    {
        [$entreprise, $admin, $role] = $this->createCompany();
        Permission::firstOrCreate(['name' => 'kpis.view', 'guard_name' => 'web']);
        $role->givePermissionTo('kpis.view');
        $this->setUpCurrentEntreprise($entreprise);

        $this->actingAs($admin)->get(route('kpis.index'))->assertForbidden();

        SubscriptionPlan::where('slug', 'essentiel')->update(['kpis_enabled' => true]);
        $this->get(route('kpis.index'))->assertOk();
    }

    public function test_company_dashboard_uses_role_permissions_and_plan_features(): void
    {
        [$entreprise, $admin, $role] = $this->createCompany();
        Permission::firstOrCreate(['name' => Permissions::DASHBOARD_VIEW, 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => Permissions::MEDICAMENTS_VIEW, 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => Permissions::KPIS_VIEW, 'guard_name' => 'web']);
        $role->givePermissionTo([
            Permissions::DASHBOARD_VIEW,
            Permissions::MEDICAMENTS_VIEW,
            Permissions::KPIS_VIEW,
        ]);
        $this->setUpCurrentEntreprise($entreprise);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Vue d’ensemble')
            ->assertSee('Produits au catalogue')
            ->assertDontSee('Chiffre d’affaires du mois')
            ->assertDontSee('Voir les indicateurs KPI');
    }

    private function createCompany(): array
    {
        $entreprise = Entreprise::create([
            'nom' => 'Pharmacie Test',
            'slug' => 'pharmacie-test-'.fake()->unique()->numerify('###'),
            'verification_status' => 'approved',
            'subscription_status' => 'trial',
            'subscription_plan' => 'essentiel',
            'trial_ends_at' => now()->addMonth(),
        ]);
        $admin = User::factory()->create();
        $entreprise->users()->attach($admin);

        app(PermissionRegistrar::class)->setPermissionsTeamId($entreprise->id);
        $role = Role::create([
            'name' => 'administrateur',
            'guard_name' => 'web',
            'team_id' => $entreprise->id,
        ]);
        $admin->assignRole($role);

        return [$entreprise, $admin, $role];
    }

    private function setUpCurrentEntreprise(Entreprise $entreprise): void
    {
        app(CurrentEntreprise::class)->set($entreprise);
        app(PermissionRegistrar::class)->setPermissionsTeamId($entreprise->id);
    }
}
