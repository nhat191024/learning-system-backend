<div id="tab3" class="mt-2 hidden overflow-hidden bg-white opacity-0 shadow-sm transition-all duration-300 ease-in-out sm:rounded-lg dark:bg-gray-800">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <x-datatables>
            <x-slot name="header">
                <th>{{ __('No.') }}</th>
                <th>{{ __('Type') }}</th>
                <th>{{ __('Title') }}</th>
                <th>{{ __('Description') }}</th>
                <th>{{ __('Duration') }}</th>
                <th>{{ __('Start Date') }}</th>
                <th>{{ __('Due Date') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Created At') }}</th>
                <th>{{ __('Actions') }}</th>
            </x-slot>

            @foreach ($class->assignments as $key => $assignment)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>{{ $assignment->type }}</td>
                    <td>{{ $assignment->title }}</td>
                    <td>{{ $assignment->description }}</td>
                    <td>{{ $assignment->duration ? $assignment->duration . ' ' . __('Minute') : 'Không có' }} </td>
                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $assignment->start_date)->format('H:i d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $assignment->due_date)->format('H:i d/m/Y') }}</td>
                    <td>
                        @if ($assignment->status == 'published')
                            <span class="badge badge-lg badge-success">{{ $assignment->status }}</span>
                        @else
                            <span class="badge badge-lg badge-error">{{ $assignment->status }}</span>
                        @endif
                    </td>
                    <td>{{ $assignment->created_at->format('H:i d/m/Y ') }}</td>
                    <td>
                        <div class="join">
                            <a class="btn btn-soft btn-info join-item" href="{{ route('admin.classAssignment.detail', ['id' => $assignment->id, 'classId' => $class->id]) }}" @disabled($assignment->type == 'lab')>{{ __('Detail') }}</a>
                            <a class="btn btn-soft btn-primary join-item">{{ __('Result') }}</a>
                            <a class="btn btn-soft btn-error join-item">{{ __('Deactive') }}</a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-datatables>
    </div>
</div>
