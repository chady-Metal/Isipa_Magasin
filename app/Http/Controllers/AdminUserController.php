<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminUserController extends Controller
{
    public function clients(Request $request)
    {
        $this->ensurePermission($request, 'customers.delete');

        $clients = User::with('role')
            ->whereHas('role', fn ($query) => $query->where('nom', 'Client'))
            ->latest()
            ->paginate(12);

        return view('store.admin.clients.index', compact('clients'));
    }

    public function deleteClient(Request $request, User $user)
    {
        $this->ensurePermission($request, 'customers.delete');

        abort_unless($user->isClient(), 403, 'Ce compte n est pas un client.');

        $data = $request->validate([
            'justification' => ['required', 'string', 'min:10'],
        ]);

        $user->update([
            'deletion_reason' => $data['justification'],
        ]);
        $user->delete();

        $this->logAdminActivity($request, 'Suppression client', 'user', $user->id, $data['justification']);

        return back()->with('success', 'Compte client supprime avec justification.');
    }

    public function admins(Request $request)
    {
        $this->ensurePermission($request, 'admins.activity.view');

        $admins = User::with(['role.attributions.permission', 'adminActivities' => fn ($query) => $query->latest()->take(10)])
            ->whereHas('role', fn ($query) => $query->whereIn('nom', ['Super Administrateur', 'Administrateur', 'Gerant']))
            ->latest()
            ->paginate(12);

        $roles = Role::whereIn('nom', ['Super Administrateur', 'Administrateur', 'Gerant'])->get();

        return view('store.admin.admins.index', compact('admins', 'roles'));
    }

    public function storeAdmin(Request $request)
    {
        $this->ensurePermission($request, 'admins.create');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'numeroTelephone' => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'roles_id' => ['required', 'exists:roles,id'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'numeroTelephone' => $data['numeroTelephone'] ?? null,
            'password' => Hash::make($data['password']),
            'roles_id' => $data['roles_id'],
        ]);

        $this->logAdminActivity($request, 'Creation administrateur', 'user', $user->id, $user->email);

        return back()->with('success', 'Compte administrateur cree avec succes.');
    }

    public function revoke(Request $request, User $user)
    {
        $this->ensurePermission($request, 'admins.revoke');

        abort_if($user->isSuperAdmin(), 403, 'Impossible de revoquer un super administrateur.');

        $clientRoleId = Role::where('nom', 'Client')->value('id');
        $user->update(['roles_id' => $clientRoleId]);
        $user->permissions()->detach();

        $this->logAdminActivity($request, 'Revocation administrateur', 'user', $user->id, $user->email);

        return back()->with('success', 'Administrateur revoque.');
    }
}
