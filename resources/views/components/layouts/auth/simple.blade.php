<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="auth-bg min-h-screen antialiased">
        <div class="relative flex min-h-screen items-center justify-center p-4 sm:p-8">
            <div class="auth-shape auth-shape-a"></div>
            <div class="auth-shape auth-shape-b"></div>

            <div class="relative grid w-full max-w-6xl overflow-hidden rounded-[2rem] border border-white/40 bg-white/70 shadow-[0_24px_80px_rgba(15,38,110,0.22)] backdrop-blur-xl lg:grid-cols-2">
                <section class="hidden flex-col justify-between bg-gradient-to-br from-[var(--isipa-primary)] via-[#3048de] to-[var(--isipa-secondary)] p-10 text-white lg:flex">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 text-xl font-black tracking-tight" wire:navigate>
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-white/20">
                            <x-app-logo-icon class="size-8 fill-current text-white" />
                        </span>
                        ISIPA Store
                    </a>

                    <div>
                        <p class="mb-4 inline-flex rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em]">Plateforme officielle</p>
                        <h2 class="text-4xl font-black leading-tight">Connectez-vous a votre espace intelligent</h2>
                        <p class="mt-4 max-w-md text-sm text-blue-100">
                            Commandez du materiel informatique, suivez vos achats, et gerez votre experience client dans une interface moderne et professionnelle.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="rounded-xl bg-white/15 p-3">Catalogue dynamique</div>
                        <div class="rounded-xl bg-white/15 p-3">Paiement integre</div>
                        <div class="rounded-xl bg-white/15 p-3">Suivi commandes</div>
                        <div class="rounded-xl bg-white/15 p-3">Support reclamations</div>
                    </div>
                </section>

                <section class="flex items-center justify-center p-5 sm:p-10">
                    <div class="w-full max-w-md">
                        <a href="{{ route('home') }}" class="mb-8 flex items-center justify-center gap-2 font-semibold text-[var(--isipa-ink)] lg:hidden" wire:navigate>
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-[var(--isipa-primary)] text-white">
                                <x-app-logo-icon class="size-6 fill-current text-white" />
                            </span>
                            ISIPA Store
                        </a>

                        <div class="auth-form-shell">
                            {{ $slot }}
                        </div>
                    </div>
                </section>
            </div>
        </div>
        @fluxScripts
    </body>
</html>
