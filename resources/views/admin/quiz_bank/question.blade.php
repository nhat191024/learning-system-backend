<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Quiz bank question') }}
        </h2>
        <div class="join">
            <a class="btn btn-soft btn-primary join-item" onclick="addQuestion.showModal()">{{ __('Add new question') }}</a>
            <a class="btn btn-soft btn-info join-item" href="{{ route('admin.quizBank.index') }}">{{ __('Back') }}</a>
        </div>
    </x-slot>

    @include('admin.quiz_bank.partials.add_question')

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <ul class="list">
                        <li class="p-4 pb-2 text-xl tracking-wide opacity-60">{{ __('Question list') }}</li>
                        @foreach ($questions as $key => $question)
                            <li class="list-row">
                                <div>{{ ++$key }}</div>
                                <div>
                                    <div class="text-xl">{{ $question['question'] }}</div>

                                    @foreach ($question['choices'] as $key => $choice)
                                        <div class="flex items-center gap-2">
                                            @if ($choice['isCorrect'])
                                                <div class="status status-success" aria-label="correct"></div>
                                            @else
                                                <div class="status status-error" aria-label="incorrect"></div>
                                            @endif

                                            <div class="text-base font-semibold uppercase opacity-60">{{ $choice['choice'] }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <x-slot name="script">
            <script>
                $(document).ready(function() {
                    // Counter for question and choice IDs
                    let questionCounter = 0;
                    let choiceCounters = [1]; // Array to track choice count for each question

                    // Add new question
                    $('#add-question').click(function() {
                        questionCounter++;
                        choiceCounters[questionCounter] = 1;

                        const newQuestion = `
                            <div class="question-block mb-8 mt-8 border-t pt-4">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-md font-medium text-gray-800 dark:text-gray-200">{{ __('Question') }} ${questionCounter + 1}</h3>
                                    <button type="button" class="delete-question btn btn-soft btn-error">{{ __('Remove Question') }}</button>
                                </div>

                                <div class="mt-4">
                                    <x-inputs.label value="{{ __('question') }}" for="question${questionCounter + 1}" />
                                    <x-inputs.text id="question${questionCounter + 1}" class="mt-2 w-full" name="questions[${questionCounter}][question]" type="text" placeholder="{{ __('question') }}" />
                                    <x-inputs.error class="mt-2" :messages="$errors->get('questions.${questionCounter}.question')" />
                                </div>

                                <div class="choices-container">
                                    <div class="choice-row mt-6">
                                        <div class="flex items-center gap-2">
                                            <x-inputs.label value="{{ __('choice 1') }}" for="q${questionCounter}-choice1" />
                                            <input class="correct-radio" name="questions[${questionCounter}][correct_answer]" type="radio" value="0" checked>
                                            <label class="text-sm">{{ __('Correct Answer') }}</label>
                                        </div>
                                        <div class="flex">
                                            <x-inputs.text id="q${questionCounter}-choice1" class="mt-2 w-full" name="questions[${questionCounter}][choices][]" type="text" placeholder="{{ __('choice') }}" />
                                            <button class="delete-choice btn btn-soft btn-error ms-2 mt-2 hidden" type="button">{{ __('X') }}</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <button type="button" class="add-choice btn btn-soft btn-info" data-question="${questionCounter}">
                                        {{ __('Add Choice') }}
                                    </button>
                                </div>
                            </div>
                        `;

                        $('#questions-container').append(newQuestion);

                        // Show delete question buttons when there are at least 2 questions
                        if (questionCounter >= 1) {
                            $('.delete-question').removeClass('hidden');
                        }
                    });

                    // Delete question when remove button is clicked (using event delegation)
                    $('#questions-container').on('click', '.delete-question', function() {
                        $(this).closest('.question-block').remove();

                        // Update question counter
                        questionCounter--;

                        // Reset choice counters array
                        choiceCounters = [];

                        // Renumber questions and update their input names
                        $('.question-block').each(function(qIndex) {
                            // Update question heading
                            $(this).find('h3').text(`{{ __('Question') }} ${qIndex + 1}`);

                            // Update question input name and ID
                            const questionInput = $(this).find('input[name^="questions"][name$="[question]"]');
                            questionInput.attr('name', `questions[${qIndex}][question]`);
                            questionInput.attr('id', `question${qIndex + 1}`);

                            // Update choice counters array
                            choiceCounters[qIndex] = $(this).find('.choice-row').length;

                            // Update all choice inputs for this question
                            $(this).find('.choice-row').each(function(cIndex) {
                                // Update choice label
                                $(this).find('label:first').text(`{{ __('choice') }} ${cIndex + 1}`);

                                // Update correct answer radio
                                const radioBtn = $(this).find('input[type="radio"]');
                                radioBtn.attr('name', `questions[${qIndex}][correct_answer]`);
                                radioBtn.val(cIndex);

                                // Update choice text input
                                const choiceInput = $(this).find('input[name$="[choices][]"]');
                                choiceInput.attr('name', `questions[${qIndex}][choices][]`);
                                choiceInput.attr('id', `q${qIndex}-choice${cIndex + 1}`);
                            });

                            // Update add choice button data attribute
                            $(this).find('.add-choice').attr('data-question', qIndex);
                        });

                        // Hide delete question buttons if only one remains
                        if (questionCounter < 1) {
                            $('.delete-question').addClass('hidden');
                        }
                    });

                    // Add choice to a specific question (using event delegation)
                    $('#questions-container').on('click', '.add-choice', function() {
                        const questionIndex = $(this).data('question') || 0;
                        const choicesContainer = $(this).closest('.question-block').find('.choices-container');

                        // Check if we've reached the maximum number of choices (4)
                        if (choiceCounters[questionIndex] >= 4) {
                            alert('{{ __('Maximum 4 choices allowed per question') }}');
                            return;
                        }

                        choiceCounters[questionIndex]++;
                        const choiceCount = choiceCounters[questionIndex];

                        const newChoice = `
                            <div class="choice-row mt-6">
                                <div class="flex items-center gap-2">
                                    <x-inputs.label value="{{ __('choice') }} ${choiceCount}" for="q${questionIndex}-choice${choiceCount}" />
                                    <input type="radio" name="questions[${questionIndex}][correct_answer]" value="${choiceCount-1}" class="correct-radio">
                                    <label class="text-sm">{{ __('Correct Answer') }}</label>
                                </div>
                                <div class="flex">
                                    <x-inputs.text id="q${questionIndex}-choice${choiceCount}" class="mt-2 w-full" name="questions[${questionIndex}][choices][]" type="text" placeholder="{{ __('choice') }}" />
                                    <button type="button" class="delete-choice btn btn-soft btn-error ms-2 mt-2">{{ __('X') }}</button>
                                </div>
                            </div>
                        `;

                        choicesContainer.append(newChoice);

                        // Update the add choice button state (disable if max reached)
                        if (choiceCount >= 4) {
                            $(this).prop('disabled', true).addClass('opacity-50');
                        }

                        // Show delete buttons when there are at least 2 choices for this question
                        if (choiceCount >= 2) {
                            choicesContainer.find('.delete-choice').removeClass('hidden');
                        }
                    });

                    // Delete choice when X button is clicked (using event delegation)
                    $('#questions-container').on('click', '.delete-choice', function() {
                        const questionBlock = $(this).closest('.question-block');
                        const questionIndex = questionBlock.find('.add-choice').data('question') || 0;
                        const addChoiceBtn = questionBlock.find('.add-choice');

                        // Check if we'll go below the minimum number of choices (2)
                        if (choiceCounters[questionIndex] <= 2) {
                            alert('{{ __('Minimum 2 choices required per question') }}');
                            return;
                        }

                        $(this).closest('.choice-row').remove();

                        // Update choice counter for this question
                        choiceCounters[questionIndex]--;

                        // Renumber the choices for this question
                        questionBlock.find('.choice-row').each(function(index) {
                            const choiceNum = index + 1;
                            $(this).find('label:first').text(`{{ __('choice') }} ${choiceNum}`);
                            $(this).find('input[type="radio"]').val(index);
                            $(this).find('input[type="text"]').attr('id', `q${questionIndex}-choice${choiceNum}`);
                        });

                        // Re-enable the add choice button if it was disabled
                        if (choiceCounters[questionIndex] < 4) {
                            addChoiceBtn.prop('disabled', false).removeClass('opacity-50');
                        }

                        // Hide delete buttons if only two choices remain (minimum required)
                        if (choiceCounters[questionIndex] <= 2) {
                            questionBlock.find('.delete-choice').addClass('hidden');
                        }
                    });

                    // Form submission validation
                    $('#questionForm').submit(function(e) {
                        let isValid = true;
                        let errorMessage = '';

                        // Validate each question has content
                        $('.question-block').each(function(qIndex) {
                            const questionInput = $(this).find(`input[name="questions[${qIndex}][question]"]`);
                            if (!questionInput.val().trim()) {
                                errorMessage += `Question ${qIndex + 1} is empty\n`;
                                isValid = false;
                            }

                            // Validate each question has at least 2 choices
                            const choiceCount = $(this).find('.choice-row').length;
                            if (choiceCount < 2) {
                                errorMessage += `Question ${qIndex + 1} needs at least 2 choices\n`;
                                isValid = false;
                            }

                            // Validate each choice has content
                            $(this).find(`input[name="questions[${qIndex}][choices][]"]`).each(function(cIndex) {
                                if (!$(this).val().trim()) {
                                    errorMessage += `Choice ${cIndex + 1} for Question ${qIndex + 1} is empty\n`;
                                    isValid = false;
                                }
                            });
                        });

                        if (!isValid) {
                            e.preventDefault();
                            alert('Please fix the following errors:\n' + errorMessage);
                            return false;
                        }

                        return true;
                    });
                });
            </script>
        </x-slot>
</x-app-layout>
