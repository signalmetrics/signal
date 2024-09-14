<button {{ $attributes->merge(['class' => 'flex items-center justify-center px-3 py-1.5 text-sm font-semibold rounded-md dark:hover:bg-gray-900 dark:text-gray-300 dark:hover:text-gray-100 hover:bg-gray-100']) }}>
    {{ $slot }}
</button>