<div class="grid gap-6 sm:grid-cols-2">
    <div>
        <label class="block text-sm font-medium text-slate-700">Nom</label>
        <input type="text" name="nom" value="{{ old('nom', isset($fournisseur) ? $fournisseur->nom : '') }}" required class="mt-2 w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" />
        <x-input-error :messages="$errors->get('nom')" class="mt-1" />
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700">Pays</label>
        <input type="text" name="pays" value="{{ old('pays', isset($fournisseur) ? $fournisseur->pays : '') }}" required class="mt-2 w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" />
        <x-input-error :messages="$errors->get('pays')" class="mt-1" />
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700">Contact</label>
        <input type="text" name="contact" value="{{ old('contact', isset($fournisseur) ? $fournisseur->contact : '') }}" class="mt-2 w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" />
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700">E-mail</label>
        <input type="email" name="email" value="{{ old('email', isset($fournisseur) ? $fournisseur->email : '') }}" class="mt-2 w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" />
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700">Téléphone</label>
        <input type="text" name="telephone" value="{{ old('telephone', isset($fournisseur) ? $fournisseur->telephone : '') }}" class="mt-2 w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" />
    </div>
</div>
