<x-actions.modal class="border border-gray-300 dark:border-gray-700" :id="'addStudent'">
    <form action="{{ route('admin.student.store', $class->id) }}" method="POST">
        @csrf

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Add new student to class') }}
        </h2>

        <div class="mt-6">
            <x-inputs.label value="{{ __('Student') }}" for="student" />
            <div class="mt-1"></div>
            <x-inputs.select-input id="student" class="select-search-modal mt-2 w-full" name="students[]" multiple>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}">{{ $student->name }}</option>
                @endforeach
            </x-inputs.select-input>
            <x-inputs.error class="mt-2" :messages="$errors->get('student')" />
        </div>

        <div class="modal-action">
            <x-buttons.success class="ms-3">
                {{ __('Xác nhận') }}
            </x-buttons.success>
        </div>
    </form>
</x-actions.modal>
