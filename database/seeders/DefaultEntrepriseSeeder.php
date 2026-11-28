<?php

namespace Database\Seeders;

use App\Models\Entreprise;
use Illuminate\Database\Seeder;

class DefaultEntrepriseSeeder extends Seeder
{
    public function run(): void
    {
        Entreprise::updateOrCreate(
            ['slug' => 'phamagest'],
            [
                'nom' => 'PharmaGest',
                'verification_status' => 'approved',
                'verified_at' => now(),
                'subscription_status' => 'active',
                'subscription_plan' => 'entreprise',
            ]
        );
    }
}
