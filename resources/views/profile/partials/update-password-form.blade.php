<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form class="mt-6 space-y-6" method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div>
            <x-inputs.label for="update_password_current_password" :value="__('Current Password')" />
            <x-inputs.text id="update_password_current_password" class="mt-1 block w-full" name="current_password" type="password" autocomplete="current-password" />
            <x-inputs.error class="mt-2" :messages="$errors->updatePassword->get('current_password')" />
        </div>

        <div>
            <x-inputs.label for="update_password_password" :value="__('New Password')" />
            <x-inputs.text id="update_password_password" class="mt-1 block w-full" name="password" type="password" autocomplete="new-password" />
            <x-inputs.error class="mt-2" :messages="$errors->updatePassword->get('password')" />
        </div>

        <div>
            <x-inputs.label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <x-inputs.text id="update_password_password_confirmation" class="mt-1 block w-full" name="password_confirmation" type="password" autocomplete="new-password" />
            <x-inputs.error class="mt-2" :messages="$errors->updatePassword->get('password_confirmation')" />
        </div>

        <div class="flex items-center gap-4">
            <x-buttons.primary>{{ __('Save') }}</x-buttons.primary>

            @if (session('status') === 'password-updated')
                <p class="text-sm text-gray-600" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
