@extends('store.layouts.app')

@section('content')
    <section class="mb-6">
        <p class="section-tag">Historique</p>
        <h1 class="section-title mt-3">Mes Commandes</h1>
        <p class="section-subtitle">Suivez vos commandes, leurs statuts, le code de suivi et l’historique complet de vos achats.</p>
    </section>

    <div class="mb-6 grid gap-3 sm:grid-cols-3">
        <div class="metric-card"><p class="metric-label">Achats totalises</p><p class="metric-value">{{ number_format($totalSpent, 2, ',', ' ') }} $</p></div>
        <div class="metric-card"><p class="metric-label">Commandes</p><p class="metric-value">{{ $commandes->count() }}</p></div>
        <div class="metric-card"><p class="metric-label">En attente</p><p class="metric-value">{{ $commandes->where('statut', 'en_attente')->count() }}</p></div>
    </div>

    @if (!empty($notifications) && $notifications->count())
        <section class="mb-6 space-y-2">
            <h2 class="text-sm font-black uppercase tracking-wide text-slate-600">Notifications recentes</h2>
            @foreach ($notifications as $notification)
                <div class="glass-panel p-3 text-sm text-slate-700">
                    <p class="font-semibold">{{ data_get($notification->data, 'title', 'Notification') }}</p>
                    <p>{{ data_get($notification->data, 'message') }}</p>
                    @if (data_get($notification->data, 'raison'))
                        <p class="mt-1 text-rose-700"><strong>Raison :</strong> {{ data_get($notification->data, 'raison') }}</p>
                    @endif
                </div>
            @endforeach
        </section>
    @endif

    <section class="space-y-4">
        @forelse ($commandes as $commande)
            <article class="store-card store-card-rich">
                <div class="flex flex-col gap-3 border-b border-slate-200 pb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Commande #{{ $commande->id }} | {{ $commande->date_commande?->format('d/m/Y') }}</p>
                        <p class="mt-1 text-sm text-slate-500">Code de suivi: <span class="font-semibold">{{ $commande->tracking_code }}</span></p>
                        <p class="mt-2">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $commande->statutBadgeClass() }}">{{ $commande->statutLabel() }}</span>
                        </p>
                    </div>
                    @if ($commande->canBeCancelled())
                        <form method="POST" action="{{ route('client.commandes.cancel', $commande) }}" data-async>
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="store-btn-danger">Annuler</button>
                        </form>
                    @endif
                </div>

                <div class="mt-4 grid gap-4 lg:grid-cols-2">
                    <div>
                        <h2 class="mb-2 text-sm font-bold uppercase tracking-wide text-slate-500">Produits</h2>
                        <ul class="space-y-2 text-sm text-slate-700">
                            @foreach ($commande->produits as $produit)
                                <li class="rounded-lg bg-slate-50 px-3 py-2">{{ $produit->nom }} x {{ $produit->pivot->quantite }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-2 text-sm font-bold uppercase tracking-wide text-slate-500">Paiement</h2>
                        @if ($commande->paiement)
                            <div class="rounded-lg bg-slate-50 p-3 text-sm text-slate-700">
                                <p>Methode: {{ $commande->paiement->methode_paiement }}</p>
                                <p>Montant: {{ number_format($commande->paiement->montant, 2, ',', ' ') }} $</p>
                                <p>Reference: {{ $commande->paiement->reference_transaction }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($commande->rejection_reason)
                    <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 p-3 text-sm text-rose-700">
                        <strong>Commande rejetee :</strong> {{ $commande->rejection_reason }}
                    </div>
                @endif
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-600">
                Vous n'avez pas encore passe de commande.
            </div>
        @endforelse
    </section>

    <section class="glass-panel mt-8 p-5">
        <h2 class="text-xl font-black text-[var(--isipa-ink)]">Donner un avis sur le service</h2>
        <form method="POST" action="{{ route('client.avis.service.store') }}" class="mt-4 grid gap-3 md:grid-cols-[0.35fr_1fr_auto]" data-async>
            @csrf
            <select name="note" class="store-select" required>
                <option value="">Note</option>
                @for ($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}">{{ $i }}/5</option>
                @endfor
            </select>
            <input type="text" name="commentaire" class="store-input" placeholder="Votre avis sur le service ISIPA Store" required />
            <button type="submit" class="store-btn-primary">Envoyer</button>
        </form>
    </section>
@endsection
