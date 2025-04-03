<div id="tab2" class="mt-2 hidden overflow-hidden bg-white opacity-0 shadow-sm transition-all duration-300 ease-in-out sm:rounded-lg dark:bg-gray-800">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <x-datatables>
            <x-slot name="header">
                <th>{{ __('No.') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Gender') }}</th>
                <th>{{ __('Email') }}</th>
                <th>{{ __('Certificate') }}</th>
                <th>{{ __('Status') }}</th>
                <th>{{ __('Created At') }}</th>
                <th>{{ __('Actions') }}</th>
            </x-slot>
            @foreach ($class->students as $key => $student)
                <tr>
                    <td>{{ ++$key }}</td>
                    <td>
                        <div class="mask mask-squircle mr-3 h-12 w-12">
                            <img src="{{ asset($student->avatar) }}" alt="Avatar" />
                        </div>
                        {{ $student->name }}
                    </td>
                    <td>{{ $student->gender }}</td>
                    <td>{{ $student->email }}</td>
                    <td>
                        @if ($student->certificates->count() > 0)
                            <span class="badge badge-lg badge-success">{{ __('Certificated') }}</span>
                        @else
                            <span class="badge badge-lg badge-error">{{ __('No certificate') }}</span>
                        @endif
                    </td>
                    <td>
                        @if ($student->status == 1)
                            <span class="badge badge-lg badge-success">{{ __('Active') }}</span>
                        @else
                            <span class="badge badge-lg badge-error">{{ __('Deactivate') }}</span>
                        @endif
                    </td>
                    <td>{{ $student->created_at->format('H:i d/m/Y ') }}</td>
                    <td>
                        <div class="join">
                            <a class="btn btn-soft btn-success join-item" href="{{ route('admin.student.certificate', ['class_id' => $class->id, 'student_id' => $student->id]) }}">{{ __('Certification') }}</a>
                            <a class="btn btn-soft btn-error join-item" href="{{ route('admin.student.destroy', ['class_id' => $class->id, 'student_id' => $student->id]) }}">{{ __('Remove') }}</a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-datatables>
    </div>
</div>
