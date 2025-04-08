<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Browse Designs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            {{-- Filter/Sort/Search Form --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <form action="{{ route('designs.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    {{-- Search Input --}}
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text" name="search" id="search" value="{{ $request->input('search') }}" placeholder="Search title or description..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>

                    {{-- Category Filter --}}
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category" id="category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $request->input('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Sort By --}}
                    <div>
                        <label for="sort" class="block text-sm font-medium text-gray-700">Sort By</label>
                        <select name="sort" id="sort" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="newest" {{ $request->input('sort', 'newest') == 'newest' ? 'selected' : '' }}>Newest</option>
                            <option value="price_asc" {{ $request->input('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ $request->input('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>

                    {{-- Submit Button --}}
                    <div class="md:col-span-4 flex justify-end mt-4">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Apply Filters
                        </button>
                         <a href="{{ route('designs.index') }}" class="ml-4 inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Design Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($designs as $design)
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg flex flex-col group">
                        <a href="{{ route('designs.show', $design->id) }}">
                            <img src="{{ $design->image_path ?? asset('placeholder.jpg') }}" alt="{{ $design->title }}" class="w-full h-48 object-cover group-hover:opacity-75 transition duration-150 ease-in-out">
                        </a>
                        <div class="p-4 flex flex-col flex-grow">
                            <a href="{{ route('designs.show', $design->id) }}">
                                <h3 class="text-lg font-semibold hover:text-blue-600">{{ $design->title }}</h3>
                            </a>
                            <p class="text-sm text-gray-500 mb-2">{{ $design->category->name ?? 'Uncategorized' }}</p>
                            <p class="text-lg font-bold mt-auto mb-3">${{ number_format($design->price, 2) }}</p>

                            {{-- Add to Cart Form --}}
                            @if($design->stock > 0)
                                <form action="{{ route('cart.add', $design->id) }}" method="POST" class="mt-auto">
                                    @csrf
                                    <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 text-sm">
                                        Add to Cart
                                    </button>
                                </form>
                            @else
                                <button type="button" class="w-full mt-auto bg-gray-400 text-white py-2 rounded-lg text-sm cursor-not-allowed" disabled>
                                    Out of Stock
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-gray-600 col-span-full text-center py-10">No designs found matching your criteria.</p>
                @endforelse
            </div>

            {{-- Pagination Links --}}
            <div class="mt-6">
                {{ $designs->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
