<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form class="mt-6 space-y-6" method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div>
            <x-inputs.input-label for="update_password_current_password" :value="__('Current Password')" />
            <x-inputs.text-input id="update_password_current_password" class="mt-1 block w-full" name="current_password" type="password" autocomplete="current-password" />
            <x-inputs.input-error class="mt-2" :messages="$errors->updatePassword->get('current_password')" />
        </div>

        <div>
            <x-inputs.input-label for="update_password_password" :value="__('New Password')" />
            <x-inputs.text-input id="update_password_password" class="mt-1 block w-full" name="password" type="password" autocomplete="new-password" />
            <x-inputs.input-error class="mt-2" :messages="$errors->updatePassword->get('password')" />
        </div>

        <div>
            <x-inputs.input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <x-inputs.text-input id="update_password_password_confirmation" class="mt-1 block w-full" name="password_confirmation" type="password" autocomplete="new-password" />
            <x-inputs.input-error class="mt-2" :messages="$errors->updatePassword->get('password_confirmation')" />
        </div>

        <div class="flex items-center gap-4">
            <x-buttons.primary-button>{{ __('Save') }}</x-buttons.primary-button>

            @if (session('status') === 'password-updated')
                <p class="text-sm text-gray-600 dark:text-gray-400" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
