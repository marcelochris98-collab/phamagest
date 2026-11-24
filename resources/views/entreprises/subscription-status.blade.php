<x-app-layout>
    <x-slot name="header">Abonnement</x-slot>

    <div class="mx-auto max-w-3xl rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <p class="text-sm text-slate-500">Entreprise</p>
        <h2 class="mt-1 text-xl font-semibold text-slate-900">{{ $activeEntreprise->nom }}</h2>
        <p class="mt-4 text-slate-700">
            Statut de l’abonnement :
            <span class="font-semibold">{{ match ($activeEntreprise->subscription_status) {
                'trial' => 'Période d’essai',
                'active' => 'Actif',
                'suspended' => 'Suspendu',
                'expired' => 'Expiré',
                default => 'En attente',
            } }}</span>
        </p>
        @if ($activeEntreprise->trial_ends_at)
            <p class="mt-2 text-sm text-slate-600">Fin de l’essai : {{ $activeEntreprise->trial_ends_at->format('d/m/Y') }}</p>
        @endif
        @if ($activeEntreprise->subscription_ends_at)
            <p class="mt-2 text-sm text-slate-600">Fin de l’abonnement : {{ $activeEntreprise->subscription_ends_at->format('d/m/Y') }}</p>
        @endif
        <p class="mt-5 text-sm text-slate-600">Contactez l’équipe PharmaGest pour connaître les modalités d’activation ou de renouvellement.</p>
    </div>
</x-app-layout>
