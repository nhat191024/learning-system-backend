<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Quiz bank management') }}
        </h2>
        <a class="btn btn-soft btn-info">{{ __('Add new quiz bank') }}</a>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto max-w-full sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <x-datatables>
                        <x-slot name="header">
                            <th>{{ __('No.') }}</th>
                            <th>{{ __('Creator') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Quiz id range') }}</th>
                            <th>{{ __('Type') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </x-slot>
                        @foreach ($quizBank as $key => $bank)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $bank->creator->name }}</td>
                                <td>{{ $bank->title }}</td>
                                <td>{{ $bank->description }}</td>
                                <td>
                                    @forelse ($bank->categories as $category)
                                        <span class="badge badge-lg badge-info">{{ $category->name }}</span>
                                    @empty
                                        <span class="badge badge-lg badge-error">{{ __('No category') }}</span>
                                    @endforelse
                                </td>
                                <td>{{ $bank->quiz_id_range }}</td>
                                <td>{{ $bank->type }}</td>
                                <td>
                                    <div class="join">
                                        <a class="btn btn-soft btn-info join-item" href="{{ route('admin.quizBank.questions', $bank->id) }}">
                                            {{ __('Questions') }}
                                        </a>
                                        <a class="btn btn-soft btn-warning join-item">
                                            {{ __('Edit') }}
                                        </a>
                                        <a class="btn btn-soft btn-error join-item">
                                            {{ __('Deactivate') }}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </x-datatables>
                </div>
            </div>
        </div>
</x-app-layout>
