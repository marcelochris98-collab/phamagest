<div class="grid gap-6 sm:grid-cols-2">
    <div>
        <label class="block text-sm font-medium text-slate-700">Nom</label>
        <input type="text" name="nom" value="{{ old('nom', isset($client) ? $client->nom : '') }}" required class="mt-2 w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" />
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700">Type</label>
        <select name="type" required class="mt-2 w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
            <option value="pharmacie" @selected(old('type', isset($client) ? $client->type->value : 'pharmacie') === 'pharmacie')>Pharmacie</option>
            <option value="clinique" @selected(old('type', isset($client) ? $client->type->value : '') === 'clinique')>Clinique</option>
        </select>
    </div>
    <div class="sm:col-span-2">
        <label class="block text-sm font-medium text-slate-700">Adresse</label>
        <textarea name="adresse" rows="2" class="mt-2 w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">{{ old('adresse', isset($client) ? $client->adresse : '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700">Téléphone</label>
        <input type="text" name="telephone" value="{{ old('telephone', isset($client) ? $client->telephone : '') }}" class="mt-2 w-full rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500" />
    </div>
</div>
