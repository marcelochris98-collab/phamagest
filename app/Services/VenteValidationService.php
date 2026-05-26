<?php

namespace App\Services;

use App\Enums\VenteStatut;
use App\Models\Vente;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class VenteValidationService
{
    public function __construct(
        protected StockService $stockService
    ) {}

    /**
     * @return array{valid: bool, errors: array<int, string>}
     */
    public function validateStock(Vente $vente): array
    {
        $vente->load('items.medicament');
        $errors = [];

        foreach ($vente->items as $item) {
            $medicament = $item->medicament;

            if ($medicament->quantite_stock < $item->quantite) {
                $errors[] = sprintf(
                    'Stock insuffisant pour « %s » : disponible %d, demandé %d.',
                    $medicament->nom,
                    $medicament->quantite_stock,
                    $item->quantite
                );
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
        ];
    }

    public function valider(Vente $vente): array
    {
        if ($vente->statut !== VenteStatut::Brouillon) {
            throw new InvalidArgumentException('Seules les ventes en brouillon peuvent être validées.');
        }

        $vente->load('items.medicament');

        if ($vente->items->isEmpty()) {
            return ['valid' => false, 'errors' => ['Ajoutez au moins un article à la vente.']];
        }

        $check = $this->validateStock($vente);

        if (! $check['valid']) {
            return $check;
        }

        DB::transaction(function () use ($vente) {
            foreach ($vente->items as $item) {
                $medicament = $item->medicament->fresh();
                $this->stockService->sortie(
                    $medicament,
                    $item->quantite,
                    sprintf('Vente #%d validée', $vente->id)
                );
            }

            $vente->update([
                'statut' => VenteStatut::Validee,
                'date_vente' => now(),
            ]);
        });

        return ['valid' => true, 'errors' => []];
    }
}

