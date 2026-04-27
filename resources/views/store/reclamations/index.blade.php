@extends('store.layouts.app')

@section('content')
    <section class="mb-6">
        <p class="section-tag">Support Client</p>
        <h1 class="section-title mt-3">Reclamations</h1>
        <p class="section-subtitle">Signalez un probleme sur un produit et suivez la reponse de l’administration.</p>
    </section>

    <div class="grid gap-6 lg:grid-cols-3">
        <section class="glass-panel h-fit p-5 lg:col-span-1">
            <h2 class="text-lg font-black text-[var(--isipa-ink)]">Nouvelle reclamation</h2>
            <form method="POST" action="{{ route('client.reclamations.store') }}" class="mt-4 space-y-4" data-async>
                @csrf
                <select name="produit_id" class="store-select" required>
                    <option value="">Selectionnez un produit</option>
                    @foreach ($produits as $produit)
                        <option value="{{ $produit->id }}">{{ $produit->nom }}</option>
                    @endforeach
                </select>
                <textarea name="message" rows="4" class="store-textarea" placeholder="Expliquez votre probleme..." required></textarea>
                <button type="submit" class="store-btn-primary w-full">Envoyer</button>
            </form>
        </section>

        <section class="space-y-4 lg:col-span-2">
            @forelse ($reclamations as $reclamation)
                <article class="store-card store-card-rich">
                    <div class="flex items-center justify-between gap-3">
                        <h2 class="font-bold text-[var(--isipa-ink)]">{{ $reclamation->produit->nom }}</h2>
                        <span class="rounded-full bg-[var(--isipa-soft)] px-3 py-1 text-xs font-semibold text-[var(--isipa-primary)]">{{ $reclamation->statut }}</span>
                    </div>
                    <p class="mt-3 text-sm text-slate-600">{{ $reclamation->message }}</p>
                    @if ($reclamation->admin_response)
                        <div class="mt-4 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                            <p class="font-semibold">Reponse administrateur</p>
                            <p class="mt-1">{{ $reclamation->admin_response }}</p>
                            <p class="mt-2 text-xs">Par {{ $reclamation->responder->name ?? 'Administration' }} le {{ optional($reclamation->responded_at)->format('d/m/Y H:i') }}</p>
                        </div>
                    @endif
                    <p class="mt-2 text-xs text-slate-500">{{ $reclamation->created_at->format('d/m/Y H:i') }}</p>
                </article>
            @empty
                <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-600">
                    Aucune reclamation envoyee pour l'instant.
                </div>
            @endforelse
        </section>
    </div>
@endsection
