<x-actions.modal class="border border-gray-300 dark:border-gray-700" :id="'createClass'">
    <form action="{{ route('admin.class.store') }}" method="POST">
        @csrf

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Add new class') }}
        </h2>

        <div class="mt-6">
            <x-inputs.input-label value="{{ __('Code') }}" for="code" />
            <x-inputs.text id="code" class="mt-2 w-full" name="code" type="text" :value="old('code')" autofocus placeholder="{{ __('Class code') }}" />
            <x-inputs.input-error class="mt-2" :messages="$errors->get('code')" />
        </div>

        <div class="mt-6">
            <x-inputs.input-label value="{{ __('Name') }}" for="name" />
            <x-inputs.text id="name" class="mt-2 w-full" name="name" type="text" :value="old('name')" autofocus placeholder="{{ __('Class name') }}" />
            <x-inputs.input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="mt-6">
            <x-inputs.input-label value="{{ __('Categories') }}" for="categories" />
            <div class="mt-1"></div>
            <x-inputs.select-input id="categories" class="select-search-modal mt-2 w-full" name="categories[]" multiple>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </x-inputs.select-input>
            <x-inputs.input-error class="mt-2" :messages="$errors->get('categories')" />
        </div>

        <div class="mt-6">
            <x-inputs.input-label value="{{ __('Description') }}" for="description" />
            <x-inputs.area id="description" class="mt-2 w-full" name="description" placeholder="{{ __('Class description') }}">
                {{ old('description') }}
            </x-inputs.area>
            <x-inputs.input-error class="mt-2" :messages="$errors->get('description')" />
        </div>

        <div class="mt-6">
            <x-inputs.input-label value="{{ __('Teacher') }}" for="teacher" />
            <div class="mt-1"></div>
            <x-inputs.select-input id="teacher" class="select-search-modal mt-2 w-full" name="teacher_id">
                @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                @endforeach
            </x-inputs.select-input>
            <x-inputs.input-error class="mt-2" :messages="$errors->get('teacher_id')" />
        </div>

        <div class="modal-action">
            <x-buttons.success class="ms-3">
                {{ __('Xác nhận') }}
            </x-buttons.success>
        </div>
    </form>
</x-actions.modal>
