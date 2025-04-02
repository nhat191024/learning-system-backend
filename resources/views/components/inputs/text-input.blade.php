@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'input dark:bg-gray-900 dark:text-gray-300']) }}>
