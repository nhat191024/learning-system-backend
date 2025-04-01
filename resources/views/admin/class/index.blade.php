<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Class management') }}
        </h2>
        <a class="btn btn-soft btn-info" onclick="createClass.showModal()">{{ __('Add new class') }}</a>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto max-w-full sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @include('admin.class.partials.create')

                    @if ($errors->any())
                        <div class="alert alert-error alert-soft" role="alert">
                            <svg class="h-6 w-6 shrink-0 stroke-current" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <x-datatables>
                        <x-slot name="header">
                            <th>{{ __('No.') }}</th>
                            <th>{{ __('Code') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Description') }}</th>
                            <th>{{ __('Teacher') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Created At') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </x-slot>
                        @foreach ($classes as $key => $class)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $class->code }}</td>
                                <td>{{ $class->name }}</td>
                                <td>
                                    @forelse ($class->categories as $category)
                                        <span class="badge badge-lg badge-info">{{ $category->name }}</span>
                                    @empty
                                        <span class="badge badge-lg badge-error">{{ __('No category') }}</span>
                                    @endforelse
                                </td>
                                <td>{{ $class->description }}</td>
                                <td>{{ $class->teacher->name }}</td>
                                <td>
                                    @if ($class->status == 'published')
                                        <span class="badge badge-lg badge-success">{{ __('Working') }}</span>
                                    @else
                                        <span class="badge badge-lg badge-error">{{ __('Stopped') }}</span>
                                    @endif
                                </td>
                                <td>{{ $class->created_at->format('H:i d/m/Y ') }}</td>
                                <td>
                                    <div class="join">
                                        <a class="btn btn-soft btn-info join-item" href="{{ route('admin.class.detail', $class->id) }}">{{ __('Detail') }}</a>
                                        @if ($class->status == 'published')
                                            <a class="btn btn-soft btn-error join-item" href="{{ route('admin.class.destroy', $class->id) }}">{{ __('Lock') }}</a>
                                        @else
                                            <a class="btn btn-soft btn-success join-item" href="{{ route('admin.class.destroy', $class->id) }}">{{ __('Unlock') }}</a>
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
