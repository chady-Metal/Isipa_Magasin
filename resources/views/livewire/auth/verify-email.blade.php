<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="mt-4 flex flex-col gap-6">
    <x-auth-header
        title="Verification de votre adresse email"
        description="Cliquez sur le lien recu par email pour activer completement votre compte."
    />

    <div class="rounded-xl bg-[var(--isipa-soft)] p-4 text-center text-sm text-[var(--isipa-ink)]">
        {{ __('Un lien de verification vous a ete envoye. Verifiez votre boite de reception.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-center text-sm font-medium text-emerald-700">
            {{ __('Un nouveau lien de verification vient d etre envoye.') }}
        </div>
    @endif

    <div class="flex flex-col items-center justify-between space-y-3">
        <flux:button wire:click="sendVerification" variant="primary" class="w-full !rounded-xl !bg-[var(--isipa-primary)] hover:!bg-[#1d22b8]">
            {{ __('Renvoyer l email de verification') }}
        </flux:button>

        <button
            wire:click="logout"
            type="submit"
            class="rounded-md text-sm font-medium text-slate-600 underline hover:text-[var(--isipa-primary)]"
        >
            {{ __('Se deconnecter') }}
        </button>
    </div>
</div>
