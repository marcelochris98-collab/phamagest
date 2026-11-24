<?php

namespace Tests\Feature;

use App\Models\Entreprise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DefaultEntrepriseSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_demo_records_and_users_are_assigned_to_the_default_company(): void
    {
        $this->seed();

        $entreprise = Entreprise::where('slug', 'phamagest')->firstOrFail();

        foreach ([
            'fournisseurs',
            'medicaments',
            'clients',
            'commandes',
            'commande_items',
        ] as $table) {
            $this->assertSame(
                0,
                DB::table($table)->whereNull('entreprise_id')->count(),
                "The {$table} table contains records without a company."
            );
            $this->assertGreaterThan(
                0,
                DB::table($table)->where('entreprise_id', $entreprise->id)->count(),
                "The {$table} table has no records for the default company."
            );
        }

        $this->assertSame(3, $entreprise->users()->count());
    }
}
