<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $design->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="md:flex">
                    {{-- Image Section --}}
                    <div class="md:w-1/2">
                        <img src="{{ $design->image_path ?? asset('placeholder.jpg') }}" alt="{{ $design->title }}" class="w-full h-auto object-cover md:rounded-l-lg">
                    </div>

                    {{-- Details Section --}}
                    <div class="md:w-1/2 p-6 md:p-8">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $design->title }}</h1>

                        <p class="text-gray-700 mb-4">
                            {{ $design->description }}
                        </p>

                        <div class="mb-4">
                            <span class="text-sm text-gray-500">Category:</span>
                            <span class="ml-2 text-sm font-medium text-gray-700">{{ $design->category->name ?? 'Uncategorized' }}</span>
                        </div>

                        <div class="mb-6">
                            <span class="text-3xl font-semibold text-gray-900">${{ number_format($design->price, 2) }}</span>
                        </div>

                        <div class="mb-6">
                            @if($design->stock > 0)
                                <span class="inline-block bg-green-200 text-green-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">In Stock ({{ $design->stock }} available)</span>
                            @else
                                <span class="inline-block bg-red-200 text-red-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded">Out of Stock</span>
                            @endif
                        </div>

                        {{-- Add to Cart Form --}}
                        @if($design->stock > 0)
                            <form action="{{ route('cart.add', $design->id) }}" method="POST">
                                @csrf
                                {{-- Optional: Add quantity selector here later --}}
                                <button type="submit" class="w-full bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150 ease-in-out">
                                    Add to Cart
                                </button>
                            </form>
                        @else
                             <button type="button" class="w-full bg-gray-400 text-white py-3 px-6 rounded-lg cursor-not-allowed" disabled>
                                Out of Stock
                            </button>
                        @endif

                        {{-- Back Button --}}
                         <div class="mt-6">
                            <a href="{{ url()->previous() }}" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-arrow-left mr-2"></i>Back
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
