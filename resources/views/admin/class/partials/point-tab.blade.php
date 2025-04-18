<div id="tab4" class="mt-2 hidden overflow-hidden bg-white opacity-0 shadow-sm transition-all duration-300 ease-in-out sm:rounded-lg dark:bg-gray-800">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <x-datatables>
            <x-slot name="header">
                <th>{{ __('No.') }}</th>
                <th>{{ __('Assignment') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Point') }}</th>
                <th>{{ __('Total point') }}</th>
            </x-slot>
            @foreach ($assignmentName as $key => $name)
                @foreach ($assignmentPoint as $secondKey => $point)
                    <tr>
                        <td>1</td>
                        <td>{{ $name }}</td>
                        <td>{{ $point['name'] }}</td>
                        <td>{{ $point['points'][$key]['score'] }}</td>
                        <td>{{ $point['totalScore'][$key]['totalScore'] }}</td>
                    </tr>
                @endforeach
            @endforeach
        </x-datatables>
    </div>
</div>
