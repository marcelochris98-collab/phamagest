@extends('layouts.platform')

@section('title', 'Forfaits')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-semibold">Forfaits et limites</h1>
    <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">Les changements s’appliquent immédiatement aux entreprises associées au forfait. Une limite plus basse n’efface aucune donnée : elle empêche uniquement les créations au-delà de la nouvelle limite.</p>
</div>

<div class="space-y-5">
    @foreach ($plans as $plan)
        <form method="POST" action="{{ route('platform.plans.update', $plan) }}" class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf
            @method('PATCH')
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-6">
                <div>
                    <label for="name-{{ $plan->id }}" class="block text-sm font-medium text-slate-700">Nom du forfait</label>
                    <input id="name-{{ $plan->id }}" name="name" value="{{ old('name', $plan->name) }}" required maxlength="50" class="mt-1 block w-full rounded-lg border-slate-300 text-sm">
                </div>
                <div>
                    <label for="price-{{ $plan->id }}" class="block text-sm font-medium text-slate-700">Prix mensuel (FCFA)</label>
                    <input id="price-{{ $plan->id }}" name="price_fcfa" type="number" min="0" value="{{ old('price_fcfa', $plan->price_fcfa) }}" placeholder="À définir" class="mt-1 block w-full rounded-lg border-slate-300 text-sm">
                </div>
                <div>
                    <label for="users-{{ $plan->id }}" class="block text-sm font-medium text-slate-700">Utilisateurs max.</label>
                    <input id="users-{{ $plan->id }}" name="user_limit" type="number" min="1" value="{{ old('user_limit', $plan->user_limit) }}" placeholder="Sans limite" class="mt-1 block w-full rounded-lg border-slate-300 text-sm">
                </div>
                <div>
                    <label for="products-{{ $plan->id }}" class="block text-sm font-medium text-slate-700">Produits max.</label>
                    <input id="products-{{ $plan->id }}" name="product_limit" type="number" min="1" value="{{ old('product_limit', $plan->product_limit) }}" placeholder="Sans limite" class="mt-1 block w-full rounded-lg border-slate-300 text-sm">
                </div>
                <label class="flex items-center gap-2 self-end rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700">
                    <input type="checkbox" name="kpis_enabled" value="1" @checked(old('kpis_enabled', $plan->kpis_enabled)) class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-600">
                    Accès aux KPI
                </label>
                <label class="flex items-center gap-2 self-end rounded-lg border border-slate-200 px-3 py-2.5 text-sm text-slate-700">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $plan->is_active)) class="rounded border-slate-300 text-emerald-700 focus:ring-emerald-600">
                    Forfait disponible
                </label>
            </div>
            <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4">
                <p class="text-xs text-slate-500">Identifiant : <code>{{ $plan->slug }}</code> · Prix affiché : {{ $plan->price_fcfa === null ? 'À définir' : number_format($plan->price_fcfa, 0, ',', ' ').' FCFA/mois' }}</p>
                <button class="rounded-lg bg-emerald-700 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-800">Enregistrer les limites</button>
            </div>
        </form>
    @endforeach
</div>
@endsection
