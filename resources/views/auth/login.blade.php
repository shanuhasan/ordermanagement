<x-guest-layout>
    <span class="pa-badge">Company login</span>
    <h2>Welcome back</h2>
    <p class="pa-sub">Sign in to manage your orders and payments.</p>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="pa-field">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus
                autocomplete="username" placeholder="you@company.com" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div class="pa-field">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password"
                placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="pa-row">
            <label for="remember_me">
                <input id="remember_me" type="checkbox" name="remember">
                <span>{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">{{ __('Forgot password?') }}</a>
            @endif
        </div>

        <x-primary-button>{{ __('Sign in') }}</x-primary-button>
    </form>
</x-guest-layout>
