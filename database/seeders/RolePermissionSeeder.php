<?php

namespace Database\Seeders;

use App\Support\Permissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (Permissions::all() as $name => $label) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $administrateur = Role::firstOrCreate(['name' => 'administrateur', 'guard_name' => 'web']);
        $administrateur->syncPermissions(Permission::all());

        $magasinier = Role::firstOrCreate(['name' => 'magasinier', 'guard_name' => 'web']);
        $magasinier->syncPermissions([
            Permissions::DASHBOARD_VIEW,
            Permissions::MEDICAMENTS_VIEW,
            Permissions::FOURNISSEURS_VIEW,
            Permissions::COMMANDES_VIEW,
            Permissions::COMMANDES_MANAGE,
            Permissions::COMMANDES_RECEIVE,
            Permissions::STOCK_VIEW,
            Permissions::STOCK_ALERTS,
            Permissions::STOCK_MOVEMENTS,
        ]);

        $vendeur = Role::firstOrCreate(['name' => 'vendeur', 'guard_name' => 'web']);
        $vendeur->syncPermissions([
            Permissions::DASHBOARD_VIEW,
            Permissions::MEDICAMENTS_VIEW,
            Permissions::CLIENTS_VIEW,
            Permissions::CLIENTS_MANAGE,
            Permissions::VENTES_VIEW,
            Permissions::VENTES_CREATE,
            Permissions::VENTES_VALIDATE,
            Permissions::VENTES_INVOICE,
        ]);
    }
}
