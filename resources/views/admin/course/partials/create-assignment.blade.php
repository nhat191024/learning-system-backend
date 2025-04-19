<x-actions.modal class="border border-gray-300 dark:border-gray-700" :id="'createAssignment'">
    <form id="assignmentForm" action="{{ route('admin.courseAssignment.store', $course->id) }}" method="POST">
        @csrf

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Add new assignment') }}
        </h2>

        <!-- Assignment Basic Information -->
        <div class="mt-4 space-y-4">
            <div class="mt-4">
                <x-inputs.label value="{{ __('video url') }}" for="video_url" />
                <x-inputs.text id="video_url" class="mt-2 w-full" name="video_url" type="text" :value="old('video_url')" autofocus placeholder="{{ __('Video url') }}" />
                <x-inputs.error class="mt-2" :messages="$errors->get('video_url')" />
            </div>

            <div class="mt-4">
                <x-inputs.label value="{{ __('Title') }}" for="title" />
                <x-inputs.text id="title" class="mt-2 w-full" name="title" type="text" :value="old('title')" autofocus placeholder="{{ __('Title') }}" />
                <x-inputs.error class="mt-2" :messages="$errors->get('title')" />
            </div>

            <div class="mt-6">
                <x-inputs.label value="{{ __('Description') }}" for="description" />
                <x-inputs.area id="description" class="mt-2 w-full" name="description" placeholder="{{ __('Class description') }}">
                    {{ old('description') }}
                </x-inputs.area>
                <x-inputs.error class="mt-2" :messages="$errors->get('description')" />
            </div>

            <div class="mt-4">
                <x-inputs.label value="{{ __('Duration (minutes)') }}" for="duration" />
                <x-inputs.text id="duration" class="mt-2 w-full" name="duration" type="number" :value="old('duration')" autofocus placeholder="{{ __('duration') }}" />
                <x-inputs.error class="mt-2" :messages="$errors->get('duration')" />
            </div>

            <!-- Question Count - Always visible -->
            <div id="questionCountSection">
                <x-inputs.label value="{{ __('Number of Questions') }}" for="question_count" />
                <x-inputs.text id="question_count" class="mt-2 w-full" name="question_count" type="number" min="1" :value="old('question_count')" placeholder="{{ __('Number of questions to include') }}" />
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Random questions will be selected from the package') }}</p>
                <x-inputs.error class="mt-2" :messages="$errors->get('question_count')" />
            </div>
        </div>

        <!-- Quiz Packages Section - Always visible -->
        <div id="quizPackageSection" class="mt-4">
            <h3 class="text-md mb-2 font-medium text-gray-700 dark:text-gray-300">{{ __('Select Quiz Package') }}</h3>
            <input id="selected_quiz_package" name="quiz_package_id" type="hidden">

            <div class="mt-2 grid grid-cols-1 gap-3 md:grid-cols-2">
                @forelse($quizPackages ?? [] as $package)
                    <button class="package-btn rounded-lg border p-3 text-left transition duration-150 ease-in-out hover:bg-gray-100 dark:hover:bg-gray-700" data-package-id="{{ $package->id }}" type="button">
                        <div class="font-semibold text-gray-800 dark:text-gray-200">{{ $package->title }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            <span class="badge badge-sm badge-info">{{ $package->quizzes->count() }} quizzes</span>
                            <span class="badge badge-sm badge-accent">{{ $package->type }}</span>
                            <span class="badge badge-sm {{ $package->status ? 'badge-success' : 'badge-warning' }}">
                                {{ $package->status ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="mt-1 truncate text-xs text-gray-500 dark:text-gray-500">{{ $package->description }}</div>
                    </button>
                @empty
                    <div class="col-span-2 py-4 text-center text-gray-500 dark:text-gray-400">
                        {{ __('No quiz packages available') }}
                    </div>
                @endforelse
            </div>
        </div>

        <div class="modal-action">
            <button class="btn btn-soft btn-success ms-3" type="button" onclick="validateForm()">
                {{ __('Submit') }}
            </button>
        </div>
    </form>
</x-actions.modal>
