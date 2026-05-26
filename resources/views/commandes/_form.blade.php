<div class="grid gap-6 sm:grid-cols-2 mb-8">
    <div>
        <label class="block text-sm font-medium text-slate-700">Fournisseur</label>
        <select name="fournisseur_id" required class="mt-2 w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            @foreach ($fournisseurs as $f)
                <option value="{{ $f->id }}" @selected(old('fournisseur_id', isset($commande) ? $commande->fournisseur_id : '') == $f->id)>{{ $f->nom }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700">Date commande</label>
        <input type="date" name="date_commande" value="{{ old('date_commande', isset($commande) ? $commande->date_commande->format('Y-m-d') : now()->format('Y-m-d')) }}" required class="mt-2 w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" />
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700">Livraison prévue</label>
        <input type="date" name="date_livraison_prevue" value="{{ old('date_livraison_prevue', isset($commande) && $commande->date_livraison_prevue ? $commande->date_livraison_prevue->format('Y-m-d') : '') }}" class="mt-2 w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" />
    </div>
</div>

<h3 class="text-sm font-semibold text-slate-700 mb-4">Lignes de commande</h3>
@php
    $existingItems = isset($commande) ? $commande->items->map(fn ($i) => [
        'medicament_id' => $i->medicament_id,
        'quantite' => $i->quantite,
        'prix_unitaire' => (float) $i->prix_unitaire,
    ])->toArray() : [];
@endphp
<x-pharma.line-items :medicaments="$medicaments" :items="$existingItems" />
