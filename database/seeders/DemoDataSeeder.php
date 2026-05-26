<?php

namespace Database\Seeders;

use App\Enums\ClientType;
use App\Enums\CommandeStatut;
use App\Models\Client;
use App\Models\Commande;
use App\Models\Fournisseur;
use App\Models\Medicament;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $fournisseur = Fournisseur::create([
            'nom' => 'Laboratoires Centrafrique',
            'pays' => 'France',
            'contact' => 'Service export',
            'email' => 'export@lab-centrafrique.fr',
            'telephone' => '+33 1 00 00 00 00',
        ]);

        $med1 = Medicament::create([
            'nom' => 'Paracétamol 500mg',
            'dci' => 'Paracétamol',
            'forme' => 'Comprimé',
            'dosage' => '500 mg',
            'quantite_stock' => 500,
            'seuil_alerte' => 100,
            'date_peremption' => now()->addYear(),
            'fournisseur_id' => $fournisseur->id,
        ]);

        $med2 = Medicament::create([
            'nom' => 'Amoxicilline 1g',
            'dci' => 'Amoxicilline',
            'forme' => 'Gélule',
            'dosage' => '1 g',
            'quantite_stock' => 45,
            'seuil_alerte' => 50,
            'date_peremption' => now()->addMonths(2),
            'fournisseur_id' => $fournisseur->id,
        ]);

        Medicament::create([
            'nom' => 'Ibuprofène 400mg',
            'dci' => 'Ibuprofène',
            'forme' => 'Comprimé',
            'dosage' => '400 mg',
            'quantite_stock' => 200,
            'seuil_alerte' => 40,
            'date_peremption' => now()->addMonths(8),
            'fournisseur_id' => $fournisseur->id,
        ]);

        Client::create([
            'nom' => 'Pharmacie du Centre',
            'type' => ClientType::Pharmacie,
            'adresse' => 'Akwa, Douala',
            'telephone' => '+237 6 00 00 00 01',
        ]);

        Client::create([
            'nom' => 'Clinique La Paix',
            'type' => ClientType::Clinique,
            'adresse' => 'Bonanjo, Douala',
            'telephone' => '+237 6 00 00 00 02',
        ]);

        $commande = Commande::create([
            'fournisseur_id' => $fournisseur->id,
            'statut' => CommandeStatut::EnAttente,
            'date_commande' => now(),
            'date_livraison_prevue' => now()->addDays(14),
        ]);

        $commande->items()->create([
            'medicament_id' => $med1->id,
            'quantite' => 200,
            'prix_unitaire' => 150,
        ]);

        $commande->items()->create([
            'medicament_id' => $med2->id,
            'quantite' => 100,
            'prix_unitaire' => 850,
        ]);

        $magasinier = User::firstOrCreate(
            ['email' => 'magasinier@phamagest.cm'],
            [
                'name' => 'Jean Magasinier',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $magasinier->syncRoles(['magasinier']);

        $vendeur = User::firstOrCreate(
            ['email' => 'vendeur@phamagest.cm'],
            [
                'name' => 'Marie Vendeur',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $vendeur->syncRoles(['vendeur']);
    }
}
