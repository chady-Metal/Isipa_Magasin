<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>

<div class="flex flex-col gap-6">
    <x-auth-header title="Connexion a votre compte" description="Entrez vos identifiants pour acceder a la plateforme ISIPA Store" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
        @csrf
        <!-- Email Address -->
        <div class="grid gap-2">
            <label for="email" class="text-sm font-semibold text-slate-700">{{ __('Email address') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="email@example.com" class="store-input" />
            @error('email')
                <p class="text-sm text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="relative">
            <div class="grid gap-2">
                <label for="password" class="text-sm font-semibold text-slate-700">{{ __('Password') }}</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="Password"
                    class="store-input"
                />
                @error('password')
                    <p class="text-sm text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            @if (Route::has('password.request'))
                <x-text-link class="absolute right-0 top-0 font-semibold text-[var(--isipa-primary)]" href="{{ route('password.request') }}">
                    {{ __('Mot de passe oublie ?') }}
                </x-text-link>
            @endif
        </div>

        <!-- Remember Me -->
        <label class="inline-flex items-center gap-2 text-sm text-slate-700">
            <input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-[var(--isipa-primary)] focus:ring-[var(--isipa-secondary)]" />
            {{ __('Remember me') }}
        </label>

        <div class="flex items-center justify-end">
            <button type="submit" class="store-btn-primary w-full">{{ __('Se connecter') }}</button>
        </div>
    </form>

    <div class="space-x-1 text-center text-sm text-slate-600">
        Pas encore de compte ?
        <x-text-link class="font-semibold text-[var(--isipa-primary)]" href="{{ route('register') }}">Inscription</x-text-link>
    </div>
</div>
