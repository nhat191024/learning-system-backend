<x-app-layout>
    <x-slot name="header">
        <h2 id="header-info" class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Course management - Info') }}
        </h2>
        <div class="join">
            <button class="btn btn-soft btn-primary join-item" onclick="createAssignment.showModal()">{{ __('Add assignment') }}</button>
            <a class="btn btn-soft btn-info join-item" href="{{ route('admin.course.index') }}">{{ __('Back') }}</a>
        </div>
    </x-slot>
    <div class="py-12">

        @include('admin.course.partials.edit')
        @include('admin.course.partials.create-assignment')

        <div class="mx-auto max-w-full sm:px-6 lg:px-8">
            <div class="tabs" role="tablist">
                <a class="tab tab-active" data-tab="tab1" role="tab">{{ __('Info') }}</a>
                <a class="tab" data-tab="tab2" role="tab">{{ __('Student') }}</a>
                <a class="tab" data-tab="tab3" role="tab">{{ __('Assignment') }}</a>
                <a class="tab" data-tab="tab4" role="tab">{{ __('Point') }}</a>
            </div>

            @include('admin.course.partials.info-tab')
            @include('admin.course.partials.student-tab')
            @include('admin.course.partials.assignment-tab')
            @include('admin.course.partials.point-tab')

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
                });

                // Update function for validateForm
                window.validateForm = function() {
                    let isValid = true;
                    const form = document.getElementById('assignmentForm');
                    const title = document.getElementById('title').value;
                    const videoUrl = document.getElementById('video_url').value;
                    const duration = document.getElementById('duration').value;
                    const quizPackageId = document.getElementById('selected_quiz_package').value;
                    const questionCount = document.getElementById('question_count').value;

                    // Clear previous error messages
                    $('.validation-error').remove();
                    $('.border-error').removeClass('border-error');

                    // Validate title
                    if (!title.trim()) {
                        isValid = false;
                        showError('title', 'Title is required');
                    }

                    // Validate video URL
                    if (!videoUrl.trim()) {
                        isValid = false;
                        showError('video_url', 'Video URL is required');
                    } else if (!isValidUrl(videoUrl)) {
                        isValid = false;
                        showError('video_url', 'Please enter a valid URL');
                    }

                    // Validate duration
                    if (!duration) {
                        isValid = false;
                        showError('duration', 'Duration is required');
                    } else if (parseInt(duration) < 1) {
                        isValid = false;
                        showError('duration', 'Duration must be at least 1 minute');
                    }

                    // Validate quiz package
                    if (!quizPackageId) {
                        isValid = false;
                        showError('quizPackageSection', 'Please select a quiz package');
                    }

                    // Validate question count
                    if (!questionCount) {
                        isValid = false;
                        showError('question_count', 'Number of questions is required');
                    } else if (parseInt(questionCount) < 1) {
                        isValid = false;
                        showError('question_count', 'Number of questions must be at least 1');
                    }

                    if (isValid) {
                        form.submit();
                    }
                };

                // Helper function to validate URL
                function isValidUrl(url) {
                    try {
                        new URL(url);
                        return true;
                    } catch (e) {
                        return false;
                    }
                }
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
