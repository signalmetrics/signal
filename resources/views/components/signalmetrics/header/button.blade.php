<button {{ $attributes->merge(['class' => 'flex items-center justify-center px-3 py-1.5 text-sm font-semibold rounded-md hover:bg-gray-100']) }}>
    {{ $slot }}
</button>