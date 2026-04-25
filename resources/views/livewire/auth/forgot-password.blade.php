<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        Password::sendResetLink($this->only('email'));

        session()->flash('status', __('A reset link will be sent if the account exists.'));
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header title="Recuperation du mot de passe" description="Entrez votre email pour recevoir un lien de reinitialisation" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">
        <!-- Email Address -->
        <div class="grid gap-2">
            <flux:input wire:model="email" label="{{ __('Email Address') }}" type="email" name="email" required autofocus placeholder="email@example.com" />
        </div>

        <flux:button variant="primary" type="submit" class="w-full !rounded-xl !bg-[var(--isipa-primary)] hover:!bg-[#1d22b8]">{{ __('Envoyer le lien') }}</flux:button>
    </form>

    <div class="space-x-1 text-center text-sm text-slate-600">
        Retour a
        <x-text-link class="font-semibold text-[var(--isipa-primary)]" href="{{ route('login') }}">la connexion</x-text-link>
    </div>
</div>
