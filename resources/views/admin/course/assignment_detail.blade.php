<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Assignment detail') }}
        </h2>
        <a class="btn btn-soft btn-info" href="{{ route('admin.course.detail', $courseId) }}">{{ __('Back') }}</a>
    </x-slot>
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
</x-app-layout>
