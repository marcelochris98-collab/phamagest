<?php

namespace App\Http\Controllers;

use App\Http\Requests\FournisseurRequest;
use App\Models\Fournisseur;
use App\Support\Permissions;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FournisseurController extends Controller
{
    public function index(): View
    {
        $this->authorizePermission(Permissions::FOURNISSEURS_VIEW);

        $fournisseurs = Fournisseur::withCount('medicaments')->orderBy('nom')->paginate(15);

        return view('fournisseurs.index', compact('fournisseurs'));
    }

    public function create(): View
    {
        $this->authorizePermission(Permissions::FOURNISSEURS_MANAGE);

        return view('fournisseurs.create');
    }

    public function store(FournisseurRequest $request): RedirectResponse
    {
        $this->authorizePermission(Permissions::FOURNISSEURS_MANAGE);

        Fournisseur::create($request->validated());

        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur créé avec succès.');
    }

    public function edit(Fournisseur $fournisseur): View
    {
        $this->authorizePermission(Permissions::FOURNISSEURS_MANAGE);

        return view('fournisseurs.edit', compact('fournisseur'));
    }

    public function update(FournisseurRequest $request, Fournisseur $fournisseur): RedirectResponse
    {
        $this->authorizePermission(Permissions::FOURNISSEURS_MANAGE);

        $fournisseur->update($request->validated());

        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur mis à jour.');
    }

    public function destroy(Fournisseur $fournisseur): RedirectResponse
    {
        $this->authorizePermission(Permissions::FOURNISSEURS_MANAGE);

        if ($fournisseur->medicaments()->exists() || $fournisseur->commandes()->exists()) {
            return back()->with('error', 'Impossible de supprimer : ce fournisseur est lié à des médicaments ou commandes.');
        }

        $fournisseur->delete();

        return redirect()->route('fournisseurs.index')->with('success', 'Fournisseur supprimé.');
    }
}
