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
- L’inscription demande seulement les coordonnées de l’entreprise et de son administrateur. Aucun numéro légal ni justificatif n’est requis pendant la phase de tests utilisateurs.
- Les nouvelles entreprises restent en attente; un administrateur plateforme examine manuellement la demande et peut l’approuver sans justificatifs pour démarrer l’essai gratuit.
- Une adresse e-mail ne peut créer qu'un compte. L'administrateur peut ensuite créer les comptes employés depuis **Administration → Utilisateurs** et leur attribuer un rôle dans son entreprise.
- La connexion se fait sur `/login`. Si un compte appartient à plusieurs entreprises, l'application demande de choisir l'espace actif.
- La vérification de l'adresse e-mail est requise avant d'accéder à l'application. En local, le message de vérification est écrit dans les logs Laravel avec `MAIL_MAILER=log`; en production, configurez un fournisseur de messagerie SMTP et vérifiez sa délivrabilité.
- Les données métier et les rôles sont séparés par entreprise. L'inscription libre ne vérifie toutefois pas encore l'identité légale ou l'autorisation pharmaceutique de l'entreprise.
- L’administration plateforme (`/platform/companies`) est réservée aux comptes promus manuellement avec `php artisan platform:admin adresse@example.com`. Cette commande demande confirmation. Elle permet de consulter les dossiers, télécharger les justificatifs privés éventuellement fournis, approuver ou refuser une demande, et gérer l’essai/l’abonnement. Les décisions sont journalisées.
- Les abonnements sont gérés manuellement; aucun paiement en ligne ni facturation récurrente n’est branché.

### Sauvegardes et restauration (MySQL/MariaDB)

- `php artisan database:backup` crée un fichier `.sql.gz` sous `storage/app/private/backups` par défaut. Pour un stockage hors site S3-compatible (S3 ou Cloudflare R2), configurez `BACKUP_DISK=s3` et les variables `AWS_*`; la rétention de 14 jours s’applique au disque sélectionné.
- Le planificateur Laravel lance une sauvegarde à 02:00. Sur un serveur Linux, activez-le avec une tâche cron exécutant `php /chemin/vers/phamagest/artisan schedule:run` chaque minute.
- Une restauration est destructive et uniquement disponible dans le terminal interactif : `php artisan database:restore phamagest-AAAAMMJJ-HHMMSS.sql.gz`. L’archive est lue depuis le disque configuré; l’opérateur doit confirmer et une sauvegarde pré-restauration est créée automatiquement avant import.
- Les fichiers locaux ne protègent pas contre la panne ou la perte du serveur. Avant production, configurez un bucket hors site avec chiffrement côté fournisseur, des identifiants limités et une rétention, puis testez régulièrement la restauration sur une base isolée. Les archives restent des dumps SQL sensibles; ne les publiez jamais via `public/`.

### Déploiement et services externes restant à choisir

- Il n’y a pas encore d’hébergeur ni de domaine configuré. Un tunnel/sous-domaine gratuit peut servir à une démo temporaire seulement; il ne constitue pas un hébergement fiable ni une adresse de production. Un vrai HTTPS nécessite un domaine maîtrisé et un serveur public; Cloudflare peut fournir gratuitement un certificat TLS pour un domaine activé chez eux.
- Configurez un fournisseur SMTP dans les variables `MAIL_*` du serveur pour la vérification d’adresse et les notifications. Le mode local `MAIL_MAILER=log` n’envoie pas de vrais e-mails.
- Aucun paiement n’est intégré. Choisissez et ouvrez un compte auprès d’une passerelle compatible Cameroun avant de développer les webhooks de paiement.
- Le contrôle documentaire est une revue manuelle interne, pas une certification réglementaire. Faites valider la liste exacte des justificatifs par un conseiller local compétent avant d’accepter de vraies pharmacies ou entreprises.
- Avant toute ouverture publique : `APP_ENV=production`, `APP_DEBUG=false`, HTTPS, clé APP protégée, sauvegarde hors site testée, SMTP vérifié, limites d’upload, supervision et procédure d’incident. Exécutez aussi `composer audit` et corrigez les avis avant déploiement : le framework Laravel 11 actuellement verrouillé est signalé par des avis connus, dont un critique; ne l’exposez pas en l’état.

## Parcours métier type

1. **Magasinier** : Créer une commande → la **réceptionner** (stock augmenté, mouvement « entrée »)
2. **Vendeur** : Créer une vente en brouillon → **valider** (si stock OK, déduction + mouvement « sortie »)
3. **Vendeur / Admin** : Télécharger la **facture PDF** d'une vente validée

## Stack

- Laravel 11, Breeze (sessions), Spatie Permission, DomPDF, Tailwind CSS 3, Alpine.js, Vite
