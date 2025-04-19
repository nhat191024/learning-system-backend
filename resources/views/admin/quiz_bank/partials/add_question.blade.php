<x-actions.modal class="border border-gray-300 dark:border-gray-700" :id="'addQuestion'">
    <form id="questionForm" action="{{ route('admin.quizBank.question.store', $quizBankId) }}" method="POST">
        @csrf

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Add new questions') }}
        </h2>

        @if ($errors->any())
            <div class="alert alert-error mt-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div id="questions-container">
            <div class="question-block mb-8">
                <div class="flex items-center justify-between">
                    <h3 class="text-md font-medium text-gray-800 dark:text-gray-200">{{ __('Question') }} 1</h3>
                    <button class="delete-question btn btn-soft btn-error hidden" type="button">{{ __('Remove Question') }}</button>
                </div>

                <div class="mt-4">
                    <x-inputs.label value="{{ __('question') }}" for="question1" />
                    <x-inputs.text id="question1" class="mt-2 w-full" name="questions[0][question]" type="text" :value="old('questions.0.question')" autofocus placeholder="{{ __('question') }}" />
                    <x-inputs.error class="mt-2" :messages="$errors->get('questions.0.question')" />
                </div>

                <div class="choices-container">
                    <div class="choice-row mt-6">
                        <div class="flex items-center gap-2">
                            <x-inputs.label value="{{ __('choice 1') }}" for="q0-choice1" />
                            <input class="correct-radio" name="questions[0][correct_answer]" type="radio" value="0" checked>
                            <label class="text-sm">{{ __('Correct Answer') }}</label>
                        </div>
                        <div class="flex">
                            <x-inputs.text id="q0-choice1" class="mt-2 w-full" name="questions[0][choices][]" type="text" :value="old('questions.0.choices.0')" placeholder="{{ __('choice') }}" />
                            <button class="delete-choice btn btn-soft btn-error ms-2 mt-2 hidden" type="button">{{ __('X') }}</button>
                        </div>
                        <x-inputs.error class="mt-2" :messages="$errors->get('questions.0.choices.0')" />
                    </div>
                </div>

                <div class="mt-4 flex items-center justify-between">
                    <button class="add-choice btn btn-soft btn-info" type="button">
                        {{ __('Add Choice') }}
                    </button>
                    <span class="text-sm text-gray-500">Min: 2 choices, Max: 4 choices</span>
                </div>
            </div>
        </div>

        <div class="modal-action mt-8">
            <x-buttons.primary id="add-question" class="btn-soft" type="button">
                {{ __('Add Another Question') }}
            </x-buttons.primary>
            <x-buttons.secondary class="btn-soft ms-1" type="button" onclick="addQuestion.close()">
                {{ __('Cancel') }}
            </x-buttons.secondary>
            <x-buttons.success class="btn-soft ms-1" type="submit">
                {{ __('Submit All Questions') }}
            </x-buttons.success>
        </div>
    </form>
</x-actions.modal>
