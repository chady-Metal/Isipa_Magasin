<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', ['title' => $title ?? 'ISIPA Store'])
</head>
<body class="store-bg text-slate-900">
    <div class="min-h-screen">
        <header class="sticky top-0 z-30 border-b border-white/30 bg-white/75 backdrop-blur-xl">
            <div class="h-1 w-full bg-gradient-to-r from-[var(--isipa-primary)] via-[var(--isipa-secondary)] to-[var(--isipa-tertiary)]"></div>
            <div class="mx-auto flex w-full max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-[var(--isipa-primary)] text-lg font-black text-white">I</span>
                    <span class="text-lg font-bold tracking-tight text-[var(--isipa-ink)]">ISIPA Store</span>
                </a>

                <nav class="hidden items-center gap-2 md:flex">
                    <a href="{{ route('home') }}" class="store-nav-link {{ request()->routeIs('home') ? 'store-nav-link-active' : '' }}">Accueil</a>
                    <a href="{{ route('catalogue.index') }}" class="store-nav-link {{ request()->routeIs('catalogue.*') ? 'store-nav-link-active' : '' }}">Catalogue</a>

                    @auth
                        @if (strtolower(optional(auth()->user()->role)->nom ?? '') === 'client')
                            <a href="{{ route('client.panier.index') }}" class="store-nav-link {{ request()->routeIs('client.panier.*') ? 'store-nav-link-active' : '' }}">Panier</a>
                            <a href="{{ route('client.commandes.index') }}" class="store-nav-link {{ request()->routeIs('client.commandes.*') ? 'store-nav-link-active' : '' }}">Commandes</a>
                            <a href="{{ route('client.reclamations.index') }}" class="store-nav-link {{ request()->routeIs('client.reclamations.*') ? 'store-nav-link-active' : '' }}">Reclamations</a>
                        @endif

                        @if (strtolower(optional(auth()->user()->role)->nom ?? '') === 'administrateur')
                            <a href="{{ route('admin.produits.index') }}" class="store-nav-link {{ request()->routeIs('admin.produits.*') ? 'store-nav-link-active' : '' }}">Admin</a>
                            <a href="{{ route('admin.commandes.index') }}" class="store-nav-link {{ request()->routeIs('admin.commandes.*') ? 'store-nav-link-active' : '' }}">Cmd clients</a>
                        @endif
                    @endauth
                </nav>

                <div class="flex items-center gap-2">
                    @auth
                        <span class="hidden rounded-full border border-[var(--isipa-secondary)]/30 bg-[var(--isipa-soft)] px-3 py-1 text-xs font-semibold text-[var(--isipa-ink)] sm:inline-flex">{{ auth()->user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="store-btn-secondary">Deconnexion</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="store-btn-secondary">Connexion</a>
                        <a href="{{ route('register') }}" class="store-btn-primary">Inscription</a>
                    @endauth
                </div>
            </div>

            <div class="mx-auto w-full max-w-7xl px-4 pb-3 sm:px-6 md:hidden lg:px-8">
                <nav class="flex flex-wrap gap-2">
                    <a href="{{ route('home') }}" class="store-nav-link {{ request()->routeIs('home') ? 'store-nav-link-active' : '' }}">Accueil</a>
                    <a href="{{ route('catalogue.index') }}" class="store-nav-link {{ request()->routeIs('catalogue.*') ? 'store-nav-link-active' : '' }}">Catalogue</a>
                    @auth
                        @if (strtolower(optional(auth()->user()->role)->nom ?? '') === 'client')
                            <a href="{{ route('client.panier.index') }}" class="store-nav-link {{ request()->routeIs('client.panier.*') ? 'store-nav-link-active' : '' }}">Panier</a>
                            <a href="{{ route('client.commandes.index') }}" class="store-nav-link {{ request()->routeIs('client.commandes.*') ? 'store-nav-link-active' : '' }}">Commandes</a>
                            <a href="{{ route('client.reclamations.index') }}" class="store-nav-link {{ request()->routeIs('client.reclamations.*') ? 'store-nav-link-active' : '' }}">Reclamations</a>
                        @endif

                        @if (strtolower(optional(auth()->user()->role)->nom ?? '') === 'administrateur')
                            <a href="{{ route('admin.produits.index') }}" class="store-nav-link {{ request()->routeIs('admin.produits.*') ? 'store-nav-link-active' : '' }}">Admin</a>
                            <a href="{{ route('admin.commandes.index') }}" class="store-nav-link {{ request()->routeIs('admin.commandes.*') ? 'store-nav-link-active' : '' }}">Cmd clients</a>
                        @endif
                    @endauth
                </nav>
            </div>
        </header>

        <main class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 glass-panel border-emerald-200 bg-emerald-50/95 px-4 py-3 text-sm font-medium text-emerald-700 reveal-up">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 glass-panel border-rose-200 bg-rose-50/95 px-4 py-3 text-sm font-medium text-rose-700 reveal-up">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 glass-panel border-amber-200 bg-amber-50/95 px-4 py-3 text-sm text-amber-800 reveal-up">
                    <p class="mb-2 font-semibold">Merci de corriger les champs suivants:</p>
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
