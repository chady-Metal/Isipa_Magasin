@extends('store.layouts.app')

@section('content')
    <section class="mb-6 reveal-up">
        <p class="section-tag">Administration</p>
        <div class="mt-3 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="section-title">Commandes Clients</h1>
            <a href="{{ route('admin.produits.index') }}" class="store-btn-secondary">Voir les produits</a>
        </div>
        <p class="section-subtitle">Confirmez ou rejetez les commandes en attente. En cas de rejet, une raison precise est obligatoire.</p>
    </section>

    <section class="space-y-4">
        @forelse ($commandes as $commande)
            <article class="store-card store-card-rich reveal-up reveal-delay-2">
                <div class="mb-4 flex flex-col gap-2 border-b border-slate-200 pb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Commande #{{ $commande->id }} | Client: {{ $commande->user->name ?? 'N/A' }}</p>
                        <p class="text-sm text-slate-500">Adresse: {{ $commande->adresse_livraison }}</p>
                        <p class="mt-2">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $commande->statutBadgeClass() }}">
                                {{ $commande->statutLabel() }}
                            </span>
                        </p>
                    </div>
                    <div class="text-sm text-slate-600">
                        @if ($commande->paiement)
                            <p class="font-semibold">Paiement: {{ $commande->paiement->methode_paiement }}</p>
                            <p>Montant: {{ number_format($commande->paiement->montant, 2, ',', ' ') }} $</p>
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

                @if ($commande->rejection_reason)
                    <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 p-3 text-sm text-rose-700">
                        <strong>Raison du rejet:</strong> {{ $commande->rejection_reason }}
                    </div>
                @endif

                @if ($commande->canBeManagedByAdmin())
                    <div class="grid gap-3 lg:grid-cols-2">
                        <form method="POST" action="{{ route('admin.commandes.confirm', $commande) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="store-btn-primary w-full">Confirmer la commande</button>
                        </form>

                        <form method="POST" action="{{ route('admin.commandes.reject', $commande) }}" class="space-y-2">
                            @csrf
                            @method('PATCH')
                            <textarea name="rejection_reason" rows="2" class="store-textarea" placeholder="Raison precise du rejet (obligatoire)"></textarea>
                            <button type="submit" class="w-full rounded-xl border border-rose-300 px-4 py-2 text-sm font-semibold text-rose-700 transition hover:bg-rose-50">
                                Rejeter la commande
                            </button>
                        </form>
                    </div>
                @endif
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-600">
                Aucune commande client pour le moment.
            </div>
        @endforelse
    </section>

    <div class="mt-8">
        {{ $commandes->links() }}
    </div>
@endsection
