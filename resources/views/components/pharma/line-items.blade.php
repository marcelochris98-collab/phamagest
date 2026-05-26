@props([
    'medicaments' => [],
    'items' => [],
    'name' => 'items',
])

@php
    $initialLines = old($name, $items ?: [['medicament_id' => '', 'quantite' => 1, 'prix_unitaire' => 0]]);
    if (empty($initialLines)) {
        $initialLines = [['medicament_id' => '', 'quantite' => 1, 'prix_unitaire' => 0]];
    }
    $medicamentsJson = $medicaments->map(function ($m) {
        return [
            'id' => $m->id,
            'nom' => $m->nom,
            'stock' => $m->quantite_stock ?? 0,
            'dosage' => $m->dosage ?? '',
        ];
    })->values();
@endphp

<div
    x-data="{
        lines: @js($initialLines),
        medicaments: @js($medicamentsJson),
        addLine() {
            this.lines.push({ medicament_id: '', quantite: 1, prix_unitaire: 0 });
        },
        removeLine(index) {
            if (this.lines.length > 1) this.lines.splice(index, 1);
        },
        stockFor(id) {
            const m = this.medicaments.find(x => x.id == id);
            return m ? m.stock : '—';
        }
    }"
    class="space-y-4"
>
    <template x-for="(line, index) in lines" :key="index">
        <div class="grid gap-4 rounded-xl border border-slate-100 bg-slate-50/50 p-4 sm:grid-cols-12 sm:items-end">
            <div class="sm:col-span-5">
                <label class="block text-xs font-medium text-slate-500 mb-1">Médicament</label>
                <select
                    :name="`{{ $name }}[${index}][medicament_id]`"
                    x-model="line.medicament_id"
                    required
                    class="w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                >
                    <option value="">— Sélectionner —</option>
                    <template x-for="med in medicaments" :key="med.id">
                        <option :value="med.id" x-text="med.nom + (med.dosage ? ' (' + med.dosage + ')' : '')"></option>
                    </template>
                </select>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-medium text-slate-500 mb-1">Quantité</label>
                <input
                    type="number"
                    min="1"
                    :name="`{{ $name }}[${index}][quantite]`"
                    x-model.number="line.quantite"
                    required
                    class="w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                />
            </div>
            <div class="sm:col-span-2">
                <label class="block text-xs font-medium text-slate-500 mb-1">Prix unit. (FCFA)</label>
                <input
                    type="number"
                    min="0"
                    step="0.01"
                    :name="`{{ $name }}[${index}][prix_unitaire]`"
                    x-model.number="line.prix_unitaire"
                    required
                    class="w-full rounded-xl border-slate-200 text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
                />
            </div>
            <div class="sm:col-span-2">
                <p class="text-xs text-slate-400">Stock dispo.</p>
                <p class="text-sm font-medium text-slate-700" x-text="stockFor(line.medicament_id)"></p>
            </div>
            <div class="sm:col-span-1 flex justify-end">
                <button
                    type="button"
                    @click="removeLine(index)"
                    class="rounded-lg p-2 text-slate-400 transition-all duration-300 hover:bg-red-50 hover:text-red-600"
                    title="Supprimer la ligne"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
            </div>
        </div>
    </template>

    <button
        type="button"
        @click="addLine()"
        class="inline-flex items-center gap-2 rounded-xl border border-dashed border-emerald-300 px-4 py-2 text-sm font-medium text-emerald-700 transition-all duration-300 hover:bg-emerald-50"
    >
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Ajouter une ligne
    </button>
</div>
