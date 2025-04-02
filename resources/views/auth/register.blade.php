<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-inputs.input-label for="name" :value="__('Name')" />
            <x-inputs.text id="name" class="mt-1 block w-full" name="name" type="text" required :value="old('name')" autofocus autocomplete="name" />
            <x-inputs.input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-inputs.input-label for="email" :value="__('Email')" />
            <x-inputs.text id="email" class="mt-1 block w-full" name="email" type="email" required :value="old('email')" autocomplete="username" />
            <x-inputs.input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-inputs.input-label for="password" :value="__('Password')" />

            <x-inputs.text id="password" class="mt-1 block w-full" name="password" type="password" required autocomplete="new-password" />

            <x-inputs.input-error class="mt-2" :messages="$errors->get('password')" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-inputs.input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-inputs.text id="password_confirmation" class="mt-1 block w-full" name="password_confirmation" type="password" required autocomplete="new-password" />

            <x-inputs.input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
        </div>

        <div class="mt-4 flex items-center justify-end">
            <a class="focus:outline-hidden rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-buttons.primary-button class="ms-4">
                {{ __('Register') }}
            </x-buttons.primary-button>
        </div>
    </form>
</x-guest-layout>
