<x-app-layout>
    <x-slot name="header">
        <h2 id="header-info" class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Class management - Info') }}
        </h2>
        <div class="join">
            <button class="btn btn-soft btn-accent join-item" onclick="addStudent.showModal()">{{ __('Add student') }}</button>
            <button class="btn btn-soft btn-primary join-item" onclick="createAssignment.showModal()">{{ __('Add assignment') }}</button>
            <a class="btn btn-soft btn-info join-item" href="{{ route('admin.class.index') }}">{{ __('Back') }}</a>
        </div>
    </x-slot>
    <div class="py-12">

        @include('admin.class.partials.edit')
        @include('admin.class.partials.add-student')
        @include('admin.class.partials.create-assignment')

        <div class="mx-auto max-w-full sm:px-6 lg:px-8">
            <div class="tabs" role="tablist">
                <a class="tab tab-active" data-tab="tab1" role="tab">{{ __('Info') }}</a>
                <a class="tab" data-tab="tab2" role="tab">{{ __('Student') }}</a>
                <a class="tab" data-tab="tab3" role="tab">{{ __('Assignment') }}</a>
                <a class="tab" data-tab="tab4" role="tab">{{ __('Point') }}</a>
            </div>

            @include('admin.class.partials.info-tab')
            @include('admin.class.partials.student-tab')
            @include('admin.class.partials.assignment-tab')
            @include('admin.class.partials.point-tab')

        </div>
    </div>

    <x-slot name="script">
        <script>
            $(document).ready(function() {
                let headerInfo = $('#header-info');
                let headerText = headerInfo.text().split(' - ')[0];

                $('.tab').on('click', function() {
                    $('.tab').removeClass('tab-active');
                    $(this).addClass('tab-active');

                    const targetTab = '#' + $(this).data('tab');
                    headerInfo.text(`${headerText} - ${$(this).text()}`);

                    $('[id^="tab"]').addClass('opacity-0');
                    setTimeout(() => {
                        $('[id^="tab"]').addClass('hidden');
                        $(targetTab).removeClass('hidden');
                        setTimeout(() => {
                            $(targetTab).removeClass('opacity-0');
                        }, 50);
                    }, 300);
                });

                // Quiz package selection handler
                $(document).on('click', '.package-btn', function() {
                    // Remove active class from all buttons
                    $('.package-btn').removeClass('ring-2 ring-primary');

                    // Add active class to clicked button
                    $(this).addClass('ring-2 ring-primary');

                    // Set the selected package id to hidden input
                    $('#selected_quiz_package').val($(this).data('package-id'));

                    // Show the question count section when a package is selected
                    $('#questionCountSection').removeClass('hidden');
                });

                // Show/hide quiz package section based on assignment type
                $('#type').on('change', function() {
                    if ($(this).val() === 'quiz') {
                        $('#quizPackageSection').removeClass('hidden');
                    } else {
                        $('#quizPackageSection').addClass('hidden');
                        $('#questionCountSection').addClass('hidden');
                        // Clear the selected package when switching away from quiz type
                        $('#selected_quiz_package').val('');
                        $('.package-btn').removeClass('ring-2 ring-primary');
                    }
                });

                // Trigger the change event on page load to set the initial state
                $('#type').trigger('change');

                // Handle date and time combination
                function updateDateTimeValues() {
                    // For start date/time
                    const startDate = $('#start_date').val();
                    const startTime = $('#start_time').val();

                    if (startDate) {
                        const startDateTimeValue = startTime ?
                            `${startDate}T${startTime}` :
                            `${startDate}T00:00`;
                        $('#start_datetime').val(startDateTimeValue);
                    } else {
                        $('#start_datetime').val('');
                    }

                    // For due date/time
                    const dueDate = $('#due_date').val();
                    const dueTime = $('#due_time').val();

                    if (dueDate) {
                        const dueDateTimeValue = dueTime ?
                            `${dueDate}T${dueTime}` :
                            `${dueDate}T23:59`;
                        $('#due_datetime').val(dueDateTimeValue);
                    } else {
                        $('#due_datetime').val('');
                    }
                }

                // Update datetime values when date or time changes
                $('#start_date, #start_time, #due_date, #due_time').on('change', updateDateTimeValues);

                // Update function for validateForm
                window.validateForm = function() {
                    // Update datetime values before validation
                    updateDateTimeValues();

                    let isValid = true;
                    const form = document.getElementById('assignmentForm');
                    const type = document.getElementById('type').value;
                    const title = document.getElementById('title').value;
                    const duration = document.getElementById('duration').value;
                    const startDateTime = document.getElementById('start_datetime').value;
                    const dueDateTime = document.getElementById('due_datetime').value;

                    // Clear previous error messages
                    $('.validation-error').remove();

                    // Validate title
                    if (!title.trim()) {
                        isValid = false;
                        showError('title', 'Title is required');
                    }

                    // Validate duration
                    if (!duration) {
                        isValid = false;
                        showError('duration', 'Duration is required');
                    } else if (parseInt(duration) < 1) {
                        isValid = false;
                        showError('duration', 'Duration must be at least 1 minute');
                    }

                    // Validate dates if both are provided
                    if (startDateTime && dueDateTime && new Date(dueDateTime) < new Date(startDateTime)) {
                        isValid = false;
                        showError('due_date', 'Due date must be after start date');
                    }

                    // Validate quiz-specific fields
                    if (type === 'quiz') {
                        const quizPackageId = document.getElementById('selected_quiz_package').value;
                        const questionCount = document.getElementById('question_count').value;

                        if (!quizPackageId) {
                            isValid = false;
                            showError('quizPackageSection', 'Please select a quiz package');
                        }

                        if (!questionCount) {
                            isValid = false;
                            showError('question_count', 'Number of questions is required');
                        } else if (parseInt(questionCount) < 1) {
                            isValid = false;
                            showError('question_count', 'Number of questions must be at least 1');
                        }
                    }

                    if (isValid) {
                        form.submit();
                    }
                };
            });

            function showError(fieldId, message) {
                const field = document.getElementById(fieldId);
                const errorDiv = document.createElement('div');
                errorDiv.className = 'validation-error text-sm text-error mt-1';
                errorDiv.innerHTML = message;

                // Insert after the field
                field.parentNode.insertBefore(errorDiv, field.nextSibling);

                // Highlight the field
                field.classList.add('border-error');
            }
        </script>
    </x-slot>
</x-app-layout>
