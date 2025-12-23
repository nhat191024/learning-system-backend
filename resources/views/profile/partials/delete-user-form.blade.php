<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-buttons.danger onclick="confirmUserDeletion.showModal()">{{ __('Delete Account') }}</x-buttons.danger>

    <x-actions.modal class="border border-gray-300 dark:border-gray-700" name="confirm-user-deletion" :id="'confirmUserDeletion'">
        <form method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-inputs.label value="{{ __('Password') }}" for="password" />
                <x-inputs.text id="password" class="mt-2 w-full" name="password" type="password" placeholder="{{ __('Password') }}" />
                <x-inputs.error class="mt-2" :messages="$errors->userDeletion->get('password')" />
            </div>

            <div class="modal-action">
                <x-buttons.danger class="ms-3">
                    {{ __('Delete Account') }}
                </x-buttons.danger>
            </div>
        </form>
    </x-actions.modal>
</section>
