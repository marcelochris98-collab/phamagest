<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Support\Permissions;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('roles', 'permissions')
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
        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
            'email_verified_at' => now(),
        ]);

        $this->syncRoleAndPermissions($user, $request->validated('role'), $request->input('permissions', []));

        return redirect()
            ->route('admin.users.index')
            ->with('success', "L'employé {$user->name} a été créé avec succès.");
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', array_merge(
            $this->formData(),
            ['user' => $user->load('roles', 'permissions')]
        ));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
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
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', "L'utilisateur {$name} a été supprimé.");
    }

    protected function formData(): array
    {
        return [
            'roles' => Role::where('guard_name', 'web')->orderBy('name')->get(),
            'permissionGroups' => Permissions::grouped(),
            'permissionLabels' => Permissions::all(),
        ];
    }

    protected function syncRoleAndPermissions(User $user, string $role, array $permissions): void
    {
        $user->syncRoles([$role]);

        if ($role === 'administrateur') {
            $user->syncPermissions(Permission::all());

            return;
        }

        $user->syncPermissions($permissions);

        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
