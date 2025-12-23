<x-actions.modal class="border border-gray-300 dark:border-gray-700" :id="'createCategory'">
    <form action="{{ route('admin.category.store') }}" method="POST">
        @csrf

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Add new category') }}
        </h2>

        <div class="mt-6">
            <x-inputs.label value="{{ __('Name') }}" for="name" />
            <x-inputs.text id="name" class="mt-2 w-full" name="name" type="text" :value="old('name')" autofocus placeholder="{{ __('Category name') }}" />
            <x-inputs.error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="mt-6">
            <x-inputs.label value="{{ __('Parent category') }}" for="parent_id" />
            <div class="mt-1"></div>
            <x-inputs.select-input id="parent_id" class="select-search-modal mt-2 w-full" name="parent_id">
                @foreach ($categoriesWithNoParent as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </x-inputs.select-input>
            <x-inputs.error class="mt-2" :messages="$errors->get('parent_id')" />
        </div>

        <div class="mt-6">
            <x-inputs.label value="{{ __('Status') }}" for="status" />
            <div class="mt-1"></div>
            <x-inputs.select-input id="status" class="mt-2 w-full" name="status">
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>{{ __('Inactive') }}</option>
            </x-inputs.select-input>
            <x-inputs.error class="mt-2" :messages="$errors->get('status')" />
        </div>

        <div class="modal-action">
            <x-buttons.success class="ms-3">
                {{ __('Xác nhận') }}
            </x-buttons.success>
        </div>
    </form>
</x-actions.modal>
