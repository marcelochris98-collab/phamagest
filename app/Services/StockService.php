<?php

namespace App\Services;

use App\Enums\MouvementStockType;
use App\Models\Medicament;
use App\Models\MouvementStock;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function entree(Medicament $medicament, int $quantite, string $motif): void
    {
        DB::transaction(function () use ($medicament, $quantite, $motif) {
            $medicament->increment('quantite_stock', $quantite);

            MouvementStock::create([
                'medicament_id' => $medicament->id,
                'type' => MouvementStockType::Entree,
                'quantite' => $quantite,
                'motif' => $motif,
                'date' => now(),
            ]);
        });
    }

    public function sortie(Medicament $medicament, int $quantite, string $motif): bool
    {
        if ($medicament->quantite_stock < $quantite) {
            return false;
        }

        DB::transaction(function () use ($medicament, $quantite, $motif) {
            $medicament->decrement('quantite_stock', $quantite);

            MouvementStock::create([
                'medicament_id' => $medicament->id,
                'type' => MouvementStockType::Sortie,
                'quantite' => $quantite,
                'motif' => $motif,
                'date' => now(),
            ]);
        });

        return true;
    }
}
