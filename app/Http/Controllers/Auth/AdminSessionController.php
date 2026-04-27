<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminSessionController extends Controller
{
    public function create()
    {
        return view('store.admin.auth.login');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['nullable', 'boolean'],
        ]);

        $email = Str::lower(trim((string) $data['email']));
        $user = User::with(['role.attributions.permission', 'permissions'])->whereRaw('LOWER(email) = ?', [$email])->first();

        if (! $user || ! Hash::check((string) $data['password'], $user->password) || ! $user->isAdmin()) {
            throw ValidationException::withMessages([
                'email' => 'Identifiants administrateur invalides.',
            ]);
        }

        Auth::login($user, (bool) ($data['remember'] ?? false));
        $request->session()->regenerate();

        $user->forceFill([
            'last_login_at' => now(),
            'last_seen_at' => now(),
            'last_operation' => 'Connexion admin',
        ])->save();

        $this->logAdminActivity($request, 'Connexion admin', 'user', $user->id, $user->email);

        return redirect()->intended(route('admin.dashboard'));
    }
}
