<?php

namespace Database\Seeders;

use App\Models\Entreprise;
use App\Models\User;
use App\Services\EntrepriseRoleService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\PermissionRegistrar;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $entreprise = Entreprise::firstOrCreate(
            ['slug' => 'phamagest'],
            ['nom' => 'PharmaGest']
        );
        app(PermissionRegistrar::class)->setPermissionsTeamId($entreprise->id);

        $admin = User::firstOrCreate(
            ['email' => 'admin@phamagest.cm'],
            [
                'name' => 'Administrateur PharmaGest',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $admin->syncRoles([app(EntrepriseRoleService::class)->createDefaultRoles($entreprise)]);
        $admin->entreprises()->syncWithoutDetaching([$entreprise->id]);
    }
}
