<?php

namespace App\Support;

class Permissions
{
    public const DASHBOARD_VIEW = 'dashboard.view';

    public const USERS_MANAGE = 'users.manage';

    public const MEDICAMENTS_VIEW = 'medicaments.view';
    public const MEDICAMENTS_MANAGE = 'medicaments.manage';

    public const FOURNISSEURS_VIEW = 'fournisseurs.view';
    public const FOURNISSEURS_MANAGE = 'fournisseurs.manage';

    public const COMMANDES_VIEW = 'commandes.view';
    public const COMMANDES_MANAGE = 'commandes.manage';
    public const COMMANDES_RECEIVE = 'commandes.receive';

    public const STOCK_VIEW = 'stock.view';
    public const STOCK_ALERTS = 'stock.alerts';
    public const STOCK_MOVEMENTS = 'stock.movements';

    public const CLIENTS_VIEW = 'clients.view';
    public const CLIENTS_MANAGE = 'clients.manage';

    public const VENTES_VIEW = 'ventes.view';
    public const VENTES_CREATE = 'ventes.create';
    public const VENTES_VALIDATE = 'ventes.validate';
    public const VENTES_INVOICE = 'ventes.invoice';

    public const KPIS_VIEW = 'kpis.view';

    public static function all(): array
    {
        return [
            self::DASHBOARD_VIEW => 'Tableau de bord',
            self::USERS_MANAGE => 'Gestion des utilisateurs',
            self::MEDICAMENTS_VIEW => 'Consulter les médicaments',
            self::MEDICAMENTS_MANAGE => 'Gérer les médicaments',
            self::FOURNISSEURS_VIEW => 'Consulter les fournisseurs',
            self::FOURNISSEURS_MANAGE => 'Gérer les fournisseurs',
            self::COMMANDES_VIEW => 'Consulter les commandes',
            self::COMMANDES_MANAGE => 'Gérer les commandes',
            self::COMMANDES_RECEIVE => 'Réceptionner les commandes',
            self::STOCK_VIEW => 'Consulter le stock',
            self::STOCK_ALERTS => 'Alertes stock / péremption',
            self::STOCK_MOVEMENTS => 'Historique des mouvements',
            self::CLIENTS_VIEW => 'Consulter les clients',
            self::CLIENTS_MANAGE => 'Gérer les clients',
            self::VENTES_VIEW => 'Consulter les ventes',
            self::VENTES_CREATE => 'Créer des ventes',
            self::VENTES_VALIDATE => 'Valider les ventes',
            self::VENTES_INVOICE => 'Télécharger les factures PDF',
            self::KPIS_VIEW => 'Indicateurs KPI (administration)',
        ];
    }

    public static function grouped(): array
    {
        return [
            'Général' => [
                self::DASHBOARD_VIEW,
                self::KPIS_VIEW,
            ],
            'Administration' => [
                self::USERS_MANAGE,
            ],
            'Médicaments & fournisseurs' => [
                self::MEDICAMENTS_VIEW,
                self::MEDICAMENTS_MANAGE,
                self::FOURNISSEURS_VIEW,
                self::FOURNISSEURS_MANAGE,
            ],
            'Approvisionnement' => [
                self::COMMANDES_VIEW,
                self::COMMANDES_MANAGE,
                self::COMMANDES_RECEIVE,
            ],
            'Stock' => [
                self::STOCK_VIEW,
                self::STOCK_ALERTS,
                self::STOCK_MOVEMENTS,
            ],
            'Commercial' => [
                self::CLIENTS_VIEW,
                self::CLIENTS_MANAGE,
                self::VENTES_VIEW,
                self::VENTES_CREATE,
                self::VENTES_VALIDATE,
                self::VENTES_INVOICE,
            ],
        ];
    }
}
