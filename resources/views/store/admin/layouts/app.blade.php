<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head', ['title' => $title ?? 'Administration ISIPA'])
</head>
<body class="admin-bg text-slate-900">
    <div class="min-h-screen">
        <header class="border-b border-slate-200 bg-white/90 backdrop-blur-xl">
            <div class="h-1.5 w-full bg-gradient-to-r from-[var(--isipa-primary)] via-[var(--isipa-secondary)] to-[var(--isipa-tertiary)]"></div>
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-[var(--isipa-primary)]">Interface administrateur</p>
                    <a href="{{ route('admin.dashboard') }}" class="mt-1 block text-2xl font-black text-[var(--isipa-admin-ink)]" data-async-link>ISIPA Store Admin</a>
                </div>
                <div class="flex items-center gap-3">
                    <span class="rounded-full bg-[var(--isipa-soft)] px-3 py-1 text-xs font-semibold text-[var(--isipa-admin-ink)]">
                        {{ auth()->user()->role->nom ?? 'Admin' }} | {{ auth()->user()->name }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="store-btn-secondary">Deconnexion</button>
                    </form>
                </div>
            </div>
            <div class="mx-auto flex max-w-7xl flex-wrap gap-2 px-4 pb-5 sm:px-6 lg:px-8">
                <a href="{{ route('admin.dashboard') }}" class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'admin-nav-link-active' : '' }}" data-async-link>Vue globale</a>
                @if (auth()->user()->hasPermission('products.update'))
                    <a href="{{ route('admin.produits.index') }}" class="admin-nav-link {{ request()->routeIs('admin.produits.*') ? 'admin-nav-link-active' : '' }}" data-async-link>Produits</a>
                @endif
                @if (auth()->user()->hasPermission('orders.view'))
                    <a href="{{ route('admin.commandes.index') }}" class="admin-nav-link {{ request()->routeIs('admin.commandes.*') ? 'admin-nav-link-active' : '' }}" data-async-link>Commandes</a>
                @endif
                @if (auth()->user()->hasPermission('claims.reply'))
                    <a href="{{ route('admin.reclamations.index') }}" class="admin-nav-link {{ request()->routeIs('admin.reclamations.*') ? 'admin-nav-link-active' : '' }}" data-async-link>Reclamations</a>
                @endif
                @if (auth()->user()->hasPermission('customers.delete'))
                    <a href="{{ route('admin.clients.index') }}" class="admin-nav-link {{ request()->routeIs('admin.clients.*') ? 'admin-nav-link-active' : '' }}" data-async-link>Clients</a>
                @endif
                @if (auth()->user()->hasPermission('admins.activity.view'))
                    <a href="{{ route('admin.admins.index') }}" class="admin-nav-link {{ request()->routeIs('admin.admins.*') ? 'admin-nav-link-active' : '' }}" data-async-link>Administrateurs</a>
                @endif
                @if (auth()->user()->hasPermission('admins.message'))
                    <a href="{{ route('admin.messages.index') }}" class="admin-nav-link {{ request()->routeIs('admin.messages.*') ? 'admin-nav-link-active' : '' }}" data-async-link>Messages</a>
                @endif
            </div>
        </header>

        <main class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8" data-page-content>
            @if (session('success'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800">
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
