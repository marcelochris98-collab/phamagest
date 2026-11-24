<?php

namespace Tests\Feature;

use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EntrepriseMembershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_belong_to_multiple_companies(): void
    {
        $user = User::factory()->create();
        $firstEntreprise = Entreprise::create([
            'nom' => 'Entreprise A',
            'slug' => 'entreprise-a',
        ]);
        $secondEntreprise = Entreprise::create([
            'nom' => 'Entreprise B',
            'slug' => 'entreprise-b',
        ]);

        $user->entreprises()->attach([$firstEntreprise->id, $secondEntreprise->id]);

        $this->assertCount(2, $user->fresh()->entreprises);
        $this->assertTrue($firstEntreprise->users->contains($user));
        $this->assertTrue($secondEntreprise->users->contains($user));
    }
}
