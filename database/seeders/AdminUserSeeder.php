<?php

namespace Database\Seeders;

use App\Models\Entreprise;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $entreprise = Entreprise::firstOrCreate(
            ['slug' => 'phamagest'],
            ['nom' => 'PharmaGest']
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@phamagest.cm'],
            [
                'name' => 'Administrateur PharmaGest',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $admin->syncRoles(['administrateur']);
        $admin->entreprises()->syncWithoutDetaching([$entreprise->id]);
    }
}
