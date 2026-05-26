<?php

namespace App\Http\Controllers;

use App\Models\Medicament;
use App\Models\MouvementStock;
use App\Support\Permissions;
use Illuminate\View\View;

class StockController extends Controller
{
    public function index(): View
    {
        $this->authorizePermission(Permissions::STOCK_VIEW);

        $medicaments = Medicament::with('fournisseur')
            ->orderBy('nom')
            ->paginate(20);

        return view('stock.index', compact('medicaments'));
    }

    public function alertes(): View
    {
        $this->authorizePermission(Permissions::STOCK_ALERTS);

        $stockCritique = Medicament::with('fournisseur')
            ->whereColumn('quantite_stock', '<=', 'seuil_alerte')
            ->orderBy('quantite_stock')
            ->get();

        $peremptionProche = Medicament::with('fournisseur')
            ->whereNotNull('date_peremption')
            ->where('date_peremption', '<=', now()->addMonths(3))
            ->orderBy('date_peremption')
            ->get();

        return view('stock.alertes', compact('stockCritique', 'peremptionProche'));
    }

    public function mouvements(): View
    {
        $this->authorizePermission(Permissions::STOCK_MOVEMENTS);

        $mouvements = MouvementStock::with('medicament')
            ->orderByDesc('date')
            ->paginate(25);

        return view('stock.mouvements', compact('mouvements'));
    }
}
