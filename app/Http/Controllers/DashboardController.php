<?php

namespace App\Http\Controllers;

use App\Enums\CommandeStatut;
use App\Enums\VenteStatut;
use App\Models\Client;
use App\Models\Commande;
use App\Models\Medicament;
use App\Models\MouvementStock;
use App\Models\Vente;
use App\Support\Permissions;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $alertesStock = Medicament::whereColumn('quantite_stock', '<=', 'seuil_alerte')->count();
        $peremptionProche = Medicament::whereNotNull('date_peremption')
            ->where('date_peremption', '<=', now()->addMonths(3))
            ->count();

        return view('dashboard', compact('alertesStock', 'peremptionProche'));
    }

    public function kpis(): View
    {
        $this->authorizeKpi();

        $stats = [
            'medicaments' => Medicament::count(),
            'stock_total' => Medicament::sum('quantite_stock'),
            'alertes_stock' => Medicament::whereColumn('quantite_stock', '<=', 'seuil_alerte')->count(),
            'peremption_proche' => Medicament::whereNotNull('date_peremption')
                ->where('date_peremption', '<=', now()->addMonths(3))->count(),
            'clients' => Client::count(),
            'commandes_en_attente' => Commande::where('statut', CommandeStatut::EnAttente)->count(),
            'ventes_mois' => Vente::where('statut', VenteStatut::Validee)
                ->whereMonth('date_vente', now()->month)
                ->whereYear('date_vente', now()->year)
                ->count(),
            'ca_mois' => Vente::where('statut', VenteStatut::Validee)
                ->whereMonth('date_vente', now()->month)
                ->whereYear('date_vente', now()->year)
                ->sum('total'),
            'mouvements_recents' => MouvementStock::with('medicament')->latest('date')->limit(5)->get(),
            'top_ventes' => Vente::with('client')
                ->where('statut', VenteStatut::Validee)
                ->orderByDesc('total')
                ->limit(5)
                ->get(),
        ];

        return view('kpis.index', compact('stats'));
    }

    protected function authorizeKpi(): void
    {
        abort_unless(auth()->user()?->can(Permissions::KPIS_VIEW), 403);
    }
}
