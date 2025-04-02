<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-inputs.input-label for="email" :value="__('Email')" />
            <x-inputs.text id="email" class="mt-1 block w-full" name="email" type="email" required :value="old('email')" autofocus />
            <x-inputs.input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <div class="mt-4 flex items-center justify-end">
            <x-buttons.primary-button>
                {{ __('Email Password Reset Link') }}
            </x-buttons.primary-button>
        </div>
    </form>
</x-guest-layout>
