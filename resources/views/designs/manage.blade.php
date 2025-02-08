<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Manage Designs</h2>
                    <a href="{{ route('designs.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        <i class="fas fa-plus mr-2"></i>Add New Design
                    </a>
                </div>

                <!-- Search and Filter Section -->
                <div class="mb-6">
                    <input type="text" placeholder="Search designs..." class="w-full px-4 py-2 border rounded-lg">
                </div>

                <!-- Designs Table -->
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">Design</th>
                            <th class="py-2 px-4 border-b">Title</th>
                            <th class="py-2 px-4 border-b">Description</th>
                            <th class="py-2 px-4 border-b">Price</th>
                            <th class="py-2 px-4 border-b">Status</th>
                            <th class="py-2 px-4 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-2 px-4 border-b">
                                <img src="https://storage.googleapis.com/a1aa/image/sdtsHM8eT3CziaY6oRtp_4Sjb7XdREl1JGtqxy5tgDw.jpg" 
                                     alt="T-shirt design" 
                                     class="w-16 h-16 object-cover rounded"
                                />
                            </td>
                            <td class="py-2 px-4 border-b">Cool T-Shirt</td>
                            <td class="py-2 px-4 border-b">A very cool t-shirt design</td>
                            <td class="py-2 px-4 border-b">$20.00</td>
                            <td class="py-2 px-4 border-b">
                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">Active</span>
                            </td>
                            <td class="py-2 px-4 border-b">
                                <div class="flex space-x-2">
                                    <button class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600" 
                                            onclick="openEditModal(1)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"
                                            onclick="confirmDelete(1)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"
                                            onclick="viewDetails(1)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="mt-4">
                    <!-- Add pagination controls here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-medium mb-4">Edit Design</h3>
            <form id="editForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Title</label>
                    <input type="text" class="w-full px-3 py-2 border rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Price</label>
                    <input type="number" step="0.01" class="w-full px-3 py-2 border rounded">
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeEditModal()" 
                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancel</button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for Modal Handling -->
    <script>
        function openEditModal(id) {
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this design?')) {
                // Handle delete action
            }
        }

        function viewDetails(id) {
            // Handle view action
            window.location.href = `/designs/${id}`;
        }
    </script>
</x-app-layout> 