# PharmaGest

Application web monolithique **Laravel 11 + Blade + Tailwind** pour la gestion B2B d'une agence d'importation et de revente de médicaments en gros (Douala, Cameroun).

## Fonctionnalités

- **RBAC** (Spatie) : Administrateur, Magasinier, Vendeur + permissions granulaires
- **Fournisseurs & médicaments** : CRUD complet
- **Commandes** : création multi-lignes, réception → entrée stock automatique (`mouvements_stock`)
- **Stock** : consultation, alertes rupture/péremption, historique des mouvements
- **Clients** : pharmacies et cliniques
- **Ventes** : brouillon → validation (contrôle stock) → sortie stock + **facture PDF**
- **KPIs** : tableau de bord administration
- **Utilisateurs** : gestion réservée à l'administrateur

## Installation (Laragon)

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Créer la base MySQL `phamagest`, puis dans `.env` :

```
DB_CONNECTION=mysql
DB_DATABASE=phamagest
DB_USERNAME=root
DB_PASSWORD=
```

```bash
php artisan migrate:fresh --seed
npm install && npm run build
```

## Comptes de démonstration

| Rôle | E-mail | Mot de passe |
|------|--------|--------------|
| Administrateur | admin@phamagest.cm | password |
| Magasinier | magasinier@phamagest.cm | password |
| Vendeur | vendeur@phamagest.cm | password |

## Parcours métier type

1. **Magasinier** : Créer une commande → la **réceptionner** (stock augmenté, mouvement « entrée »)
2. **Vendeur** : Créer une vente en brouillon → **valider** (si stock OK, déduction + mouvement « sortie »)
3. **Vendeur / Admin** : Télécharger la **facture PDF** d'une vente validée

## Stack

- Laravel 11, Breeze (sessions), Spatie Permission, DomPDF, Tailwind CSS 3, Alpine.js, Vite
