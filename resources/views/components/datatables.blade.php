<table class="datatables stripe hover">
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
