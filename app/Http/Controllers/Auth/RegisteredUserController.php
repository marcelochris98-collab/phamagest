<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Entreprise;
use App\Models\EntrepriseDocument;
use App\Models\User;
use App\Services\EntrepriseRoleService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use RuntimeException;
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
            'numero_enregistrement' => ['required', 'string', 'max:100'],
            'numero_autorisation' => ['required', 'string', 'max:100'],
            'document_enregistrement' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'document_autorisation' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        [$entreprise, $user] = DB::transaction(function () use ($validated, $request) {
            $entreprise = Entreprise::create([
                'nom' => $validated['entreprise'],
                'slug' => Str::slug($validated['entreprise']).'-'.Str::lower((string) Str::ulid()),
                'email' => $validated['entreprise_email'] ?? null,
                'telephone' => $validated['entreprise_telephone'] ?? null,
                'adresse' => $validated['entreprise_adresse'] ?? null,
                'numero_enregistrement' => $validated['numero_enregistrement'],
                'numero_autorisation' => $validated['numero_autorisation'],
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

            foreach ([
                'document_enregistrement' => 'enregistrement',
                'document_autorisation' => 'autorisation',
            ] as $field => $type) {
                $file = $request->file($field);
                $path = $file->store("private/entreprise-documents/{$entreprise->id}", 'local');

                if (! $path) {
                    throw new RuntimeException('Un justificatif n’a pas pu être enregistré.');
                }

                EntrepriseDocument::create([
                    'entreprise_id' => $entreprise->id,
                    'uploaded_by' => $user->id,
                    'type' => $type,
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType() ?: 'application/octet-stream',
                    'size' => $file->getSize(),
                ]);
            }

            return [$entreprise, $user];
        });

        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->put('active_entreprise_id', $entreprise->getKey());

        return redirect()->route('entreprise.verification.status');
    }
}
