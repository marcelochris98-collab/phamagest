<?php

namespace App\Http\Controllers;

use App\Enums\CommandeStatut;
use App\Enums\VenteStatut;
use App\Models\Client;
use App\Models\Commande;
use App\Models\Medicament;
use App\Models\MouvementStock;
use App\Models\SubscriptionPlan;
use App\Models\Vente;
use App\Support\CurrentEntreprise;
use App\Support\Permissions;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $stats = [];
        $recentActivity = collect();

        if ($user->can(Permissions::MEDICAMENTS_VIEW)) {
            $stats['products'] = Medicament::count();
        }

        if ($user->can(Permissions::STOCK_VIEW)) {
            $stats['stockTotal'] = Medicament::sum('quantite_stock');
        }

        if ($user->can(Permissions::STOCK_ALERTS)) {
            $stats['stockAlerts'] = Medicament::whereColumn('quantite_stock', '<=', 'seuil_alerte')->count();
            $stats['expiringSoon'] = Medicament::whereNotNull('date_peremption')
                ->whereDate('date_peremption', '>=', now()->toDateString())
                ->whereDate('date_peremption', '<=', now()->addMonths(3)->toDateString())
                ->count();
        }

        if ($user->can(Permissions::COMMANDES_VIEW)) {
            $stats['pendingOrders'] = Commande::where('statut', CommandeStatut::EnAttente)->count();

            foreach (Commande::with('fournisseur')->latest('date_commande')->limit(5)->get() as $commande) {
                $recentActivity->push([
                    'date' => $commande->date_commande,
                    'title' => 'Commande fournisseur',
                    'description' => $commande->fournisseur?->nom ?? 'Fournisseur supprimé',
                    'detail' => $commande->statut->label(),
                ]);
            }
        }

        if ($user->can(Permissions::VENTES_VIEW)) {
            $salesThisMonth = Vente::where('statut', VenteStatut::Validee)
                ->whereMonth('date_vente', now()->month)
                ->whereYear('date_vente', now()->year);
            $stats['salesThisMonth'] = (clone $salesThisMonth)->count();
            $stats['revenueThisMonth'] = (clone $salesThisMonth)->sum('total');

            foreach (Vente::with('client')
                ->where('statut', VenteStatut::Validee)
                ->latest('date_vente')
                ->limit(5)
                ->get() as $vente) {
                $recentActivity->push([
                    'date' => $vente->date_vente,
                    'title' => 'Vente validée',
                    'description' => $vente->client?->nom ?? 'Client supprimé',
                    'detail' => number_format($vente->total, 0, ',', ' ').' FCFA',
                ]);
            }
        }

        if ($user->can(Permissions::STOCK_MOVEMENTS)) {
            foreach (MouvementStock::with('medicament')->latest('date')->limit(5)->get() as $movement) {
                $recentActivity->push([
                    'date' => $movement->date,
                    'title' => $movement->type->label(),
                    'description' => $movement->medicament?->nom ?? 'Produit supprimé',
                    'detail' => number_format($movement->quantite).' unité(s)',
                ]);
            }
        }

        $recentActivity = $recentActivity
            ->sortByDesc('date')
            ->take(7)
            ->values();

        return view('dashboard', compact('stats', 'recentActivity'));
    }

    public function kpis(): View
    {
        $this->authorizeKpi();
        abort_unless(
            SubscriptionPlan::where('slug', app(CurrentEntreprise::class)->get()->subscription_plan)
                ->firstOrFail()
                ->kpis_enabled,
            403,
            'Les indicateurs KPI ne sont pas inclus dans le forfait de cette entreprise.'
        );

        $stats = [
            'medicaments' => Medicament::count(),
            'stock_total' => Medicament::sum('quantite_stock'),
            'alertes_stock' => Medicament::whereColumn('quantite_stock', '<=', 'seuil_alerte')->count(),
            'peremption_proche' => Medicament::whereNotNull('date_peremption')
                ->whereDate('date_peremption', '>=', now()->toDateString())
                ->whereDate('date_peremption', '<=', now()->addMonths(3)->toDateString())
                ->count(),
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
