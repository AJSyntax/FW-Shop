<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($designs as $design)
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <img src="{{ $design->image_path }}" alt="{{ $design->title }}" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-semibold">{{ $design->title }}</h3>
                            <p class="text-gray-600">{{ $design->category->name }}</p>
                            <p class="text-lg font-bold mt-2">${{ $design->price }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            {{ $designs->links() }}
        </div>
    </div>
</x-app-layout> 