<?php

namespace Tests\Feature;

use App\Models\Entreprise;
use App\Models\User;
use App\Support\CurrentEntreprise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurrentEntrepriseTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_single_company_is_selected_automatically(): void
    {
        $user = User::factory()->create();
        $entreprise = $this->createEntreprise('Entreprise A', 'entreprise-a');
        $user->entreprises()->attach($entreprise);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertForbidden()
            ->assertSessionHas('active_entreprise_id', $entreprise->id);

        $this->assertSame($entreprise->id, app(CurrentEntreprise::class)->get()->id);
    }

    public function test_a_user_with_multiple_companies_must_choose_an_active_company(): void
    {
        $user = User::factory()->create();
        $first = $this->createEntreprise('Entreprise A', 'entreprise-a');
        $second = $this->createEntreprise('Entreprise B', 'entreprise-b');
        $user->entreprises()->attach([$first->id, $second->id]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertRedirect(route('entreprise.choose'));

        $this->get(route('entreprise.choose'))
            ->assertOk()
            ->assertSee('Entreprise A')
            ->assertSee('Entreprise B');
    }

    public function test_a_user_can_switch_only_to_a_company_they_belong_to(): void
    {
        $user = User::factory()->create();
        $first = $this->createEntreprise('Entreprise A', 'entreprise-a');
        $second = $this->createEntreprise('Entreprise B', 'entreprise-b');
        $other = $this->createEntreprise('Entreprise C', 'entreprise-c');
        $user->entreprises()->attach([$first->id, $second->id]);

        $this->actingAs($user)
            ->withSession(['active_entreprise_id' => $first->id])
            ->post(route('entreprise.active'), ['entreprise_id' => $second->id])
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('active_entreprise_id', $second->id);

        $this->post(route('entreprise.active'), ['entreprise_id' => $other->id])
            ->assertForbidden()
            ->assertSessionHas('active_entreprise_id', $second->id);
    }

    private function createEntreprise(string $nom, string $slug): Entreprise
    {
        return Entreprise::create([
            'nom' => $nom,
            'slug' => $slug,
        ]);
    }
}
