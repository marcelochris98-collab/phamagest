<x-guest-layout>
    <h2 class="text-lg font-semibold text-slate-900">Choisir une entreprise</h2>
    <p class="mt-1 text-sm text-slate-500">Sélectionnez l’espace auquel vous souhaitez accéder.</p>

    <form method="POST" action="{{ route('entreprise.active') }}" class="mt-6 space-y-5">
        @csrf

        <div>
            <label for="entreprise_id" class="block text-sm font-medium text-slate-700">Entreprise</label>
            <select
                id="entreprise_id"
                name="entreprise_id"
                required
                autofocus
                class="mt-1 block w-full rounded-xl border-slate-300 shadow-sm focus:border-emerald-500 focus:ring-emerald-500"
            >
                @foreach ($entreprises as $entreprise)
                    <option value="{{ $entreprise->id }}" @selected((string) session('active_entreprise_id') === (string) $entreprise->id)>
                        {{ $entreprise->nom }}
                    </option>
                @endforeach
            </select>
            @error('entreprise_id')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button
            type="submit"
            class="w-full rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700"
        >
            Continuer
        </button>
    </form>

    <form method="POST" action="{{ route('logout') }}" class="mt-4 text-center">
        @csrf
        <button type="submit" class="text-sm font-medium text-slate-500 hover:text-slate-800">
            Se déconnecter
        </button>
    </form>
</x-guest-layout>
