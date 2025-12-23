<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Course management') }}
        </h2>
        <a class="btn btn-soft btn-info" onclick="createCourse.showModal()">{{ __('Add new course') }}</a>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto max-w-full sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @include('admin.course.partials.create')

                    <x-datatables>
                        <x-slot name="header">
                            <th>{{ __('No.') }}</th>
                            <th>{{ __('Code') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Created At') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </x-slot>
                        @foreach ($courses as $key => $course)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $course->code }}</td>
                                <td>{{ $course->name }}</td>
                                <td>
                                    @forelse ($course->categories as $category)
                                        <span class="badge badge-lg badge-info">{{ $category->name }}</span>
                                    @empty
                                        <span class="badge badge-lg badge-error">{{ __('No category') }}</span>
                                    @endforelse
                                </td>
                                <td>{{ $course->description }}</td>
                                <td>
                                    @if ($course->status == 'published')
                                        <span class="badge badge-lg badge-success">{{ $course->status }}</span>
                                    @else
                                        <span class="badge badge-lg badge-error">{{ $course->status }}</span>
                                    @endif
                                </td>
                                <td>{{ $course->created_at->format('H:i d/m/Y ') }}</td>
                                <td>
                                    <div class="join">
                                        <a class="btn btn-soft btn-info join-item" href="{{ route('admin.course.detail', $course->id) }}">{{ __('Detail') }}</a>
                                        @if ($course->status == 'published')
                                            <a class="btn btn-soft btn-error join-item" href="{{ route('admin.course.destroy', $course->id) }}">{{ __('Lock') }}</a>
                                        @else
                                            <a class="btn btn-soft btn-success join-item" href="{{ route('admin.course.destroy', $course->id) }}">{{ __('Unlock') }}</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </x-datatables>
                </div>
            </div>
        </div>
</x-app-layout>
