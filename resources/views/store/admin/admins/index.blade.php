@extends('store.admin.layouts.app')

@section('content')
    <section class="mb-6">
        <p class="section-tag">Supervision</p>
        <h1 class="section-title mt-3">Administrateurs et Gerants</h1>
        <p class="section-subtitle">Creation, permissions, revocation et suivi des dernieres operations.</p>
    </section>

    @if (auth()->user()->hasPermission('admins.create'))
        <section class="admin-card mb-6">
            <h2 class="text-xl font-black text-[var(--isipa-admin-ink)]">Creer un compte administrateur</h2>
            <form method="POST" action="{{ route('admin.admins.store') }}" class="mt-4 grid gap-3 md:grid-cols-2" data-async>
                @csrf
                <input type="text" name="name" class="store-input" placeholder="Nom complet" required />
                <input type="email" name="email" class="store-input" placeholder="Gmail administrateur" required />
                <input type="text" name="numeroTelephone" class="store-input" placeholder="Telephone" />
                <select name="roles_id" class="store-select" required>
                    <option value="">Role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->nom }}</option>
                    @endforeach
                </select>
                <input type="password" name="password" class="store-input" placeholder="Mot de passe" required />
                <input type="password" name="password_confirmation" class="store-input" placeholder="Confirmation" required />
                <div class="md:col-span-2 rounded-2xl bg-slate-50 p-4 text-sm text-slate-600">
                    Les permissions sont appliquees automatiquement selon le role choisi.
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="store-btn-primary">Creer le compte</button>
                </div>
            </form>
        </section>
    @endif

    <section class="space-y-4">
        @foreach ($admins as $admin)
            <article class="admin-card">
                <div class="grid gap-4 xl:grid-cols-[0.9fr_1.1fr]">
                    <div>
                        <h2 class="text-lg font-black text-[var(--isipa-admin-ink)]">{{ $admin->name }}</h2>
                        <p class="text-sm text-slate-500">{{ $admin->email }} | {{ $admin->role->nom ?? 'Aucun rôle' }}</p>
                        <p class="mt-2 text-sm text-slate-600">Derniere connexion: {{ optional($admin->last_login_at)->format('d/m/Y H:i') ?: 'Jamais' }}</p>
                        <p class="text-sm text-slate-600">Derniere operation: {{ $admin->last_operation ?: 'Aucune' }}</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($admin->role?->attributions ?? [] as $attribution)
                                <span class="rounded-full bg-[var(--isipa-soft)] px-3 py-1 text-xs font-semibold text-[var(--isipa-primary)]">{{ $attribution->permission->nom ?? 'N/A' }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="rounded-2xl border border-slate-200 p-4 text-sm text-slate-600">
                            Le role <strong>{{ $admin->role->nom }}</strong> applique automatiquement son jeu de permissions par defaut.
                        </div>
                        @if (auth()->user()->hasPermission('admins.revoke') && ! $admin->isSuperAdmin())
                            <form method="POST" action="{{ route('admin.admins.revoke', $admin) }}" data-async>
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="store-btn-danger">Revoquer cet administrateur</button>
                            </form>
                        @endif
                        <div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-700">
                            <p class="font-semibold">Journal recent</p>
                            <div class="mt-2 space-y-2">
                                 @foreach ($admin->adminActivities->take(5) as $activity)
                                    <p>{{ $activity->created_at->format('d/m H:i') }} | {{ $activity->action }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        @endforeach
    </section>

    <div class="mt-8">{{ $admins->links() }}</div>
@endsection
