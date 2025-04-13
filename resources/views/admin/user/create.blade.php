<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Add new user') }}
        </h2>
        <a class="btn btn-soft btn-info" href="{{ route('admin.users.index') }}">{{ __('Back') }}</a>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.users.store') }}" method="post">
                        @csrf
                        <div>
                            <x-inputs.label value="{{ __('Name') }}" for="name" />
                            <x-inputs.text id="name" class="mt-2 w-full" name="name" type="text" :value="old('name')" autofocus placeholder="{{ __('Name') }}" />
                            <x-inputs.input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>
                        <div class="mt-4">
                            <x-inputs.label value="{{ __('Gender') }}" for="gender" />
                            <x-inputs.select-input id="gender" class="mt-2 w-full" name="gender">
                                <option value="male">{{ __('Male') }}</option>
                                <option value="female">{{ __('Female') }}</option>
                            </x-inputs.select-input>
                            <x-inputs.input-error class="mt-2" :messages="$errors->get('gender')" />
                        </div>
                        <div class="mt-4">
                            <x-inputs.label value="{{ __('Email') }}" for="email" />
                            <x-inputs.text id="email" class="mt-2 w-full" name="email" type="email" :value="old('email')" placeholder="{{ __('Email') }}" />
                            <x-inputs.input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>
                        <div class="mt-4">
                            <x-inputs.label value="{{ __('Role') }}" for="role_id" />
                            <x-inputs.select-input id="role_id" class="mt-2 w-full" name="role_id">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </x-inputs.select-input>
                            <x-inputs.input-error class="mt-2" :messages="$errors->get('role_id')" />
                        </div>
                        <div class="mt-4">
                            <x-inputs.label value="{{ __('Status') }}" for="status" />
                            <x-inputs.select-input id="status" class="mt-2 w-full" name="status">
                                <option value="1">{{ __('Active') }}</option>
                                <option value="0">{{ __('Deactivate') }}</option>
                            </x-inputs.select-input>
                            <x-inputs.input-error class="mt-2" :messages="$errors->get('status')" />
                        </div>
                        <div class="mt-6">
                            <x-buttons.primary type="submit">
                                {{ __('Save') }}
                            </x-buttons.primary>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</x-app-layout>
