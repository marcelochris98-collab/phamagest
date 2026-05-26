<?php

namespace App\Http\Controllers;

use App\Http\Requests\MedicamentRequest;
use App\Models\Fournisseur;
use App\Models\Medicament;
use App\Support\Permissions;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MedicamentController extends Controller
{
    public function index(): View
    {
        $this->authorizePermission(Permissions::MEDICAMENTS_VIEW);

        $medicaments = Medicament::with('fournisseur')
            ->orderBy('nom')
            ->paginate(15);

        return view('medicaments.index', compact('medicaments'));
    }

    public function create(): View
    {
        $this->authorizePermission(Permissions::MEDICAMENTS_MANAGE);

        return view('medicaments.create', ['fournisseurs' => Fournisseur::orderBy('nom')->get()]);
    }

    public function store(MedicamentRequest $request): RedirectResponse
    {
        $this->authorizePermission(Permissions::MEDICAMENTS_MANAGE);

        Medicament::create($request->validated());

        return redirect()->route('medicaments.index')->with('success', 'Médicament ajouté au catalogue.');
    }

    public function edit(Medicament $medicament): View
    {
        $this->authorizePermission(Permissions::MEDICAMENTS_MANAGE);

        return view('medicaments.edit', [
            'medicament' => $medicament,
            'fournisseurs' => Fournisseur::orderBy('nom')->get(),
        ]);
    }

    public function update(MedicamentRequest $request, Medicament $medicament): RedirectResponse
    {
        $this->authorizePermission(Permissions::MEDICAMENTS_MANAGE);

        $medicament->update($request->validated());

        return redirect()->route('medicaments.index')->with('success', 'Médicament mis à jour.');
    }

    public function destroy(Medicament $medicament): RedirectResponse
    {
        $this->authorizePermission(Permissions::MEDICAMENTS_MANAGE);

        if ($medicament->commandeItems()->exists() || $medicament->venteItems()->exists()) {
            return back()->with('error', 'Ce médicament est référencé dans des commandes ou ventes.');
        }

        $medicament->delete();

        return redirect()->route('medicaments.index')->with('success', 'Médicament supprimé.');
    }
}
