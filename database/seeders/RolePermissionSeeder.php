<?php

namespace Database\Seeders;

use App\Models\Entreprise;
use App\Services\EntrepriseRoleService;
use Illuminate\Database\Seeder;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        app(EntrepriseRoleService::class)->createDefaultRoles(
            Entreprise::where('slug', 'phamagest')->firstOrFail()
        );
    }
}
