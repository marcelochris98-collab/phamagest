<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;
use App\Models\User;
use App\Services\EntrepriseRoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\PermissionRegistrar;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'entreprise' => ['required', 'string', 'max:255'],
            'entreprise_email' => ['nullable', 'email', 'max:255'],
            'entreprise_telephone' => ['nullable', 'string', 'max:30'],
            'entreprise_adresse' => ['nullable', 'string', 'max:1000'],
            'numero_enregistrement' => ['nullable', 'string', 'max:100'],
            'numero_autorisation' => ['nullable', 'string', 'max:100'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        [$entreprise, $user] = DB::transaction(function () use ($validated) {
            $entreprise = Entreprise::create([
                'nom' => $validated['entreprise'],
                'slug' => Str::slug($validated['entreprise']).'-'.Str::lower((string) Str::ulid()),
                'email' => $validated['entreprise_email'] ?? null,
                'telephone' => $validated['entreprise_telephone'] ?? null,
                'adresse' => $validated['entreprise_adresse'] ?? null,
                'numero_enregistrement' => $validated['numero_enregistrement'] ?? null,
                'numero_autorisation' => $validated['numero_autorisation'] ?? null,
                'verification_status' => 'approved',
                'verified_at' => now(),
                'subscription_status' => 'trial',
                'trial_ends_at' => now()->addMonth(),
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
            ]);
            $user->entreprises()->attach($entreprise);

            $registrar = app(PermissionRegistrar::class);
            $registrar->setPermissionsTeamId($entreprise->getKey());
            $administrator = app(EntrepriseRoleService::class)->createDefaultRoles($entreprise);
            $user->assignRole($administrator);
            $registrar->forgetCachedPermissions();

            return [$entreprise, $user];
        });

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->put('active_entreprise_id', $entreprise->getKey());

        return redirect()->route('dashboard');
    }
}
