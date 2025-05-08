<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(Auth::user()->isAdmin() && isset($pendingOrdersCount) && $pendingOrdersCount > 0)
                <div class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                    <div class="flex items-center">
                        <div class="py-1"><i class="fas fa-exclamation-circle mr-2"></i></div>
                        <div>
                            <p class="font-bold">Orders Awaiting Confirmation</p>
                            <p class="text-sm">You have {{ $pendingOrdersCount }} {{ Str::plural('order', $pendingOrdersCount) }} awaiting your confirmation. <a href="{{ route('orders.index', ['status' => 'pending']) }}" class="underline font-semibold">View pending orders</a></p>
                            <p class="text-sm mt-1"><i class="fas fa-info-circle mr-1"></i> Customers cannot download their designs until you confirm their orders.</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Order Management</h2>

                    <!-- Order Status Filter -->
                    <div class="flex space-x-2">
                        <a href="{{ route('orders.index') }}" class="px-4 py-2 {{ request('status') ? 'bg-gray-200 hover:bg-gray-300' : 'bg-gray-700 text-white hover:bg-gray-800' }} rounded-lg">All Orders</a>
                        <a href="{{ route('orders.index', ['status' => 'pending']) }}" class="px-4 py-2 {{ request('status') === 'pending' ? 'bg-yellow-700 text-white hover:bg-yellow-800' : 'bg-yellow-500 text-white hover:bg-yellow-600' }} rounded-lg">Pending</a>
                        <a href="{{ route('orders.index', ['status' => 'processing']) }}" class="px-4 py-2 {{ request('status') === 'processing' ? 'bg-blue-700 text-white hover:bg-blue-800' : 'bg-blue-500 text-white hover:bg-blue-600' }} rounded-lg">Processing</a>
                        <a href="{{ route('orders.index', ['status' => 'shipped']) }}" class="px-4 py-2 {{ request('status') === 'shipped' ? 'bg-green-700 text-white hover:bg-green-800' : 'bg-green-500 text-white hover:bg-green-600' }} rounded-lg">Shipped</a>
                        <a href="{{ route('orders.index', ['status' => 'delivered']) }}" class="px-4 py-2 {{ request('status') === 'delivered' ? 'bg-blue-700 text-white hover:bg-blue-800' : 'bg-blue-500 text-white hover:bg-blue-600' }} rounded-lg">Delivered</a>
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
                        @forelse($orders as $order)
                            <tr class="{{ $order->status === 'pending' ? 'bg-yellow-50' : '' }}" data-order-id="{{ $order->id }}" data-status="{{ $order->status }}">
                                <td class="py-2 px-4 border-b">{{ $order->order_number }}</td>
                                <td class="py-2 px-4 border-b">{{ $order->user->name }}</td>
                                <td class="py-2 px-4 border-b">{{ $order->items->count() }} {{ Str::plural('item', $order->items->count()) }}</td>
                                <td class="py-2 px-4 border-b">${{ number_format($order->total_amount, 2) }}</td>
                                <td class="py-2 px-4 border-b">
                                    <span class="px-2 py-1 rounded-full text-sm
                                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                        @elseif($order->status === 'delivered') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="py-2 px-4 border-b">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="py-2 px-4 border-b">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white rounded-md">
                                            <i class="fas fa-eye mr-1"></i>
                                            View
                                        </a>
                                        <button class="inline-flex items-center px-3 py-1 {{ $order->status === 'pending' ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' }} text-white rounded-md"
                                                onclick="updateStatus({{ $order->id }})" title="{{ $order->status === 'pending' ? 'Confirm Order' : 'Update Status' }}">
                                            @if($order->status === 'pending')
                                                <i class="fas fa-check mr-1"></i> Confirm
                                            @else
                                                <i class="fas fa-shipping-fast mr-1"></i>
                                                Update
                                            @endif
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-4 px-4 text-center text-gray-500">No orders found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div id="statusModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 id="statusModalTitle" class="text-lg font-medium mb-4">Update Order Status</h3>
            <form id="statusForm" method="POST">
                @csrf
                @method('PUT')
                <!-- Order ID is already in the URL, no need for a hidden field -->
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Status</label>
                    <select name="status" class="w-full px-3 py-2 border rounded">
                        <option value="pending">Pending - Awaiting Confirmation</option>
                        <option value="processing">Processing - Confirmed (Enables Downloads)</option>
                        <option value="shipped">Shipped - Design Delivered</option>
                        <option value="delivered">Delivered - Design Downloaded</option>
                        <option value="cancelled">Cancelled - Order Rejected</option>
                    </select>
                </div>
                <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded text-sm text-blue-700">
                    <p><i class="fas fa-info-circle mr-1"></i> <strong>Note:</strong> Changing status to "Processing" or beyond will allow the customer to download their design files. Status will automatically change to "Delivered" when designs are downloaded.</p>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeStatusModal()"
                            class="inline-flex items-center px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">
                        <i class="fas fa-times mr-1"></i>
                        Cancel
                    </button>
                    <button type="submit" id="statusSubmitButton"
                            class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                        <i class="fas fa-save mr-1"></i>
                        Update Status
                    </button>
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
            // No need to set order_id in form as it's in the URL
            // Set the form action URL using the correct path with admin prefix
            document.getElementById('statusForm').action = `/admin/orders/${id}/status`;

            // Get the current status from the row
            const row = document.querySelector(`tr[data-order-id="${id}"]`);
            if (row) {
                const currentStatus = row.getAttribute('data-status');
                // Pre-select the current status in the dropdown
                const statusSelect = document.querySelector('select[name="status"]');
                if (statusSelect && currentStatus) {
                    for (let i = 0; i < statusSelect.options.length; i++) {
                        if (statusSelect.options[i].value === currentStatus) {
                            statusSelect.selectedIndex = i;
                            break;
                        }
                    }
                }

                // Update modal title and button based on status
                const modalTitle = document.getElementById('statusModalTitle');
                const submitButton = document.getElementById('statusSubmitButton');
                if (currentStatus === 'pending') {
                    if (modalTitle) modalTitle.textContent = 'Confirm Order';
                    if (submitButton) submitButton.textContent = 'Confirm Order';
                    // Pre-select 'processing' for pending orders
                    for (let i = 0; i < statusSelect.options.length; i++) {
                        if (statusSelect.options[i].value === 'processing') {
                            statusSelect.selectedIndex = i;
                            break;
                        }
                    }
                } else {
                    if (modalTitle) modalTitle.textContent = 'Update Order Status';
                    if (submitButton) submitButton.textContent = 'Update Status';
                }
            }

            // Show the modal
            document.getElementById('statusModal').classList.remove('hidden');
        }

        function closeStatusModal() {
            document.getElementById('statusModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
