<table id="datatables" class="stripe hover">
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
