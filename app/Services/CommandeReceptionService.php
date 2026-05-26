<?php

namespace App\Services;

use App\Enums\CommandeStatut;
use App\Models\Commande;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class CommandeReceptionService
{
    public function __construct(
        protected StockService $stockService
    ) {}

    public function receive(Commande $commande): void
    {
        if ($commande->statut !== CommandeStatut::EnAttente) {
            throw new InvalidArgumentException('Seules les commandes en attente peuvent être réceptionnées.');
        }

        DB::transaction(function () use ($commande) {
            $commande->load('items.medicament');

            foreach ($commande->items as $item) {
                $medicament = $item->medicament->fresh();
                $this->stockService->entree(
                    $medicament,
                    $item->quantite,
                    sprintf('Réception commande #%d', $commande->id)
                );
            }

            $commande->update(['statut' => CommandeStatut::Livree]);
        });
    }
}
