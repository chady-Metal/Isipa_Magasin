@extends('store.admin.layouts.app')

@section('content')
    <section class="mb-6">
        <p class="section-tag">Administration</p>
        <h1 class="section-title mt-3">Clients</h1>
        <p class="section-subtitle">Suppression encadree avec justification obligatoire.</p>
    </section>

    <section class="space-y-4">
        @foreach ($clients as $client)
            <article class="admin-card">
                <div class="grid gap-4 lg:grid-cols-[1fr_auto] lg:items-center">
                    <div>
                        <h2 class="text-lg font-black text-[var(--isipa-admin-ink)]">{{ $client->name }}</h2>
                        <p class="text-sm text-slate-500">{{ $client->email }} | {{ $client->numeroTelephone }}</p>
                    </div>
                    <form method="POST" action="{{ route('admin.clients.destroy', $client) }}" class="grid gap-2 lg:w-[22rem]" data-async>
                        @csrf
                        @method('DELETE')
                        <input type="text" name="justification" class="store-input" placeholder="Justification obligatoire" required />
                        <button type="submit" class="store-btn-danger">Supprimer le compte</button>
                    </form>
                </div>
            </article>
        @endforeach
    </section>

    <div class="mt-8">{{ $clients->links() }}</div>
@endsection
