<?php

namespace App\Http\Controllers;

use App\Enums\CommandeStatut;
use App\Http\Requests\CommandeRequest;
use App\Models\Commande;
use App\Models\Fournisseur;
use App\Models\Medicament;
use App\Services\CommandeReceptionService;
use App\Support\Permissions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CommandeController extends Controller
{
    public function __construct(
        protected CommandeReceptionService $receptionService
    ) {}

    public function index(): View
    {
        $this->authorizePermission(Permissions::COMMANDES_VIEW);

        $commandes = Commande::with('fournisseur')->withCount('items')
            ->orderByDesc('date_commande')
            ->paginate(15);

        return view('commandes.index', compact('commandes'));
    }

    public function create(): View
    {
        $this->authorizePermission(Permissions::COMMANDES_MANAGE);

        return view('commandes.create', [
            'fournisseurs' => Fournisseur::orderBy('nom')->get(),
            'medicaments' => Medicament::orderBy('nom')->get(['id', 'nom', 'quantite_stock']),
        ]);
    }

    public function store(CommandeRequest $request): RedirectResponse
    {
        $this->authorizePermission(Permissions::COMMANDES_MANAGE);

        $commande = DB::transaction(function () use ($request) {
            $commande = Commande::create([
                'fournisseur_id' => $request->validated('fournisseur_id'),
                'statut' => CommandeStatut::EnAttente,
                'date_commande' => $request->validated('date_commande'),
                'date_livraison_prevue' => $request->validated('date_livraison_prevue'),
            ]);

            foreach ($request->validated('items') as $item) {
                $commande->items()->create($item);
            }

            return $commande;
        });

        return redirect()->route('commandes.show', $commande)->with('success', 'Commande créée.');
    }

    public function show(Commande $commande): View
    {
        $this->authorizePermission(Permissions::COMMANDES_VIEW);

        $commande->load(['fournisseur', 'items.medicament']);

        return view('commandes.show', compact('commande'));
    }

    public function edit(Commande $commande): View|RedirectResponse
    {
        $this->authorizePermission(Permissions::COMMANDES_MANAGE);

        if ($commande->statut !== CommandeStatut::EnAttente) {
            return redirect()->route('commandes.show', $commande)
                ->with('error', 'Seules les commandes en attente sont modifiables.');
        }

        return view('commandes.edit', [
            'commande' => $commande->load('items.medicament'),
            'fournisseurs' => Fournisseur::orderBy('nom')->get(),
            'medicaments' => Medicament::orderBy('nom')->get(['id', 'nom', 'quantite_stock']),
        ]);
    }

    public function update(CommandeRequest $request, Commande $commande): RedirectResponse
    {
        $this->authorizePermission(Permissions::COMMANDES_MANAGE);

        if ($commande->statut !== CommandeStatut::EnAttente) {
            return back()->with('error', 'Commande non modifiable.');
        }

        DB::transaction(function () use ($request, $commande) {
            $commande->update([
                'fournisseur_id' => $request->validated('fournisseur_id'),
                'date_commande' => $request->validated('date_commande'),
                'date_livraison_prevue' => $request->validated('date_livraison_prevue'),
            ]);

            $commande->items()->delete();

            foreach ($request->validated('items') as $item) {
                $commande->items()->create($item);
            }
        });

        return redirect()->route('commandes.show', $commande)->with('success', 'Commande mise à jour.');
    }

    public function destroy(Commande $commande): RedirectResponse
    {
        $this->authorizePermission(Permissions::COMMANDES_MANAGE);

        if ($commande->statut === CommandeStatut::Livree) {
            return back()->with('error', 'Une commande livrée ne peut pas être supprimée.');
        }

        $commande->delete();

        return redirect()->route('commandes.index')->with('success', 'Commande supprimée.');
    }

    public function receive(Commande $commande): RedirectResponse
    {
        $this->authorizePermission(Permissions::COMMANDES_RECEIVE);

        try {
            $this->receptionService->receive($commande);
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('commandes.show', $commande)
            ->with('success', 'Commande réceptionnée. Le stock a été mis à jour.');
    }

    public function cancel(Commande $commande): RedirectResponse
    {
        $this->authorizePermission(Permissions::COMMANDES_MANAGE);

        if ($commande->statut !== CommandeStatut::EnAttente) {
            return back()->with('error', 'Seules les commandes en attente peuvent être annulées.');
        }

        $commande->update(['statut' => CommandeStatut::Annulee]);

        return redirect()->route('commandes.show', $commande)->with('success', 'Commande annulée.');
    }
}
