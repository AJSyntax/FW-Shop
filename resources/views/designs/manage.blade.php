<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Manage Designs</h2>
                    <a href="{{ route('admin.designs.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-plus mr-2"></i>Add New Design
                    </a>
                </div>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                {{-- Add section to display errors --}}
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <strong class="font-bold">Error!</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                {{-- Stock column removed as digital products have unlimited downloads --}}
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($designs as $design)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <img src="{{ $design->image_path }}" alt="{{ $design->title }}" class="w-16 h-16 object-cover rounded">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $design->title }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $design->category->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">${{ $design->price }}</td>
                                    {{-- Stock column removed as digital products have unlimited downloads --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.designs.edit', $design) }}"
                                               class="inline-flex items-center px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md">
                                                <i class="fas fa-edit mr-1"></i>
                                                Edit
                                            </a>
                                            <button type="button"
                                                    onclick="showDeleteConfirmation({{ $design->id }})"
                                                    class="inline-flex items-center px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-md">
                                                <i class="fas fa-trash mr-1"></i>
                                                Delete
                                            </button>
                                        </div>

                                        <!-- Confirmation Message (Initially Hidden) -->
                                        <div id="confirmDelete{{ $design->id }}" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
                                            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                                                <div class="mt-3 text-center">
                                                    <h3 class="text-lg leading-6 font-medium text-gray-900">Delete Design</h3>
                                                    <div class="mt-2 px-7 py-3">
                                                        <p class="text-sm text-gray-500">
                                                            Are you sure you want to delete this design? This action cannot be undone.
                                                        </p>
                                                    </div>
                                                    <div class="flex justify-center gap-3 mt-4">
                                                        <form action="{{ route('admin.designs.destroy', $design) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                    class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md">
                                                                Yes, Delete
                                                            </button>
                                                        </form>
                                                        <button onclick="hideDeleteConfirmation({{ $design->id }})"
                                                                class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-md">
                                                            Cancel
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $designs->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
function showDeleteConfirmation(id) {
    document.getElementById('confirmDelete' + id).classList.remove('hidden');
}

function hideDeleteConfirmation(id) {
    document.getElementById('confirmDelete' + id).classList.add('hidden');
}
</script>
