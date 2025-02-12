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
                    <h2 class="text-2xl font-semibold">Order Management</h2>
                    
                    <!-- Order Status Filter -->
                    <div class="flex space-x-2">
                        <button class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">All Orders</button>
                        <button class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600">Pending</button>
                        <button class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Processing</button>
                        <button class="px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600">Shipped</button>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="mb-6">
                    <input type="text" placeholder="Search orders..." class="w-full px-4 py-2 border rounded-lg">
                </div>

                <!-- Orders Table -->
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">Order ID</th>
                            <th class="py-2 px-4 border-b">Customer</th>
                            <th class="py-2 px-4 border-b">Items</th>
                            <th class="py-2 px-4 border-b">Total</th>
                            <th class="py-2 px-4 border-b">Status</th>
                            <th class="py-2 px-4 border-b">Date</th>
                            <th class="py-2 px-4 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-2 px-4 border-b">#12345</td>
                            <td class="py-2 px-4 border-b">John Doe</td>
                            <td class="py-2 px-4 border-b">2 items</td>
                            <td class="py-2 px-4 border-b">$45.00</td>
                            <td class="py-2 px-4 border-b">
                                <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                                    Pending
                                </span>
                            </td>
                            <td class="py-2 px-4 border-b">2024-01-20</td>
                            <td class="py-2 px-4 border-b">
                                <div class="flex space-x-2">
                                    <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600"
                                            onclick="viewOrder(1)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600"
                                            onclick="updateStatus(1)">
                                        <i class="fas fa-shipping-fast"></i>
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

    <!-- Status Update Modal -->
    <div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-medium mb-4">Update Order Status</h3>
            <form id="statusForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Status</label>
                    <select class="w-full px-3 py-2 border rounded">
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeStatusModal()" 
                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Cancel</button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Update</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for Modal Handling -->
    <script>
        function viewOrder(id) {
            window.location.href = `/orders/${id}`;
        }

        function updateStatus(id) {
            document.getElementById('statusModal').classList.remove('hidden');
        }

        function closeStatusModal() {
            document.getElementById('statusModal').classList.add('hidden');
        }
    </script>
</x-app-layout> 