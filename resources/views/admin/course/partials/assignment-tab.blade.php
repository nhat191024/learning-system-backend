<div id="tab3" class="mt-2 hidden overflow-hidden bg-white opacity-0 shadow-sm transition-all duration-300 ease-in-out sm:rounded-lg dark:bg-gray-800">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <x-datatables>
            <x-slot name="header">
                <th>{{ __('No.') }}</th>
                <th>{{ __('Video Url') }}</th>
                <th>{{ __('Title') }}</th>
                <th>{{ __('Description') }}</th>
                <th>{{ __('Duration') }}</th>
                <th>{{ __('Created At') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Actions') }}</th>
            </x-slot>

            @foreach ($course->assignments as $key => $assignment)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $assignment->video_url }}</td>
                    <td>{{ $assignment->title }}</td>
                    <td>{{ $assignment->description }}</td>
                    <td>{{ $assignment->duration ? $assignment->duration . ' ' . __('Minute') : 'Không có' }} </td>
                    <td>{{ $assignment->created_at->format('H:i d/m/Y ') }}</td>
                    <td>
                        @if ($assignment->status == 'published')
                            <span class="badge badge-success">{{ __('published') }}</span>
                        @else
                            <span class="badge badge-warning">{{ __('closed') }}</span>
                        @endif
                    <td>
                        <div class="join">
                            <a class="btn btn-soft btn-info join-item" href="{{ route('admin.courseAssignment.detail', ['id' => $assignment->id, 'courseId' => $course->id]) }}">{{ __('Detail') }}</a>
                            @if ($assignment->status == 'published')
                                <a class="btn btn-soft btn-error join-item" href="{{ route('admin.courseAssignment.destroy', $assignment->id) }}">{{ __('Deactive') }}</a>
                            @else
                                <a class="btn btn-soft btn-success join-item" href="{{ route('admin.courseAssignment.destroy', $assignment->id) }}">{{ __('Active') }}</a>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-datatables>
    </div>
</div>
