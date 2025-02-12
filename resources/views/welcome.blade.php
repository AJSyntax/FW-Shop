<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>FandomWearShop</title>

        <!-- Scripts and Styles -->
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"/>
        
        <style>
            body {
                font-family: 'Roboto', sans-serif;
            }
        </style>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="bg-gray-100 flex items-center justify-center min-h-screen relative">
        <img 
            src="https://res.cloudinary.com/dmygblav6/image/upload/v1738581630/piclumen-1737719529537_syawkb.png" 
            alt="Abstract background image with geometric shapes and vibrant colors" 
            class="absolute inset-0 w-full h-full object-cover z-0"
        />
        
        <div class="bg-white bg-opacity-75 p-24 rounded-lg shadow-lg text-center relative z-10">
            <img 
                alt="Logo of Fandom Wear Shop with a stylish and modern design" 
                class="mx-auto mb-12" 
                height="200" 
                src="https://res.cloudinary.com/dmygblav6/image/upload/v1738584298/piclumen-1738582764073-removebg-preview_uomkiz.png" 
                width="200"
            />
            
            <h1 class="text-7xl font-bold mb-12">Welcome to Fandom Wear Shop</h1>
            
            <div class="space-x-12">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" 
                           class="bg-blue-500 text-white text-2xl px-8 py-4 rounded hover:bg-blue-600 transition duration-300">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="bg-blue-500 text-white text-2xl px-8 py-4 rounded hover:bg-blue-600 transition duration-300">
                            Login
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" 
                               class="bg-green-500 text-white text-2xl px-8 py-4 rounded hover:bg-green-600 transition duration-300">
                                Register
                            </a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </body>
</html>
