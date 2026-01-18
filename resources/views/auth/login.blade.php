<x-guest-layout>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password"
                name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
        <label class="flex items-center gap-2 text-gray/80">
            <input id="remember_me" type="checkbox" class="rounded border-white/30 text-gray" name="remember">
            <span>{{ __('Remember me') }}</span>
        </label>
        </div>

        <!-- Forgot Password -->
        <div class="flex items-center justify-start mt-7 space-x-12">
        @if (Route::has('password.request'))
            <a class="text-sm text-gray/80 hover:underline" href="{{ route('password.request') }}">
                {{ __('Forgot your password?') }}
            </a>
        @endif

        <!-- Login & Register Buttons -->
        <div class="flex items-center justify-end mt-4 space-x-3">
            <x-primary-button>
                {{ __('Log in') }}
            </x-primary-button>

            <a href="{{ route('register') }}"
                class="inline-flex items-center justify-center px-4 py-3
                    bg-white/20 text-red  rounded-full
                    border border-white/40
                    hover:bg-blue/30
                    focus:outline-none focus:ring-2 focus:ring-blue focus:ring-offset-2
                    transition">
                {{ __('Register') }}
            </a>
        </div>
    </form>
</x-guest-layout>