<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Analytics</title>

        <!-- Used to add dark mode right away, adding here prevents any flicker -->
        <script>
            if (typeof(Storage) !== "undefined") {
                if(localStorage.getItem('theme') && localStorage.getItem('theme') == 'dark'){
                    document.documentElement.classList.add('dark');
                }
            }
        </script>

        <script src="//unpkg.com/alpinejs" defer></script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased dark:bg-gray-950">
        <x-signalmetrics.header />
        <main class="px-5">
            {{ $slot }}
        </main>
    </body>
</html>
