<div class="mb-6">
    <label class="block text-sm font-medium text-slate-700">Client grossiste</label>
    <select name="client_id" required class="mt-2 w-full max-w-md rounded-xl border-slate-200 shadow-sm focus:border-emerald-500 focus:ring-emerald-500">
        @foreach ($clients as $c)
            <option value="{{ $c->id }}" @selected(old('client_id', isset($vente) ? $vente->client_id : '') == $c->id)>{{ $c->nom }} ({{ $c->type->label() }})</option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('client_id')" class="mt-1" />
</div>

<h3 class="text-sm font-semibold text-slate-700 mb-4">Articles</h3>
@php
    $existingItems = isset($vente) ? $vente->items->map(fn ($i) => [
        'medicament_id' => $i->medicament_id,
        'quantite' => $i->quantite,
        'prix_unitaire' => (float) $i->prix_unitaire,
    ])->toArray() : [];
@endphp
<x-pharma.line-items :medicaments="$medicaments" :items="$existingItems" />
