@extends('store.layouts.app')

@section('content')
    <section class="mb-6 reveal-up">
        <p class="section-tag">Historique</p>
        <h1 class="section-title mt-3">Mes Commandes</h1>
        <p class="section-subtitle">Suivez vos commandes, leurs statuts et les details de paiement en un seul endroit.</p>
    </section>

    @if (!empty($notifications) && $notifications->count())
        <section class="mb-6 space-y-2 reveal-up reveal-delay-1">
            <h2 class="text-sm font-black uppercase tracking-wide text-slate-600">Notifications recentes</h2>
            @foreach ($notifications as $notification)
                <div class="glass-panel p-3 text-sm text-slate-700">
                    <p class="font-semibold">{{ data_get($notification->data, 'title', 'Notification') }}</p>
                    <p>{{ data_get($notification->data, 'message') }}</p>
                    @if (data_get($notification->data, 'raison'))
                        <p class="mt-1 text-rose-700"><strong>Raison:</strong> {{ data_get($notification->data, 'raison') }}</p>
                    @endif
                </div>
            @endforeach
        </section>
    @endif

    <section class="space-y-4">
        @forelse ($commandes as $commande)
            <article class="store-card store-card-rich reveal-up reveal-delay-2">
                <div class="flex flex-col gap-3 border-b border-slate-200 pb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-slate-500">Commande #{{ $commande->id }} - {{ $commande->date_commande }}</p>
                        <p class="mt-2">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $commande->statutBadgeClass() }}">
                                {{ $commande->statutLabel() }}
                            </span>
                        </p>
                    </div>
                    @if ($commande->canBeCancelled())
                        <form method="POST" action="{{ route('client.commandes.cancel', $commande) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="rounded-xl border border-rose-300 px-3 py-2 text-sm font-semibold text-rose-600 transition hover:bg-rose-50">Annuler</button>
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
                        @else
                            <p class="text-sm text-slate-500">Aucun paiement enregistre.</p>
                        @endif
                    </div>
                </div>

                @if ($commande->rejection_reason)
                    <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 p-3 text-sm text-rose-700">
                        <strong>Commande rejetee:</strong> {{ $commande->rejection_reason }}
                    </div>
                @endif
            </article>
        @empty
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-600">
                Vous n'avez pas encore passe de commande.
            </div>
        @endforelse
    </section>
@endsection
