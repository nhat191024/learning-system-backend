<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Users management') }}
        </h2>
        <a class="btn btn-soft btn-info" href="{{ route('admin.users.create') }}">{{ __('Add new user') }}</a>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto max-w-full sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <x-datatables>
                        <x-slot name="header">
                            <th>{{ __('No.') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Gender') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Role') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Created At') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </x-slot>
                        @foreach ($users as $key => $user)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>
                                    <div class="mask mask-squircle mr-3 h-12 w-12">
                                        <img src="{{ asset($user->avatar) }}" alt="Avatar" />
                                    </div>
                                    {{ $user->name }}
                                </td>
                                <td>{{ $user->gender }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role->name }}</td>
                                <td>
                                    @if ($user->status == 1)
                                        <span class="badge badge-lg badge-success">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge badge-lg badge-error">{{ __('Deactivate') }}</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('H:i d/m/Y ') }}</td>
                                <td>
                                    <div class="join">
                                        <a class="btn btn-soft btn-warning join-item" href="{{ route('admin.users.edit', $user->id) }}">{{ __('Edit') }}</a>
                                        @if ($user->status == 1)
                                            <a class="btn btn-soft btn-error join-item" href="{{ route('admin.users.destroy', $user->id) }}">{{ __('Deactivate') }}</a>
                                        @else
                                            <a class="btn btn-soft btn-success join-item" href="{{ route('admin.users.destroy', $user->id) }}">{{ __('Active') }}</a>
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
