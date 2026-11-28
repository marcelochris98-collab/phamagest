<x-app-layout>
    <x-slot name="header">Forfait et abonnement</x-slot>

    @php
        $statusLabel = match ($activeEntreprise->subscription_status) {
            'trial' => 'Période d’essai',
            'active' => 'Actif',
            'suspended' => 'Suspendu',
            'expired' => 'Expiré',
            default => 'En attente',
        };
        $deadline = $activeEntreprise->subscription_status === 'trial'
            ? $activeEntreprise->trial_ends_at
            : $activeEntreprise->subscription_ends_at;
    @endphp

    <div class="mx-auto max-w-5xl space-y-8">
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-500">Entreprise</p>
                    <h2 class="mt-1 text-xl font-semibold text-slate-900">{{ $activeEntreprise->nom }}</h2>
                    <p class="mt-4 text-sm text-slate-700">Statut : <span class="font-semibold">{{ $statusLabel }}</span></p>
                    <p class="mt-2 text-sm text-slate-700">Forfait : <span class="font-semibold">{{ $activeEntreprise->subscriptionPlan?->name ?? 'Non défini' }}</span></p>
                    @if ($deadline)
                        <p class="mt-2 text-sm text-slate-600">
                            {{ $activeEntreprise->subscription_status === 'trial' ? 'Fin de l’essai' : 'Fin de l’abonnement' }} :
                            <strong>{{ $deadline->format('d/m/Y à H:i') }}</strong>
                        </p>
                    @endif
                </div>
                <span class="rounded-full {{ $activeEntreprise->subscription_status === 'active' ? 'bg-emerald-100 text-emerald-800' : ($activeEntreprise->subscription_status === 'trial' ? 'bg-sky-100 text-sky-800' : 'bg-amber-100 text-amber-900') }} px-3 py-1.5 text-xs font-semibold">
                    {{ $statusLabel }}
                </span>
            </div>

            @if ($activeEntreprise->subscription_status === 'expired' || $activeEntreprise->subscription_status === 'suspended')
                <div class="mt-6 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-950">
                    L’accès aux fonctionnalités de gestion est suspendu. Vous pouvez choisir un forfait ci-dessous. L’équipe PharmaGest devra confirmer et activer l’abonnement avant le rétablissement de l’accès.
                </div>
            @elseif ($activeEntreprise->subscription_status === 'trial' && $deadline)
                <div class="mt-6 rounded-xl border border-sky-200 bg-sky-50 p-4 text-sm leading-6 text-sky-950">
                    Votre essai inclut le forfait {{ $activeEntreprise->subscriptionPlan?->name ?? 'actuel' }}. Choisissez un forfait avant son échéance pour demander son activation sans interruption.
                </div>
            @elseif ($activeEntreprise->subscription_status === 'active' && $deadline)
                <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm leading-6 text-emerald-950">
                    Votre abonnement est actif. Vous pouvez demander son renouvellement avant la date d’échéance.
                </div>
            @endif

            @if ($activeEntreprise->subscription_requested_plan)
                <div class="mt-4 rounded-xl border border-violet-200 bg-violet-50 p-4 text-sm leading-6 text-violet-950">
                    Demande en attente pour le forfait
                    <strong>{{ $activeEntreprise->requestedSubscriptionPlan?->name ?? $activeEntreprise->subscription_requested_plan }}</strong>
                    @if ($activeEntreprise->subscription_requested_at)
                        — envoyée le {{ $activeEntreprise->subscription_requested_at->format('d/m/Y à H:i') }}.
                    @else
                        .
                    @endif
                    L’accès reste régi par le statut et l’échéance actuels jusqu’à activation par PharmaGest.
                </div>
            @endif
        </section>

        <section>
            <div class="mb-5">
                <h2 class="text-xl font-semibold text-slate-900">Choisir ou renouveler un forfait</h2>
                <p class="mt-1 text-sm leading-6 text-slate-600">Les tarifs sont en FCFA. Le paiement en ligne n’est pas encore disponible : l’envoi de cette demande ne déclenche aucun paiement ni activation automatique.</p>
            </div>

            <div class="grid gap-4 lg:grid-cols-3">
                @foreach ($plans as $plan)
                    <article class="flex flex-col rounded-2xl border {{ $activeEntreprise->subscription_requested_plan === $plan->slug ? 'border-violet-300 ring-2 ring-violet-100' : 'border-slate-200' }} bg-white p-5 shadow-sm">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">{{ $plan->name }}</h3>
                            <p class="mt-2 text-sm font-semibold text-emerald-800">
                                {{ $plan->price_fcfa === null ? 'Prix à définir' : number_format($plan->price_fcfa, 0, ',', ' ').' FCFA / mois' }}
                            </p>
                            <ul class="mt-5 space-y-2 text-sm text-slate-600">
                                <li>Jusqu’à {{ $plan->user_limit ? $plan->user_limit.' utilisateur(s)' : 'un nombre illimité d’utilisateurs' }}</li>
                                <li>{{ $plan->product_limit ? 'Jusqu’à '.number_format($plan->product_limit, 0, ',', ' ').' produits' : 'Produits sans limite' }}</li>
                                <li>{{ $plan->kpis_enabled ? 'Indicateurs KPI inclus' : 'Indicateurs KPI non inclus' }}</li>
                                <li>Gestion des médicaments, fournisseurs, clients, stock, commandes et ventes</li>
                            </ul>
                        </div>

                        @role('administrateur')
                            <form method="POST" action="{{ route('entreprise.subscription.request') }}" class="mt-6">
                                @csrf
                                <input type="hidden" name="subscription_plan" value="{{ $plan->slug }}">
                                <button class="w-full rounded-xl {{ $activeEntreprise->subscription_requested_plan === $plan->slug ? 'border border-violet-200 bg-violet-50 text-violet-900 hover:bg-violet-100' : 'bg-emerald-700 text-white hover:bg-emerald-800' }} px-4 py-2.5 text-sm font-semibold transition">
                                    {{ $activeEntreprise->subscription_requested_plan === $plan->slug ? 'Renvoyer la demande' : ($activeEntreprise->subscription_status === 'active' ? 'Demander le renouvellement' : 'Demander ce forfait') }}
                                </button>
                            </form>
                        @endrole
                    </article>
                @endforeach
            </div>
        </section>

        @unlessrole('administrateur')
            <p class="rounded-xl border border-slate-200 bg-white p-4 text-sm text-slate-600">Seul l’administrateur de l’entreprise peut demander un forfait. Contactez-le pour continuer.</p>
        @endunlessrole
    </div>
</x-app-layout>
