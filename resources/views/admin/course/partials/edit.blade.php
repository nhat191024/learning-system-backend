<x-actions.modal class="border border-gray-300 dark:border-gray-700" :id="'editClass'">
    <form action="{{ route('admin.course.update', $course->id) }}" method="POST">
        @csrf
        @method('PUT')

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Edit class') }} {{ $course->name }}
        </h2>

        <div class="mt-6">
            <x-inputs.label value="{{ __('Code') }}" for="code" />
            <x-inputs.text id="code" class="mt-2 w-full" name="code" type="text" required :value="old('code', $course->code)" autofocus placeholder="{{ __('Course code') }}" />
            <x-inputs.error class="mt-2" :messages="$errors->get('code')" />
        </div>

        <div class="mt-6">
            <x-inputs.label value="{{ __('Name') }}" for="name" />
            <x-inputs.text id="name" class="mt-2 w-full" name="name" type="text" required :value="old('name', $course->name)" autofocus placeholder="{{ __('Course name') }}" />
            <x-inputs.error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div class="mt-6">
            <x-inputs.label value="{{ __('Categories') }}" for="categories" />
            <div class="mt-1"></div>
            <x-inputs.select-input id="categories" class="select-search-modal mt-2 w-full" name="categories[]" required multiple>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ in_array($category->id, $course->categories->pluck('id')->toArray()) ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </x-inputs.select-input>
            <x-inputs.error class="mt-2" :messages="$errors->get('categories')" />
        </div>

        <div class="mt-6">
            <x-inputs.label value="{{ __('Description') }}" for="description" />
            <x-inputs.area id="description" class="mt-2 w-full" name="description" placeholder="{{ __('Class description') }}">
                {{ old('description', $course->description) }}
            </x-inputs.area>
            <x-inputs.error class="mt-2" :messages="$errors->get('description')" />
        </div>

        <div class="modal-action">
            <x-buttons.success class="ms-3">
                {{ __('Submit') }}
            </x-buttons.success>
        </div>
    </form>
</x-actions.modal>
