@extends('store.admin.layouts.app')

@section('content')
    <section class="mb-6">
        <p class="section-tag">Messagerie interne</p>
        <h1 class="section-title mt-3">Messages Administrateurs</h1>
        <p class="section-subtitle">Communication entre super administrateur, administrateurs et gerants.</p>
    </section>

    <section class="grid gap-6 xl:grid-cols-[0.85fr_1.15fr]">
        <article class="admin-card">
            <h2 class="text-xl font-black text-[var(--isipa-admin-ink)]">Nouveau message</h2>
            <form method="POST" action="{{ route('admin.messages.store') }}" class="mt-4 space-y-3" data-async>
                @csrf
                <select name="recipient_id" class="store-select">
                    <option value="">Tous les administrateurs</option>
                    @foreach ($admins as $admin)
                        <option value="{{ $admin->id }}">{{ $admin->name }} - {{ $admin->role->nom }}</option>
                    @endforeach
                </select>
                <input type="text" name="subject" class="store-input" placeholder="Objet" required />
                <textarea name="message" rows="5" class="store-textarea" placeholder="Votre message" required></textarea>
                <button type="submit" class="store-btn-primary w-full">Envoyer</button>
            </form>
        </article>

        <article class="admin-card">
            <h2 class="text-xl font-black text-[var(--isipa-admin-ink)]">Historique des messages</h2>
            <div class="mt-4 space-y-3">
                @foreach ($messages as $message)
                    <div class="rounded-2xl bg-slate-50 p-4 text-sm text-slate-700">
                        <div class="flex items-center justify-between gap-3">
                            <p class="font-semibold">{{ $message->subject }}</p>
                            <span class="text-xs text-slate-500">{{ $message->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <p class="mt-1 text-slate-500">De {{ $message->sender->name ?? 'Admin' }} vers {{ $message->recipient->name ?? 'Tous' }}</p>
                        <p class="mt-2">{{ $message->message }}</p>
                    </div>
                @endforeach
            </div>
            <div class="mt-8">{{ $messages->links() }}</div>
        </article>
    </section>
@endsection
