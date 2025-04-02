<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input name="token" type="hidden" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-inputs.input-label for="email" :value="__('Email')" />
            <x-inputs.text id="email" class="mt-1 block w-full" name="email" type="email" required :value="old('email', $request->email)" autofocus autocomplete="username" />
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
            <x-buttons.primary-button>
                {{ __('Reset Password') }}
            </x-buttons.primary-button>
        </div>
    </form>
</x-guest-layout>
