<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EntrepriseContextController extends Controller
{
    public function choose(Request $request): View
    {
        $entreprises = $request->user()->entreprises()->orderBy('nom')->get();

        abort_if($entreprises->isEmpty(), 403, 'Votre compte n’est associé à aucune entreprise.');

        return view('entreprises.choose', compact('entreprises'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'entreprise_id' => ['required', 'integer'],
        ]);

        $entreprise = $request->user()
            ->entreprises()
            ->whereKey($validated['entreprise_id'])
            ->first();

        abort_unless($entreprise, 403, 'Vous n’êtes pas membre de cette entreprise.');

        $request->session()->put('active_entreprise_id', $entreprise->getKey());

        return redirect()->route('dashboard')->with('success', 'Espace entreprise changé.');
    }
}
