<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col items-center justify-center min-h-screen px-4">
            <div class="w-full max-w-sm">
                <a href="/" class="flex items-center justify-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white">
                    {{ config('app.name', 'Laravel') }}
                </a>

                <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
