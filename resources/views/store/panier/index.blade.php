@extends('store.layouts.app')

@section('content')
    <section class="mb-6 reveal-up">
        <p class="section-tag">Espace Client</p>
        <h1 class="section-title mt-3">Mon Panier</h1>
        <p class="section-subtitle">Ajustez vos quantites et validez votre commande avec le mode de paiement de votre choix.</p>
    </section>

    <div class="mb-6 grid gap-3 sm:grid-cols-3">
        <div class="metric-card reveal-up reveal-delay-1">
            <p class="metric-label">Articles</p>
            <p class="metric-value">{{ $panier->produits->count() }}</p>
        </div>
        <div class="metric-card reveal-up reveal-delay-1">
            <p class="metric-label">Total</p>
            <p class="metric-value">{{ number_format($total, 2, ',', ' ') }} $</p>
        </div>
        <div class="metric-card reveal-up reveal-delay-2">
            <p class="metric-label">Etat</p>
            <p class="mt-2 text-sm font-semibold text-slate-700">
                <span class="status-dot {{ $panier->produits->isEmpty() ? 'status-dot-warn' : 'status-dot-success' }} mr-2"></span>
                {{ $panier->produits->isEmpty() ? 'Panier vide' : 'Pret pour commande' }}
            </p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        <section class="space-y-4 lg:col-span-2">
            @forelse ($panier->produits as $produit)
                <article class="store-card store-card-rich reveal-up reveal-delay-2">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-[var(--isipa-ink)]">{{ $produit->nom }}</h2>
                            <p class="text-sm text-slate-500">Prix unitaire: {{ number_format($produit->prix, 2, ',', ' ') }} $</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <form method="POST" action="{{ route('client.panier.update', $produit) }}" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <input type="number" name="quantite" min="1" max="{{ $produit->stock }}" value="{{ $produit->pivot->quantite }}" class="store-input !w-24 !py-2" />
                                <button type="submit" class="store-btn-secondary">Modifier</button>
                            </form>
                            <form method="POST" action="{{ route('client.panier.remove', $produit) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-xl border border-rose-300 px-3 py-2 text-sm font-semibold text-rose-600 transition hover:bg-rose-50">Retirer</button>
                            </form>
                        </div>
                    </div>
                    <p class="mt-3 text-sm font-semibold text-[var(--isipa-primary)]">
                        Sous-total: {{ number_format($produit->prix * $produit->pivot->quantite, 2, ',', ' ') }} $
                    </p>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-600">
                    Votre panier est vide. <a class="font-bold text-[var(--isipa-primary)] underline" href="{{ route('catalogue.index') }}">Voir le catalogue</a>
                </div>
            @endforelse
        </section>

        <aside class="glass-panel reveal-up reveal-delay-3 h-fit p-5">
            <h2 class="text-xl font-black text-[var(--isipa-ink)]">Validation de commande</h2>
            <p class="mt-2 text-sm text-slate-600">Total a payer: <span class="font-black text-[var(--isipa-primary)]">{{ number_format($total, 2, ',', ' ') }} $</span></p>

            <form method="POST" action="{{ route('client.commandes.store') }}" class="mt-5 space-y-4">
                @csrf
                <div>
                    <label class="mb-1 block text-sm font-semibold">Adresse de livraison</label>
                    <input type="text" name="adresse_livraison" class="store-input" required />
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold">Methode de paiement</label>
                    <select name="methode_paiement" class="store-select" required>
                        <option value="mobile-money">Mobile Money</option>
                        <option value="carte">Carte bancaire</option>
                        <option value="virement">Virement</option>
                    </select>
                </div>
                <div>
                    <label class="mb-1 block text-sm font-semibold">Numero du compte / telephone</label>
                    <input type="text" name="numero_compte" class="store-input" required />
                </div>
                <button type="submit" class="store-btn-primary w-full">Passer la commande</button>
            </form>
        </aside>
    </div>
@endsection
