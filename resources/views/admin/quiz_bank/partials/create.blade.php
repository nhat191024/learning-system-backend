<x-actions.modal class="border border-gray-300 dark:border-gray-700" :id="'createQuizBank'">
    <form action="{{ route('admin.quizBank.store') }}" method="POST">
        @csrf

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Add quiz bank') }}
        </h2>

        <div class="mt-6">
            <x-inputs.label value="{{ __('Title') }}" for="title" />
            <x-inputs.text id="title" class="mt-2 w-full" name="title" type="text" :value="old('title')" autofocus placeholder="{{ __('Quiz bank title') }}" />
            <x-inputs.error class="mt-2" :messages="$errors->get('title')" />
        </div>

        <div class="mt-6">
            <x-inputs.label value="{{ __('Categories') }}" for="categories" />
            <div class="mt-1"></div>
            <x-inputs.select-input id="categories" class="select-search-modal mt-2 w-full" name="categories[]" multiple>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </x-inputs.select-input>
            <x-inputs.error class="mt-2" :messages="$errors->get('categories')" />
        </div>

        <div class="mt-6">
            <x-inputs.label value="{{ __('Description') }}" for="description" />
            <x-inputs.area id="description" class="mt-2 w-full" name="description" placeholder="{{ __('Quiz bank description') }}">
                {{ old('description') }}
            </x-inputs.area>
            <x-inputs.error class="mt-2" :messages="$errors->get('description')" />
        </div>

        <div class="mt-6">
            <x-inputs.label value="{{ __('Type') }}" for="type" />
            <div class="mt-1"></div>
            <x-inputs.select-input id="type" class="mt-2 w-full" name="type">
                <option value="public" {{ old('status') == 'public' ? 'selected' : '' }}>{{ __('public') }}</option>
                <option value="private" {{ old('status') == 'private' ? 'selected' : '' }}>{{ __('private') }}</option>
            </x-inputs.select-input>
            <x-inputs.error class="mt-2" :messages="$errors->get('type')" />
        </div>

        <div class="modal-action">
            <x-buttons.success class="ms-3">
                {{ __('Xác nhận') }}
            </x-buttons.success>
        </div>
    </form>
</x-actions.modal>
