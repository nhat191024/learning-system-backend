<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-buttons.danger-button onclick="confirmUserDeletion.showModal()">{{ __('Delete Account') }}</x-buttons.danger-button>

    <x-actions.modal :id="'confirmUserDeletion'">
        <form method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-inputs.input-label class="sr-only" value="{{ __('Password') }}" for="password" />

                <x-inputs.text-input id="password" class="mt-1 block w-3/4" name="password" type="password" placeholder="{{ __('Password') }}" />

                <x-inputs.input-error class="mt-2" :messages="$errors->userDeletion->get('password')" />
            </div>

            <div class="modal-action">
                <x-buttons.danger-button class="ms-3">
                    {{ __('Delete Account') }}
                </x-buttons.danger-button>
            </div>
        </form>
    </x-actions.modal>
</section>
