@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'file-input dark:bg-gray-900 dark:text-gray-300', 'type' => 'file']) }}>
