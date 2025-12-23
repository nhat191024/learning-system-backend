@props(['align' => 'right', 'width' => '52'])

@php
    $alignmentClasses = match ($align) {
        'left' => 'dropdown-start',
        'center' => 'dropdown-center',
        default => 'dropdown-end',
    };

    $width = match ($width) {
        '52' => 'w-52',
        default => $width,
    };
@endphp

<div {{ $attributes->merge(['class' => 'dropdown ' . $alignmentClasses]) }}>
    <div class="m-1 flex items-center" tabindex="0" role="button"> {{ $trigger }}</div>
    <ul class="dropdown-content menu bg-base-100 rounded-box z-1 {{ $width }} border p-2 shadow-sm dark:border-gray-700 dark:bg-gray-800" tabindex="0">
        {{ $content }}
    </ul>
</div>
