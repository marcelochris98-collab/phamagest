<?php

namespace Tests\Feature;

use App\Http\Requests\CommandeRequest;
use App\Models\Commande;
use App\Models\Entreprise;
use App\Models\Fournisseur;
use App\Models\Medicament;
use App\Models\User;
use App\Support\CurrentEntreprise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TenantDataIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_models_only_return_records_from_the_active_company(): void
    {
        $firstEntreprise = $this->createEntreprise('Entreprise A', 'entreprise-a');
        $secondEntreprise = $this->createEntreprise('Entreprise B', 'entreprise-b');

        $this->setCurrentEntreprise($firstEntreprise);
        $firstFournisseur = Fournisseur::create(['nom' => 'Fournisseur A', 'pays' => 'Cameroun']);

        $this->setCurrentEntreprise($secondEntreprise);
        $secondFournisseur = Fournisseur::create(['nom' => 'Fournisseur B', 'pays' => 'Cameroun']);

        $this->setCurrentEntreprise($firstEntreprise);

        $this->assertSame([$firstFournisseur->id], Fournisseur::pluck('id')->all());
        $this->assertNull(Fournisseur::find($secondFournisseur->id));
    }

    public function test_model_relations_and_validation_reject_other_company_records(): void
    {
        $firstEntreprise = $this->createEntreprise('Entreprise A', 'entreprise-a');
        $secondEntreprise = $this->createEntreprise('Entreprise B', 'entreprise-b');

        $this->setCurrentEntreprise($secondEntreprise);
        $secondFournisseur = Fournisseur::create(['nom' => 'Fournisseur B', 'pays' => 'Cameroun']);
        $secondMedicament = Medicament::create([
            'nom' => 'Médicament B',
            'quantite_stock' => 10,
            'seuil_alerte' => 2,
        ]);

        $this->setCurrentEntreprise($firstEntreprise);

        $validator = Validator::make([
            'fournisseur_id' => $secondFournisseur->id,
            'date_commande' => now()->toDateString(),
            'items' => [[
                'medicament_id' => $secondMedicament->id,
                'quantite' => 1,
                'prix_unitaire' => 1,
            ]],
        ], (new CommandeRequest)->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('fournisseur_id', $validator->errors()->toArray());
        $this->assertArrayHasKey('items.0.medicament_id', $validator->errors()->toArray());

        $this->expectException(ValidationException::class);
        Commande::create([
            'fournisseur_id' => $secondFournisseur->id,
            'date_commande' => now()->toDateString(),
        ]);
    }

    public function test_implicit_route_binding_hides_another_companys_records(): void
    {
        $firstEntreprise = $this->createEntreprise('Entreprise A', 'entreprise-a');
        $secondEntreprise = $this->createEntreprise('Entreprise B', 'entreprise-b');

        $this->setCurrentEntreprise($secondEntreprise);
        $secondFournisseur = Fournisseur::create(['nom' => 'Fournisseur B', 'pays' => 'Cameroun']);

        $user = User::factory()->create();
        $user->entreprises()->attach($firstEntreprise);

        $this->actingAs($user)
            ->get(route('fournisseurs.edit', $secondFournisseur))
            ->assertNotFound();
    }

    public function test_company_administrators_only_manage_their_own_users(): void
    {
        $firstEntreprise = $this->createEntreprise('Entreprise A', 'entreprise-a');
        $secondEntreprise = $this->createEntreprise('Entreprise B', 'entreprise-b');
        $admin = User::factory()->create();
        $otherUser = User::factory()->create();
        $admin->entreprises()->attach($firstEntreprise);
        $otherUser->entreprises()->attach($secondEntreprise);
        $admin->assignRole(Role::create([
            'name' => 'administrateur',
            'guard_name' => 'web',
        ]));

        $this->actingAs($admin)
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertDontSee($otherUser->email);

        $this->get(route('admin.users.edit', $otherUser))
            ->assertNotFound();
    }

    private function createEntreprise(string $nom, string $slug): Entreprise
    {
        return Entreprise::create([
            'nom' => $nom,
            'slug' => $slug,
        ]);
    }

    private function setCurrentEntreprise(Entreprise $entreprise): void
    {
        app(CurrentEntreprise::class)->set($entreprise);
    }
}
