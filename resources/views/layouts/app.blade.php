<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'FandomWearShop') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="https://res.cloudinary.com/dmygblav6/image/upload/v1738584298/piclumen-1738582764073-removebg-preview_uomkiz.png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{-- Global Flash Message Display --}}
                @php
                    $message = '';
                    $type = '';
                    if (session('success')) {
                        $message = session('success');
                        $type = 'success';
                    } elseif (session('error')) {
                        $message = session('error');
                        $type = 'error';
                    } elseif (session('info')) {
                        $message = session('info');
                        $type = 'info';
                    }

                    $bgColor = match($type) {
                        'success' => 'bg-green-500',
                        'error' => 'bg-red-500',
                        'info' => 'bg-blue-500',
                        default => '',
                    };
                @endphp

                @if ($message && $bgColor)
                    <div
                        x-data="{ show: true }"
                        x-init="setTimeout(() => show = false, 4000)"
                        x-show="show"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-y-0"
                        x-transition:leave-end="opacity-0 transform translate-y-2"
                        class="fixed top-5 right-5 z-50 {{ $bgColor }} text-white text-sm font-bold px-4 py-3 rounded-lg shadow-md"
                        role="alert"
                    >
                        <p>{{ $message }}</p>
                    </div>
                @endif
                {{-- End Global Flash Message Display --}}

                {{ $slot }}
            </main>
        </div>

        @stack('modals')

        @livewireScripts

        @stack('scripts') {{-- Add stack for page-specific scripts --}}
    </body>
</html>
