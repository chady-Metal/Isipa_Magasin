@extends('store.admin.layouts.app')

@section('content')
    <section class="mb-6 flex items-end justify-between">
        <div>
            <p class="section-tag">Administration</p>
            <h1 class="section-title mt-3">Modifier {{ $produit->nom }}</h1>
        </div>
        <a href="{{ route('admin.produits.index') }}" class="store-btn-secondary" data-async-link>Retour</a>
    </section>

    <section class="admin-card max-w-5xl">
        <form method="POST" action="{{ route('admin.produits.update', $produit) }}" class="grid gap-4 sm:grid-cols-2" data-async>
            @csrf
            @method('PUT')
            @include('store.admin.produits.partials.form-fields', ['produit' => $produit])
            <div class="sm:col-span-2 mt-2">
                <button type="submit" class="store-btn-primary w-full">Mettre a jour le produit</button>
            </div>
        </form>
    </section>
@endsection
