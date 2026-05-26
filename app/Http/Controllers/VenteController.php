<?php

namespace App\Http\Controllers;

use App\Enums\VenteStatut;
use App\Http\Requests\VenteRequest;
use App\Models\Client;
use App\Models\Medicament;
use App\Models\Vente;
use App\Services\VenteValidationService;
use App\Support\Permissions;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class VenteController extends Controller
{
    public function __construct(
        protected VenteValidationService $validationService
    ) {}

    public function index(): View
    {
        $this->authorizePermission(Permissions::VENTES_VIEW);

        $ventes = Vente::with(['client', 'user'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('ventes.index', compact('ventes'));
    }

    public function create(): View
    {
        $this->authorizePermission(Permissions::VENTES_CREATE);

        return view('ventes.create', [
            'clients' => Client::orderBy('nom')->get(),
            'medicaments' => Medicament::orderBy('nom')->get(['id', 'nom', 'quantite_stock', 'dosage']),
        ]);
    }

    public function store(VenteRequest $request): RedirectResponse
    {
        $this->authorizePermission(Permissions::VENTES_CREATE);

        $vente = DB::transaction(function () use ($request) {
            $total = collect($request->validated('items'))->sum(
                fn ($item) => $item['quantite'] * $item['prix_unitaire']
            );

            $vente = Vente::create([
                'client_id' => $request->validated('client_id'),
                'user_id' => auth()->id(),
                'total' => $total,
                'statut' => VenteStatut::Brouillon,
                'date_vente' => now(),
            ]);

            foreach ($request->validated('items') as $item) {
                $vente->items()->create($item);
            }

            return $vente;
        });

        return redirect()->route('ventes.show', $vente)->with('success', 'Vente enregistrée en brouillon.');
    }

    public function show(Vente $vente): View
    {
        $this->authorizePermission(Permissions::VENTES_VIEW);

        $vente->load(['client', 'user', 'items.medicament']);

        return view('ventes.show', compact('vente'));
    }

    public function edit(Vente $vente): View|RedirectResponse
    {
        $this->authorizePermission(Permissions::VENTES_CREATE);

        if ($vente->statut !== VenteStatut::Brouillon) {
            return redirect()->route('ventes.show', $vente)
                ->with('error', 'Seules les ventes en brouillon sont modifiables.');
        }

        return view('ventes.edit', [
            'vente' => $vente->load('items.medicament'),
            'clients' => Client::orderBy('nom')->get(),
            'medicaments' => Medicament::orderBy('nom')->get(['id', 'nom', 'quantite_stock', 'dosage']),
        ]);
    }

    public function update(VenteRequest $request, Vente $vente): RedirectResponse
    {
        $this->authorizePermission(Permissions::VENTES_CREATE);

        if ($vente->statut !== VenteStatut::Brouillon) {
            return back()->with('error', 'Vente non modifiable.');
        }

        DB::transaction(function () use ($request, $vente) {
            $total = collect($request->validated('items'))->sum(
                fn ($item) => $item['quantite'] * $item['prix_unitaire']
            );

            $vente->update([
                'client_id' => $request->validated('client_id'),
                'total' => $total,
            ]);

            $vente->items()->delete();

            foreach ($request->validated('items') as $item) {
                $vente->items()->create($item);
            }
        });

        return redirect()->route('ventes.show', $vente)->with('success', 'Vente mise à jour.');
    }

    public function destroy(Vente $vente): RedirectResponse
    {
        $this->authorizePermission(Permissions::VENTES_CREATE);

        if ($vente->statut !== VenteStatut::Brouillon) {
            return back()->with('error', 'Seules les ventes en brouillon peuvent être supprimées.');
        }

        $vente->delete();

        return redirect()->route('ventes.index')->with('success', 'Vente supprimée.');
    }

    public function validateVente(Vente $vente): RedirectResponse
    {
        $this->authorizePermission(Permissions::VENTES_VALIDATE);

        try {
            $result = $this->validationService->valider($vente);
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }

        if (! $result['valid']) {
            return back()
                ->with('error', 'Validation impossible : stock insuffisant.')
                ->with('stock_errors', $result['errors']);
        }

        return redirect()->route('ventes.show', $vente->fresh())
            ->with('success', 'Vente validée. Le stock a été déduit.');
    }

    public function invoice(Vente $vente): Response|RedirectResponse
    {
        $this->authorizePermission(Permissions::VENTES_INVOICE);

        if ($vente->statut !== VenteStatut::Validee) {
            return back()->with('error', 'La facture PDF est disponible uniquement pour les ventes validées.');
        }

        $vente->load(['client', 'user', 'items.medicament']);

        $pdf = Pdf::loadView('ventes.invoice-pdf', compact('vente'))
            ->setPaper('a4');

        return $pdf->download(sprintf('facture-phamagest-%d.pdf', $vente->id));
    }
}
