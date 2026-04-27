@extends('store.admin.layouts.app')

@section('content')
    <section class="mb-6">
        <p class="section-tag">Administration</p>
        <h1 class="section-title mt-3">Reclamations Clients</h1>
        <p class="section-subtitle">Les administrateurs et gerants repondent ici aux reclamations des clients.</p>
    </section>

    <section class="space-y-4">
        @foreach ($reclamations as $reclamation)
            <article class="admin-card">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                    <div>
                        <h2 class="text-lg font-black text-[var(--isipa-admin-ink)]">{{ $reclamation->produit->nom ?? 'Produit' }}</h2>
                        <p class="text-sm text-slate-500">{{ $reclamation->user->name ?? 'Client' }} | {{ $reclamation->created_at->format('d/m/Y H:i') }}</p>
                        <p class="mt-3 text-sm text-slate-700">{{ $reclamation->message }}</p>
                    </div>
                    <span class="rounded-full bg-[var(--isipa-soft)] px-3 py-1 text-xs font-semibold text-[var(--isipa-primary)]">{{ $reclamation->statut }}</span>
                </div>
                <form method="POST" action="{{ route('admin.reclamations.reply', $reclamation) }}" class="mt-4 grid gap-3 lg:grid-cols-[0.3fr_1fr_auto]" data-async>
                    @csrf
                    @method('PATCH')
                    <select name="statut" class="store-select">
                        <option value="en attente">En attente</option>
                        <option value="en cours">En cours</option>
                        <option value="traitee">Traitee</option>
                    </select>
                    <input type="text" name="admin_response" value="{{ $reclamation->admin_response }}" class="store-input" placeholder="Reponse au client" required />
                    <button type="submit" class="store-btn-primary">Repondre</button>
                </form>
            </article>
        @endforeach
    </section>

    <div class="mt-8">{{ $reclamations->links() }}</div>
@endsection
