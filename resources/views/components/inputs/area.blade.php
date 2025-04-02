@props(['disabled' => false])

<textarea @disabled($disabled) {{ $attributes->merge(['class' => 'textarea dark:bg-gray-900 dark:text-gray-300']) }}>{{ $slot }}</textarea>
