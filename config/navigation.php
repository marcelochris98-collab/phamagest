<?php

use App\Support\Permissions;

return [
    [
        'label' => 'Tableau de bord',
        'route' => 'dashboard',
        'icon' => 'home',
        'permission' => Permissions::DASHBOARD_VIEW,
    ],
    [
        'label' => 'KPIs',
        'route' => 'kpis.index',
        'icon' => 'chart',
        'permission' => Permissions::KPIS_VIEW,
    ],
    [
        'label' => 'Utilisateurs',
        'route' => 'admin.users.index',
        'icon' => 'users',
        'permission' => Permissions::USERS_MANAGE,
        'role' => 'administrateur',
    ],
    [
        'label' => 'Forfait',
        'route' => 'entreprise.subscription.status',
        'icon' => 'building',
        'role' => 'administrateur',
    ],
    [
        'label' => 'Médicaments',
        'route' => 'medicaments.index',
        'icon' => 'pill',
        'permission' => Permissions::MEDICAMENTS_VIEW,
    ],
    [
        'label' => 'Fournisseurs',
        'route' => 'fournisseurs.index',
        'icon' => 'truck',
        'permission' => Permissions::FOURNISSEURS_VIEW,
    ],
    [
        'label' => 'Commandes',
        'route' => 'commandes.index',
        'icon' => 'clipboard',
        'permission' => Permissions::COMMANDES_VIEW,
    ],
    [
        'label' => 'Stock',
        'route' => 'stock.index',
        'icon' => 'archive',
        'permission' => Permissions::STOCK_VIEW,
    ],
    [
        'label' => 'Alertes stock',
        'route' => 'stock.alertes',
        'icon' => 'alert',
        'permission' => Permissions::STOCK_ALERTS,
        'pulse' => true,
    ],
    [
        'label' => 'Mouvements',
        'route' => 'stock.mouvements',
        'icon' => 'arrows',
        'permission' => Permissions::STOCK_MOVEMENTS,
    ],
    [
        'label' => 'Clients',
        'route' => 'clients.index',
        'icon' => 'building',
        'permission' => Permissions::CLIENTS_VIEW,
    ],
    [
        'label' => 'Ventes',
        'route' => 'ventes.index',
        'icon' => 'cart',
        'permission' => Permissions::VENTES_VIEW,
    ],
];
