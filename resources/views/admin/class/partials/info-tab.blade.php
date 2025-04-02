<div id="tab1" class="mt-2 overflow-hidden bg-white shadow-sm transition-all duration-300 ease-in-out sm:rounded-lg dark:bg-gray-800">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <ul class="list">
            <li class="p-4 pb-2 text-xs tracking-wide opacity-60">{{ __('Class Information') }}</li>
            <li class="list-row">
                <div>
                    <div>{{ __('Name') }}</div>
                    <div class="text-xs font-semibold uppercase opacity-60">{{ $class->name }}</div>
                </div>
            </li>
            <li class="list-row">
                <div>
                    <div>{{ __('Code') }}</div>
                    <div class="text-xs font-semibold uppercase opacity-60">{{ $class->code }}</div>
                </div>
            </li>
            <li class="list-row">
                <div>
                    <div>{{ __('Teacher') }}</div>
                    <div class="text-xs font-semibold uppercase opacity-60">{{ $class->teacher->name }}</div>
                </div>
            </li>
            <li class="list-row">
                <div>
                    <div>{{ __('Students') }}</div>
                    <div class="text-xs font-semibold uppercase opacity-60">{{ $class->students->count() }}</div>
                </div>
            </li>
            <li class="list-row">
                <div>
                    <div>{{ __('Assignments') }}</div>
                    <div class="text-xs font-semibold uppercase opacity-60">{{ $class->assignments->count() }}</div>
                </div>
            </li>
            <li class="list-row">
                <div>
                    <div>{{ __('Status') }}</div>
                    <div class="text-xs font-semibold uppercase opacity-60">
                        @if ($class->status == 'published')
                            <div class="status status-success animate-bounce"></div>
                        @else
                            <div class="status status-error animate-bounce"></div>
                        @endif
                        {{ $class->status }}
                    </div>
                </div>
            </li>
            <li class="list-row">
                <div>
                    <div>{{ __('Categories') }}</div>
                    <div class="text-xs font-semibold uppercase opacity-60">
                        @forelse ($class->categories as $category)
                            <span class="badge badge-sm badge-info">{{ $category->name }}</span>
                        @empty
                            <span class="badge badge-lg badge-error">{{ __('No category') }}</span>
                        @endforelse
                    </div>
                </div>
            </li>
            <li class="list-row">
                <div>
                    <div>{{ __('Actions') }}</div>
                    <div class="text-xs font-semibold uppercase opacity-60">
                        <div class="join">
                            <button class="btn btn-soft btn-warning join-item" onclick="editClass.showModal()">{{ __('Edit') }}</button>
                            @if ($class->status == 'published')
                                <a class="btn btn-soft btn-error join-item" href="{{ route('admin.class.destroy', $class->id) }}">{{ __('Lock') }}</a>
                            @else
                                <a class="btn btn-soft btn-success join-item" href="{{ route('admin.class.destroy', $class->id) }}">{{ __('Unlock') }}</a>
                            @endif
                            <a class="btn btn-soft btn-accent join-item">{{ __('Import Student') }}</a>
                            <a class="btn btn-soft btn-success join-item">{{ __('Excel Export') }}</a>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
