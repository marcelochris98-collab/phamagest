<div class="grid gap-6 sm:grid-cols-2">
    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-slate-700">Nom commercial</label>
        <input type="text" name="nom" value="{{ old('nom', isset($medicament) ? $medicament->nom : '') }}" required class="mt-2 w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" />
    </div>
    <div><label class="block text-sm font-medium text-slate-700">DCI</label><input type="text" name="dci" value="{{ old('dci', isset($medicament) ? $medicament->dci : '') }}" class="mt-2 w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" /></div>
    <div><label class="block text-sm font-medium text-slate-700">Forme</label><input type="text" name="forme" value="{{ old('forme', isset($medicament) ? $medicament->forme : '') }}" class="mt-2 w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" /></div>
    <div><label class="block text-sm font-medium text-slate-700">Dosage</label><input type="text" name="dosage" value="{{ old('dosage', isset($medicament) ? $medicament->dosage : '') }}" class="mt-2 w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" /></div>
    <div><label class="block text-sm font-medium text-slate-700">Quantité en stock</label><input type="number" name="quantite_stock" min="0" value="{{ old('quantite_stock', isset($medicament) ? $medicament->quantite_stock : 0) }}" required class="mt-2 w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" /></div>
    <div><label class="block text-sm font-medium text-slate-700">Seuil d'alerte</label><input type="number" name="seuil_alerte" min="0" value="{{ old('seuil_alerte', isset($medicament) ? $medicament->seuil_alerte : 10) }}" required class="mt-2 w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" /></div>
    <div><label class="block text-sm font-medium text-slate-700">Date de péremption</label><input type="date" name="date_peremption" value="{{ old('date_peremption', isset($medicament) && $medicament->date_peremption ? $medicament->date_peremption->format('Y-m-d') : '') }}" class="mt-2 w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" /></div>
    <div>
        <label class="block text-sm font-medium text-slate-700">Fournisseur</label>
        <select name="fournisseur_id" class="mt-2 w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            <option value="">— Aucun —</option>
            @foreach ($fournisseurs as $f)
                <option value="{{ $f->id }}" @selected(old('fournisseur_id', isset($medicament) ? $medicament->fournisseur_id : '') == $f->id)>{{ $f->nom }}</option>
            @endforeach
        </select>
    </div>
</div>
