<x-app-layout>
    <x-slot name="header">
        <h2 id="header-info" class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Class management - Info') }}
        </h2>
        <div class="join">
            <button class="btn btn-soft btn-accent join-item" onclick="addStudent.showModal()">{{ __('Add student') }}</button>
            <a class="btn btn-soft btn-info join-item" href="{{ route('admin.class.index') }}">{{ __('Back') }}</a>
        </div>
    </x-slot>
    <div class="py-12">

        @include('admin.class.partials.edit')
        @include('admin.class.partials.add-student')

        <div class="mx-auto max-w-full sm:px-6 lg:px-8">
            <div class="tabs" role="tablist">
                <a class="tab tab-active" data-tab="tab1" role="tab">{{ __('Info') }}</a>
                <a class="tab" data-tab="tab2" role="tab">{{ __('Student') }}</a>
                <a class="tab" data-tab="tab3" role="tab">{{ __('Assignment') }}</a>
                <a class="tab" data-tab="tab4" role="tab">{{ __('Point') }}</a>
            </div>

            @include('admin.class.partials.info-tab')
            @include('admin.class.partials.student-tab')
            @include('admin.class.partials.assignment-tab')
            @include('admin.class.partials.point-tab')

        </div>
    </div>

    <x-slot name="script">
        <script>
            $(document).ready(function() {
                let headerInfo = $('#header-info');
                let headerText = headerInfo.text().split(' - ')[0];

                $('.tab').on('click', function() {
                    $('.tab').removeClass('tab-active');
                    $(this).addClass('tab-active');

                    const targetTab = '#' + $(this).data('tab');
                    headerInfo.text(`${headerText} - ${$(this).text()}`);

                    $('[id^="tab"]').addClass('opacity-0');
                    setTimeout(() => {
                        $('[id^="tab"]').addClass('hidden');
                        $(targetTab).removeClass('hidden');
                        setTimeout(() => {
                            $(targetTab).removeClass('opacity-0');
                        }, 50);
                    }, 300);
                });
            });
        </script>
    </x-slot>
</x-app-layout>
