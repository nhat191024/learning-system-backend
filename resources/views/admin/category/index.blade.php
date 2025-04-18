<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Category management') }}
        </h2>
        <btn class="btn btn-soft btn-info" onclick="createCategory.showModal()">{{ __('Add new category') }}</btn>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto max-w-full sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @include('admin.category.partials.create')

                    <x-datatables>
                        <x-slot name="header">
                            <th>{{ __('No.') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Parent') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </x-slot>
                        @foreach ($categories as $key => $category)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $category->name }}</td>
                                <td>
                                    @if ($category->parent_id)
                                        <span class="badge badge-lg badge-info">{{ $category->parent->name }}</span>
                                    @else
                                        <span class="badge badge-lg badge-info">{{ __('No parent') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($category->status == 'active')
                                        <span class="badge badge-lg badge-success">{{ __('Active') }}</span>
                                    @else
                                        <span class="badge badge-lg badge-error">{{ __('Deactivate') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="join">
                                        <a class="btn btn-soft btn-warning join-item" href="{{ route('admin.category.edit', $category->id) }}">{{ __('Edit') }}</a>
                                        @if ($category->status == 'active')
                                            <a class="btn btn-soft btn-error join-item" href="{{ route('admin.category.destroy', $category->id) }}">{{ __('Deactivate') }}</a>
                                        @else
                                            <a class="btn btn-soft btn-success join-item" href="{{ route('admin.category.destroy', $category->id) }}">{{ __('Active') }}</a>
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
