<?php

namespace App\Services;

use App\Models\Entreprise;
use App\Support\Permissions;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class EntrepriseRoleService
{
    public function createDefaultRoles(Entreprise $entreprise): Role
    {
        $registrar = app(PermissionRegistrar::class);
        $registrar->setPermissionsTeamId($entreprise->getKey());

        foreach (Permissions::all() as $name => $label) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $definitions = [
            'administrateur' => Permission::all(),
            'magasinier' => [
                Permissions::DASHBOARD_VIEW,
                Permissions::MEDICAMENTS_VIEW,
                Permissions::FOURNISSEURS_VIEW,
                Permissions::COMMANDES_VIEW,
                Permissions::COMMANDES_MANAGE,
                Permissions::COMMANDES_RECEIVE,
                Permissions::STOCK_VIEW,
                Permissions::STOCK_ALERTS,
                Permissions::STOCK_MOVEMENTS,
            ],
            'vendeur' => [
                Permissions::DASHBOARD_VIEW,
                Permissions::MEDICAMENTS_VIEW,
                Permissions::CLIENTS_VIEW,
                Permissions::CLIENTS_MANAGE,
                Permissions::VENTES_VIEW,
                Permissions::VENTES_CREATE,
                Permissions::VENTES_VALIDATE,
                Permissions::VENTES_INVOICE,
            ],
        ];

        $administrator = null;
        foreach ($definitions as $name => $permissions) {
            $role = Role::firstOrCreate([
                'name' => $name,
                'guard_name' => 'web',
                'team_id' => $entreprise->getKey(),
            ]);

            $role->syncPermissions($permissions);

            if ($name === 'administrateur') {
                $administrator = $role;
            }
        }

        if (! $administrator) {
            throw new \LogicException('The company administrator role was not created.');
        }

        return $administrator;
    }
}
