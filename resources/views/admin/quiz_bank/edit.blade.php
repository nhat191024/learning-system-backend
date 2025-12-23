<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Edit quiz bank') }}
        </h2>
        <a class="btn btn-soft btn-info" href="{{ route('admin.quizBank.index') }}">{{ __('Back') }}</a>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin.quizBank.update', $quizBank->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        <div class="mt-6">
                            <x-inputs.label value="{{ __('Title') }}" for="title" />
                            <x-inputs.text id="title" class="mt-2 w-full" name="title" type="text" :value="old('title', $quizBank->title)" autofocus placeholder="{{ __('Quiz bank title') }}" />
                            <x-inputs.error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div class="mt-6">
                            <x-inputs.label value="{{ __('Categories') }}" for="categories" />
                            <div class="mt-1"></div>
                            <x-inputs.select-input id="categories" class="select-search mt-2 w-full" name="categories[]" multiple>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ in_array($category->id, old('categories', $quizBank->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </x-inputs.select-input>
                            <x-inputs.error class="mt-2" :messages="$errors->get('categories')" />
                        </div>

                        <div class="mt-6">
                            <x-inputs.label value="{{ __('Description') }}" for="description" />
                            <x-inputs.area id="description" class="mt-2 w-full" name="description" placeholder="{{ __('Quiz bank description') }}">
                                {{ old('description', $quizBank->description) }}
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
