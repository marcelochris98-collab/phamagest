<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\Entreprise;
use App\Models\User;
use App\Services\SubscriptionLimitService;
use App\Support\CurrentEntreprise;
use App\Support\Permissions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('roles', 'permissions')
            ->whereHas('entreprises', function ($query) {
                $query->whereKey(app(CurrentEntreprise::class)->get()->getKey());
            })
            ->orderBy('name')
            ->paginate(12);

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.create', $this->formData());
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $entreprise = app(CurrentEntreprise::class)->get();

        $user = DB::transaction(function () use ($request, $entreprise) {
            $lockedEntreprise = Entreprise::query()->lockForUpdate()->findOrFail($entreprise->id);
            if (! app(SubscriptionLimitService::class)->canAddUser($lockedEntreprise)) {
                return null;
            }

            $user = User::create([
                'name' => $request->validated('name'),
                'email' => $request->validated('email'),
                'password' => $request->validated('password'),
                'email_verified_at' => now(),
            ]);
            $user->entreprises()->attach($entreprise);

            $this->syncRoleAndPermissions($user, $request->validated('role'), $request->input('permissions', []));

            return $user;
        });

        if (! $user) {
            return back()->withInput()->with('error', 'La limite d’utilisateurs de votre forfait est atteinte. Contactez PharmaGest pour changer de forfait.');
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', "L'employé {$user->name} a été créé avec succès.");
    }

    public function edit(User $user): View
    {
        $this->ensureUserBelongsToCurrentEntreprise($user);

        return view('admin.users.edit', array_merge(
            $this->formData(),
            ['user' => $user->load('roles', 'permissions')]
        ));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $this->ensureUserBelongsToCurrentEntreprise($user);

        $data = [
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->validated('password');
        }

        $user->update($data);

        $this->syncRoleAndPermissions($user, $request->validated('role'), $request->input('permissions', []));

        return redirect()
            ->route('admin.users.index')
            ->with('success', "Le profil de {$user->name} a été mis à jour.");
    }

    public function destroy(User $user): RedirectResponse
    {
        $this->ensureUserBelongsToCurrentEntreprise($user);

        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $name = $user->name;
        $user->entreprises()->detach(app(CurrentEntreprise::class)->get());

        return redirect()
            ->route('admin.users.index')
            ->with('success', "L'accès de {$name} à cette entreprise a été supprimé.");
    }

    protected function formData(): array
    {
        $teamKey = config('permission.column_names.team_foreign_key');

        return [
            'roles' => Role::where('guard_name', 'web')
                ->where($teamKey, app(CurrentEntreprise::class)->get()->getKey())
                ->orderBy('name')
                ->get(),
            'permissionGroups' => Permissions::grouped(),
            'permissionLabels' => Permissions::all(),
        ];
    }

    protected function syncRoleAndPermissions(User $user, string $role, array $permissions): void
    {
        $teamKey = config('permission.column_names.team_foreign_key');
        $roleModel = Role::where('name', $role)
            ->where($teamKey, app(CurrentEntreprise::class)->get()->getKey())
            ->firstOrFail();
        $user->syncRoles([$roleModel]);

        if ($role === 'administrateur') {
            $user->syncPermissions(Permission::all());

            return;
        }

        $user->syncPermissions($permissions);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }

    protected function ensureUserBelongsToCurrentEntreprise(User $user): void
    {
        abort_unless(
            $user->entreprises()
                ->whereKey(app(CurrentEntreprise::class)->get()->getKey())
                ->exists(),
            404
        );
    }
}
