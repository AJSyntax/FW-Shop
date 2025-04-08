<x-app-layout>
    {{-- Header slot can be removed or kept depending on desired look --}}
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot> --}}

    {{-- Main Content Area --}}
    <main class="container mx-auto px-6 py-8">
        {{-- Welcome Message --}}
        <h1 class="text-3xl font-semibold text-gray-800">
            Welcome, {{ Auth::user()->name }}
        </h1>
        <p class="mt-4 text-gray-600">
            Here are some of our latest t-shirt designs:
        </p>

        {{-- Product Grid --}}
        <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse ($designs as $design)
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    {{-- Use design image path, provide alt text --}}
                    <img alt="{{ $design->title }}" class="w-full h-48 object-cover" src="{{ $design->image_path ?? asset('placeholder.jpg') }}" /> {{-- Added placeholder fallback --}}
                    <div class="p-4">
                        <h2 class="text-lg font-semibold text-gray-800">
                            {{ $design->title }}
                        </h2>
                        <p class="text-gray-600 mt-2">
                            ${{ number_format($design->price, 2) }} {{-- Format price --}}
                        </p>
                        {{-- TODO: Implement Add to Cart functionality --}}
                        <button class="mt-4 w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600">
                            Add to Cart
                        </button>
                    </div>
                </div>
            @empty
                <p class="text-gray-600 col-span-full">No designs available at the moment.</p>
            @endforelse
        </div>
    </main>

    {{-- Optional: Add a footer similar to the example if not using the main layout's footer --}}
    {{-- <footer class="bg-white shadow mt-8">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <p class="text-gray-600">
                © {{ date('Y') }} Print on Demand Shop. All rights reserved.
            </p>
            <div class="flex space-x-4">
                <a class="text-gray-600 hover:text-gray-800" href="#"><i class="fab fa-facebook-f"></i></a>
                <a class="text-gray-600 hover:text-gray-800" href="#"><i class="fab fa-twitter"></i></a>
                <a class="text-gray-600 hover:text-gray-800" href="#"><i class="fab fa-instagram"></i></a>
                <a class="text-gray-600 hover:text-gray-800" href="#"><i class="fab fa-linkedin-in"></i></a>
            </div>
        </div>
    </footer> --}}

</x-app-layout>
