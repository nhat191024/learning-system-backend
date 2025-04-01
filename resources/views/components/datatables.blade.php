<table id="datatables" class="display">
    <thead>
        <tr>
            {{ $header }}
        </tr>
    </thead>
    <tbody>
        {{ $slot }}
    </tbody>
    <tfoot>
        <tr>
            {{ $header }}
        </tr>
    </tfoot>
</table>
