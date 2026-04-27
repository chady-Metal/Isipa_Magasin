<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', ['title' => 'Connexion administrateur'])
</head>
<body class="admin-bg flex min-h-screen items-center justify-center px-4 py-10">
    <div class="admin-auth-shape admin-auth-shape-a"></div>
    <div class="admin-auth-shape admin-auth-shape-b"></div>
    <div class="admin-auth-shell relative z-10 w-full max-w-md">
        <p class="section-tag">Acces securise</p>
        <h1 class="mt-4 text-3xl font-black text-[var(--isipa-admin-ink)]">Connexion administrateur</h1>
        <p class="mt-2 text-sm text-slate-600">Chemin protege: <strong>/admin/login</strong>. Seuls les comptes ayant un role administrateur ou gerant peuvent entrer ici.</p>

        @if ($errors->any())
            <div class="mt-4 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.store') }}" class="mt-6 space-y-4">
            @csrf
            <input type="email" name="email" class="store-input" placeholder="Gmail administrateur" required />
            <input type="password" name="password" class="store-input" placeholder="Mot de passe" required />
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" value="1" />
                Se souvenir de moi
            </label>
            <button type="submit" class="store-btn-primary w-full">Entrer dans l'espace admin</button>
        </form>

        <div class="mt-6 text-sm text-slate-600">
            <a href="{{ route('login') }}" class="font-semibold text-[var(--isipa-primary)]">Retour a la connexion client</a>
        </div>
    </div>
</body>
</html>
