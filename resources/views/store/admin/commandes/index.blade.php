@extends('store.admin.layouts.app')

@section('content')
    <section class="mb-6">
        <p class="section-tag">Administration</p>
        <h1 class="section-title mt-3">Gestion des Commandes</h1>
        <p class="section-subtitle">Confirmation, rejet motive, suivi des commandes et etat de vente de la boutique.</p>
    </section>

    <div class="mb-6 grid gap-3 md:grid-cols-3">
        <div class="admin-card"><p class="metric-label">Commandes en attente</p><p class="metric-value">{{ $pendingCount }}</p></div>
        <div class="admin-card"><p class="metric-label">Ventes totales</p><p class="metric-value">{{ number_format($salesTotal, 2, ',', ' ') }} $</p></div>
        <div class="admin-card"><p class="metric-label">Commandes total</p><p class="metric-value">{{ $commandes->total() }}</p></div>
    </div>

    <section class="space-y-4">
        @foreach ($commandes as $commande)
            <article class="admin-card">
                <div class="mb-4 flex flex-col gap-2 border-b border-slate-200 pb-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Commande #{{ $commande->id }} | Client: {{ $commande->user->name ?? 'N/A' }}</p>
                        <p class="text-sm text-slate-500">Code suivi: {{ $commande->tracking_code }} | Adresse: {{ $commande->adresse_livraison }}</p>
                        <p class="mt-2"><span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $commande->statutBadgeClass() }}">{{ $commande->statutLabel() }}</span></p>
                    </div>
                    <div class="text-sm text-slate-600">
                        @if ($commande->paiement)
                            <p class="font-semibold">Paiement: {{ $commande->paiement->methode_paiement }}</p>
                            <p>Montant: {{ number_format($commande->paiement->montant, 2, ',', ' ') }} $</p>
                        @endif
                        @if ($commande->processedBy)
                            <p class="mt-1 text-xs">Traitee par {{ $commande->processedBy->name }}</p>
                        @endif
                    </div>
                </div>
                <div class="mb-4">
                    <h2 class="mb-2 text-sm font-bold uppercase tracking-wide text-slate-500">Produits</h2>
                    <ul class="space-y-2 text-sm text-slate-700">
                        @foreach ($commande->produits as $produit)
                            <li class="rounded-lg bg-slate-50 px-3 py-2">{{ $produit->nom }} x {{ $produit->pivot->quantite }}</li>
                        @endforeach
                    </ul>
                </div>
                @if ($commande->canBeManagedByAdmin())
                    <div class="grid gap-3 lg:grid-cols-2">
                        <form method="POST" action="{{ route('admin.commandes.confirm', $commande) }}" data-async>
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="store-btn-primary w-full">Confirmer la commande</button>
                        </form>
                        <form method="POST" action="{{ route('admin.commandes.reject', $commande) }}" class="space-y-2" data-async>
                            @csrf
                            @method('PATCH')
                            <textarea name="rejection_reason" rows="2" class="store-textarea" placeholder="Raison precise du rejet"></textarea>
                            <button type="submit" class="store-btn-danger w-full">Rejeter la commande</button>
                        </form>
                    </div>
                @endif
                @if ($commande->rejection_reason)
                    <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 p-3 text-sm text-rose-700">{{ $commande->rejection_reason }}</div>
                @endif
            </article>
        @endforeach
    </section>

    <div class="mt-8">{{ $commandes->links() }}</div>
@endsection
