@props(['id'])

<dialog id="{{ $id }}" class="modal myModal">
    <div {{ $attributes->merge(['class' => 'modal-box dark:bg-gray-900']) }}>
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        {{ $slot }}
    </div>
    <form class="modal-backdrop" method="dialog">
        <button>close</button>
    </form>
</dialog>
