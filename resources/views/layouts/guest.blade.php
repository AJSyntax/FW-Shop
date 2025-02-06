<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Fandom Wear Shop') }}</title>

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Fonts and Icons -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>

        <!-- Custom Styles -->
        <style>
            body {
                font-family: 'Roboto', sans-serif;
            }
            .transition-all {
                transition: all 0.3s ease-in-out;
            }
            /* Only apply hover effect to form inputs and submit button */
            input[type="email"]:hover,
            input[type="password"]:hover,
            input[type="text"]:hover,
            button[type="submit"]:hover {
                transform: scale(1.05);
            }
            
            /* Remove hover effect from other elements */
            .hover-scale {
                transition: all 0.3s ease-in-out;
            }
        </style>

        <!-- Additional Styles -->
        @livewireStyles
    </head>
    <body class="bg-gray-100 min-h-screen">
        <div class="font-sans text-gray-900 antialiased">
            {{ $slot }}
        </div>

        @livewireScripts
    </body>
</html>
