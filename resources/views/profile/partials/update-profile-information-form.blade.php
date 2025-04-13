<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form class="mt-6 space-y-6" method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div>
            <x-inputs.label for="name" :value="__('Name')" />
            <x-inputs.text id="name" class="mt-1 block w-full" name="name" type="text" required :value="old('name', $user->name)" autofocus autocomplete="name" />
            <x-inputs.error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-inputs.label for="email" :value="__('Email')" />
            <x-inputs.text id="email" class="mt-1 block w-full" name="email" type="email" required :value="old('email', $user->email)" autocomplete="username" />
            <x-inputs.error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="mt-2 text-sm text-gray-800">
                        {{ __('Your email address is unverified.') }}

                        <button class="focus:outline-hidden rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2" form="send-verification">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4">
            <x-buttons.primary>{{ __('Save') }}</x-buttons.primary>

            @if (session('status') === 'profile-updated')
                <p class="text-sm text-gray-600" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
