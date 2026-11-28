# PharmaGest

Application web monolithique **Laravel 13 + Blade + Tailwind** pour la gestion B2B d'une agence d'importation et de revente de médicaments en gros (Douala, Cameroun).

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

Le projet nécessite **PHP 8.3 ou supérieur** et Composer. Puis installez les dépendances :

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
- L’inscription demande seulement les coordonnées de l’entreprise et de son administrateur. Aucun numéro légal ni justificatif n’est requis pendant la phase de tests utilisateurs.
- À l’inscription, l’entreprise et son premier compte administrateur sont activés immédiatement avec un essai gratuit d’un mois. La migration `2026_11_24_000005_start_trials_for_existing_pending_companies` convertit également les inscriptions antérieures restées en attente.
- Une adresse e-mail ne peut créer qu'un compte. L'administrateur peut ensuite créer les comptes employés depuis **Administration → Utilisateurs** et leur attribuer un rôle dans son entreprise.
- La connexion se fait sur `/login`. Si un compte appartient à plusieurs entreprises, l'application demande de choisir l'espace actif.
- La vérification de l’adresse e-mail n’est pas requise pour accéder à l’espace entreprise pendant cette phase de tests.
- Les données métier et les rôles sont séparés par entreprise. L’inscription ne vérifie pas l’identité légale ou l’autorisation pharmaceutique de l’entreprise.
- Le compte administrateur plateforme est distinct des comptes d’entreprise. Créez-le une seule fois avec `php artisan platform:admin:create admin@example.com`, puis choisissez un mot de passe dédié et robuste dans l’invite masquée. Connectez-vous depuis `/login`; ce compte arrive directement sur `/platform/companies` et ne rejoint aucun espace entreprise. Les administrateurs d’entreprise ordinaires arrivent sur leur tableau de bord.
- L’administration plateforme (`/platform/companies`) permet de gérer les statuts d’abonnement et les essais; les décisions sont journalisées. Pour promouvoir un compte existant à la place, utilisez `php artisan platform:admin adresse@example.com`.
- Les forfaits Essentiel, Croissance et Entreprise sont configurables dans `/platform/plans`. Le super-admin peut modifier les prix mensuels en FCFA, les plafonds d’utilisateurs et de produits, et l’accès aux KPI. Une limite vide signifie « sans limite »; modifier une limite ne supprime aucune donnée existante.
- L’essai initial dure un mois sur le forfait Essentiel. Dans les 7 jours avant l’échéance, l’espace entreprise affiche un avertissement. À l’échéance, la première requête protégée suspend l’accès aux fonctions métier et journalise l’expiration; l’entreprise conserve l’accès à sa page abonnement pour demander un forfait. L’administrateur plateforme doit l’activer manuellement.
- L’entreprise peut demander un forfait ou son renouvellement depuis **Forfait**; la demande ne vaut pas paiement et n’active pas l’abonnement. Les prix sont à définir par le super-admin. Aucun paiement en ligne ni facturation récurrente n’est branché.

### Sauvegardes et restauration (MySQL/MariaDB)

- `php artisan database:backup` crée un fichier `.sql.gz` sous `storage/app/private/backups` par défaut. Pour un stockage hors site S3-compatible (S3 ou Cloudflare R2), configurez `BACKUP_DISK=s3` et les variables `AWS_*`; la rétention de 14 jours s’applique au disque sélectionné.
- Le planificateur Laravel lance une sauvegarde à 02:00. Sur un serveur Linux, activez-le avec une tâche cron exécutant `php /chemin/vers/phamagest/artisan schedule:run` chaque minute.
- Une restauration est destructive et uniquement disponible dans le terminal interactif : `php artisan database:restore phamagest-AAAAMMJJ-HHMMSS.sql.gz`. L’archive est lue depuis le disque configuré; l’opérateur doit confirmer et une sauvegarde pré-restauration est créée automatiquement avant import.
- Les fichiers locaux ne protègent pas contre la panne ou la perte du serveur. Avant production, configurez un bucket hors site avec chiffrement côté fournisseur, des identifiants limités et une rétention, puis testez régulièrement la restauration sur une base isolée. Les archives restent des dumps SQL sensibles; ne les publiez jamais via `public/`.

### Déploiement et services externes restant à choisir

- Il n’y a pas encore d’hébergeur ni de domaine configuré. Un tunnel/sous-domaine gratuit peut servir à une démo temporaire seulement; il ne constitue pas un hébergement fiable ni une adresse de production. Un vrai HTTPS nécessite un domaine maîtrisé et un serveur public; Cloudflare peut fournir gratuitement un certificat TLS pour un domaine activé chez eux.
- Configurez un fournisseur SMTP dans les variables `MAIL_*` du serveur pour la réinitialisation de mot de passe et les notifications. Le mode local `MAIL_MAILER=log` n’envoie pas de vrais e-mails.
- Aucun paiement n’est intégré. Choisissez et ouvrez un compte auprès d’une passerelle compatible Cameroun avant de développer les webhooks de paiement.
- Le contrôle documentaire est une revue manuelle interne, pas une certification réglementaire. Faites valider la liste exacte des justificatifs par un conseiller local compétent avant d’accepter de vraies pharmacies ou entreprises.
- Avant toute ouverture publique : `APP_ENV=production`, `APP_DEBUG=false`, HTTPS, clé APP protégée, sauvegarde hors site testée, SMTP vérifié, limites d’upload, supervision et procédure d’incident. Les dépendances ont été mises à niveau vers Laravel 13 et `composer audit` ne signale actuellement aucun avis pour le lockfile; relancez cet audit avant chaque déploiement et gardez les dépendances à jour.

## Parcours métier type

1. **Magasinier** : Créer une commande → la **réceptionner** (stock augmenté, mouvement « entrée »)
2. **Vendeur** : Créer une vente en brouillon → **valider** (si stock OK, déduction + mouvement « sortie »)
3. **Vendeur / Admin** : Télécharger la **facture PDF** d'une vente validée

## Stack

- Laravel 13, Breeze (sessions), Spatie Permission, DomPDF, Tailwind CSS 3, Alpine.js, Vite
