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

## Accès multi-entreprises

- La page `/register` crée un espace entreprise et son premier compte administrateur.
- Une adresse e-mail ne peut créer qu'un compte. L'administrateur peut ensuite créer les comptes employés depuis **Administration → Utilisateurs** et leur attribuer un rôle dans son entreprise.
- La connexion se fait sur `/login`. Si un compte appartient à plusieurs entreprises, l'application demande de choisir l'espace actif.
- La vérification de l'adresse e-mail est requise avant d'accéder à l'application. En local, le message de vérification est écrit dans les logs Laravel avec `MAIL_MAILER=log`; en production, configurez un fournisseur de messagerie SMTP et vérifiez sa délivrabilité.
- Les données métier et les rôles sont séparés par entreprise. L'inscription libre ne vérifie toutefois pas encore l'identité légale ou l'autorisation pharmaceutique de l'entreprise.

Avant une mise en production, configurez un vrai domaine HTTPS, les courriels, les sauvegardes/restaurations et le suivi opérationnel. L'application n'inclut pas encore de facturation/abonnement SaaS ni de vérification réglementaire des entreprises.

## Parcours métier type

1. **Magasinier** : Créer une commande → la **réceptionner** (stock augmenté, mouvement « entrée »)
2. **Vendeur** : Créer une vente en brouillon → **valider** (si stock OK, déduction + mouvement « sortie »)
3. **Vendeur / Admin** : Télécharger la **facture PDF** d'une vente validée

## Stack

- Laravel 11, Breeze (sessions), Spatie Permission, DomPDF, Tailwind CSS 3, Alpine.js, Vite
