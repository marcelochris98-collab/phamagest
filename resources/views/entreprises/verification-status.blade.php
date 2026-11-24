<x-app-layout>
    <x-slot name="header">Vérification de l’entreprise</x-slot>

    <div class="mx-auto max-w-3xl space-y-6">
        <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <p class="text-sm text-slate-500">Entreprise</p>
            <h2 class="mt-1 text-xl font-semibold text-slate-900">{{ $entreprise->nom }}</h2>
            <p class="mt-4 text-sm">
                Statut :
                <span class="font-semibold">{{ match ($entreprise->verification_status) {
                    'approved' => 'Vérifiée',
                    'rejected' => 'Corrections demandées',
                    default => 'En attente de vérification',
                } }}</span>
            </p>
            @if ($entreprise->verification_note)
                <p class="mt-3 rounded-xl bg-amber-50 p-4 text-sm text-amber-900">{{ $entreprise->verification_note }}</p>
            @endif
            @if ($entreprise->verification_status === 'pending')
                <p class="mt-4 text-sm text-slate-600">L’équipe PharmaGest examine les justificatifs. L’accès aux fonctions de gestion sera ouvert après validation.</p>
            @endif
        </section>

        @if (auth()->user()->hasRole('administrateur') && $documents->isNotEmpty())
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h3 class="font-semibold text-slate-900">Justificatifs envoyés</h3>
                <ul class="mt-4 divide-y divide-slate-100">
                    @foreach ($documents as $document)
                        <li class="py-3 text-sm text-slate-700">{{ $document->type === 'enregistrement' ? 'Enregistrement' : 'Autorisation' }} — {{ $document->original_name }}</li>
                    @endforeach
                </ul>
            </section>
        @endif

        @if ($entreprise->verification_status === 'rejected' && auth()->user()->hasRole('administrateur'))
            <form method="POST" action="{{ route('entreprise.verification.resubmit') }}" enctype="multipart/form-data" class="space-y-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                @csrf
                <h3 class="font-semibold text-slate-900">Renvoyer le dossier corrigé</h3>
                <div>
                    <label for="numero_enregistrement" class="text-sm font-medium text-slate-700">Numéro d’enregistrement</label>
                    <input id="numero_enregistrement" name="numero_enregistrement" required value="{{ old('numero_enregistrement', $entreprise->numero_enregistrement) }}" class="mt-1 block w-full rounded-xl border-slate-300">
                    @error('numero_enregistrement')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="document_enregistrement" class="text-sm font-medium text-slate-700">Nouveau justificatif d’enregistrement</label>
                    <input id="document_enregistrement" name="document_enregistrement" type="file" accept=".pdf,.jpg,.jpeg,.png" required class="mt-1 block w-full text-sm">
                    @error('document_enregistrement')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="numero_autorisation" class="text-sm font-medium text-slate-700">Numéro d’autorisation</label>
                    <input id="numero_autorisation" name="numero_autorisation" required value="{{ old('numero_autorisation', $entreprise->numero_autorisation) }}" class="mt-1 block w-full rounded-xl border-slate-300">
                    @error('numero_autorisation')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="document_autorisation" class="text-sm font-medium text-slate-700">Nouveau justificatif d’autorisation</label>
                    <input id="document_autorisation" name="document_autorisation" type="file" accept=".pdf,.jpg,.jpeg,.png" required class="mt-1 block w-full text-sm">
                    @error('document_autorisation')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <button class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Renvoyer pour vérification</button>
            </form>
        @endif
    </div>
</x-app-layout>
