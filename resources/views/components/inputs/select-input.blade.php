@props(['disabled' => false])

<select {{ $attributes->merge(['class' => 'select dark:bg-gray-900 ']) }}>
    {{ $slot }}
</select>
