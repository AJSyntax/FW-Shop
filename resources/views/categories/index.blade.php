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
                    <h2 class="text-2xl font-semibold">Categories</h2>
                    <button onclick="openAddModal()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        <i class="fas fa-plus mr-2"></i>Add Category
                    </button>
                </div>

                <!-- Categories Table -->
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">Name</th>
                            <th class="py-2 px-4 border-b">Description</th>
                            <th class="py-2 px-4 border-b">Designs Count</th>
                            <th class="py-2 px-4 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-2 px-4 border-b">Movies</td>
                            <td class="py-2 px-4 border-b">Movie-themed t-shirt designs</td>
                            <td class="py-2 px-4 border-b">12</td>
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
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add/Edit Category Modal -->
    <div id="categoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-medium mb-4" id="modalTitle">Add Category</h3>
            <form id="categoryForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Name</label>
                    <input type="text" name="name" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Description</label>
                    <textarea name="description" class="w-full px-3 py-2 border rounded" rows="3"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeModal()" 
                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancel</button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for Modal Handling -->
    <script>
        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Add Category';
            document.getElementById('categoryModal').classList.remove('hidden');
        }

        function openEditModal(id) {
            document.getElementById('modalTitle').textContent = 'Edit Category';
            document.getElementById('categoryModal').classList.remove('hidden');
            // Populate form with category data
        }

        function closeModal() {
            document.getElementById('categoryModal').classList.add('hidden');
        }

        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this category?')) {
                // Handle delete action
            }
        }
    </script>
</x-app-layout> 