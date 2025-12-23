@props(['active'])

@php
    $classes = $active ?? false ? 'mb-2 font-medium menu-active' : 'mb-2 font-medium';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
