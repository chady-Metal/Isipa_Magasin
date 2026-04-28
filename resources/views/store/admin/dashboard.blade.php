@extends('store.admin.layouts.app')

@section('content')
    <section class="mb-8">
        <p class="section-tag">Vue globale</p>
        <h1 class="section-title mt-3">Pilotage de la boutique</h1>
        <p class="section-subtitle">Suivi des ventes, du stock, des commandes, des messages support et des activites administrateurs en temps reel.</p>
    </section>

    <section class="mb-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <div class="admin-stat-card admin-stat-card-blue"><p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-100">Produits</p><p class="mt-3 text-3xl font-black">{{ $stats['products'] }}</p></div>
        <div class="admin-stat-card admin-stat-card-ice"><p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Clients</p><p class="mt-3 text-3xl font-black text-[var(--isipa-admin-ink)]">{{ $stats['clients'] }}</p></div>
        <div class="admin-stat-card admin-stat-card-gold"><p class="text-xs font-semibold uppercase tracking-[0.18em]">Commandes en attente</p><p class="mt-3 text-3xl font-black">{{ $stats['pending_orders'] }}</p></div>
        <div class="admin-stat-card admin-stat-card-blue"><p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-100">Ventes</p><p class="mt-3 text-3xl font-black">{{ number_format($stats['sales_total'], 2, ',', ' ') }} $</p></div>
    </section>

    <section class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
        <div class="space-y-6">
            <article class="admin-card">
                <h2 class="text-xl font-black text-[var(--isipa-admin-ink)]">Stock critique (<= 5)</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($stockAlerts as $produit)
                        <div class="flex items-center justify-between rounded-2xl bg-slate-50 px-4 py-3">
                            <div>
                                <p class="font-semibold">{{ $produit->nom }}</p>
                                <p class="text-xs text-slate-500">{{ $produit->categorie->nom ?? 'Sans categorie' }}</p>
                            </div>
                            <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-700">{{ $produit->stock }} restants</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">Aucune alerte de stock.</p>
                    @endforelse
                </div>
            </article>

            <article class="admin-card">
                <h2 class="text-xl font-black text-[var(--isipa-admin-ink)]">Dernieres commandes</h2>
                <div class="mt-4 space-y-3">
                    @foreach ($commandes as $commande)
                        <div class="rounded-2xl bg-slate-50 px-4 py-3">
                            <p class="font-semibold">#{{ $commande->id }} | {{ $commande->user->name ?? 'Client' }}</p>
                            <p class="text-sm text-slate-600">{{ $commande->tracking_code }} | {{ $commande->statutLabel() }}</p>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>

        <div class="space-y-6">
            <article class="admin-card">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-black text-[var(--isipa-admin-ink)]">Mouvements administrateurs</h2>
                    @if (auth()->user()->hasPermission('admins.activity.view'))
                        <a href="{{ route('admin.admins.index') }}" class="text-xs font-bold uppercase tracking-wider text-[var(--isipa-primary)] hover:underline" data-async-link>Gérer</a>
                    @endif
                </div>
                <div class="mt-4 space-y-3">
                    @foreach ($recentActivities as $activity)
                        <div class="rounded-2xl bg-slate-50 px-4 py-3 text-sm">
                            <p class="font-semibold">{{ $activity->admin->name ?? 'Admin' }}</p>
                            <p>{{ $activity->action }}</p>
                            <p class="text-xs text-slate-500">{{ $activity->created_at->format('d/m/Y H:i') }} | {{ $activity->details }}</p>
                        </div>
                    @endforeach
                </div>
            </article>

            <article class="admin-card">
                <h2 class="text-xl font-black text-[var(--isipa-admin-ink)]">Messages support / admin</h2>
                <div class="mt-4 space-y-3">
                    @foreach ($supportMessages as $message)
                        <div class="rounded-2xl border border-slate-200 px-4 py-3 text-sm">
                            <p class="font-semibold">{{ $message->subject }}</p>
                            <p class="text-slate-600">{{ $message->name }} | {{ $message->email }}</p>
                        </div>
                    @endforeach
                    @foreach ($recentMessages as $message)
                        <div class="rounded-2xl bg-slate-50 px-4 py-3 text-sm">
                            <p class="font-semibold">{{ $message->subject }}</p>
                            <p class="text-slate-600">{{ $message->sender->name ?? 'Admin' }} vers {{ $message->recipient->name ?? 'Tous' }}</p>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>
    </section>
@endsection
