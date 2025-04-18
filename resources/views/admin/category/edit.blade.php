<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Edit Category') }}
        </h2>
        <a class="btn btn-soft btn-info" href="{{ route('admin.category.index') }}">{{ __('Back') }}</a>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.category.update', $category->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div>
                            <x-inputs.label value="{{ __('Name') }}" for="name" />
                            <x-inputs.text id="name" class="mt-2 w-full" name="name" type="text" :value="old('name', $category->name)" autofocus placeholder="{{ __('Name') }}" />
                            <x-inputs.error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div class="mt-6">
                            <x-inputs.label value="{{ __('Parent category') }}" for="parent_id" />
                            <div class="mt-1"></div>
                            <x-inputs.select-input id="parent_id" class="select-search mt-2 w-full" name="parent_id">
                                <option value="" {{ old('parent_id', $category->parent_id) == null ? 'selected' : '' }}>{{ __('No parent') }}</option>
                                @foreach ($categoriesWithNoParent as $categoryWithNoParent)
                                    <option value="{{ $categoryWithNoParent->id }}" {{ old('parent_id', $category->parent_id) == $categoryWithNoParent->id ? 'selected' : '' }}>
                                        {{ $categoryWithNoParent->name }}
                                    </option>
                                @endforeach
                            </x-inputs.select-input>
                            <x-inputs.error class="mt-2" :messages="$errors->get('parent_id')" />
                        </div>

                        <div class="mt-4">
                            <x-inputs.label value="{{ __('Status') }}" for="status" />
                            <x-inputs.select-input id="status" class="mt-2 w-full" name="status">
                                <option value="active" {{ old('status', $category->status) == '1' ? 'selected' : '' }}>{{ __('Active') }}</option>
                                <option value="inactive" {{ old('status', $category->status) == '0' ? 'selected' : '' }}>{{ __('Deactivate') }}</option>
                            </x-inputs.select-input>
                            <x-inputs.error class="mt-2" :messages="$errors->get('status')" />
                        </div>

                        <div class="mt-6">
                            <x-buttons.primary type="submit">
                                {{ __('Update') }}
                            </x-buttons.primary>
                        </div>
                    </form>
                </div>
            </div>
        </div>
</x-app-layout>
