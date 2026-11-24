<?php

namespace Database\Seeders;

use App\Models\Entreprise;
use Illuminate\Database\Seeder;

class DefaultEntrepriseSeeder extends Seeder
{
    public function run(): void
    {
        Entreprise::firstOrCreate(
            ['slug' => 'phamagest'],
            ['nom' => 'PharmaGest']
        );
    }
}
