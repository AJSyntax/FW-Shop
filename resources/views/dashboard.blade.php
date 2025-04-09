<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Sales Card -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-500 bg-opacity-10">
                            <i class="fas fa-dollar-sign text-2xl text-blue-500"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500">Total Sales</p>
                            <h3 class="text-2xl font-bold">$15,350</h3>
                            <p class="text-green-500 text-sm">+12% from last month</p>
                        </div>
                    </div>
                </div>

                <!-- Total Orders Card -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-500 bg-opacity-10">
                            <i class="fas fa-shopping-cart text-2xl text-green-500"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500">Total Orders</p>
                            <h3 class="text-2xl font-bold">256</h3>
                            <p class="text-green-500 text-sm">+5% from last month</p>
                        </div>
                    </div>
                </div>

                <!-- Active Users Card -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-500 bg-opacity-10">
                            <i class="fas fa-users text-2xl text-purple-500"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500">Active Users</p>
                            <h3 class="text-2xl font-bold">1,245</h3>
                            <p class="text-green-500 text-sm">+8% from last month</p>
                        </div>
                    </div>
                </div>

                <!-- Total Designs Card -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-500 bg-opacity-10">
                            <i class="fas fa-tshirt text-2xl text-yellow-500"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500">Total Designs</p>
                            <h3 class="text-2xl font-bold">85</h3>
                            <p class="text-green-500 text-sm">+3 new this month</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Table -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Recent Orders</h2>
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentOrders ?? [] as $order)
                            <tr class="{{ $order->status === 'pending' ? 'bg-yellow-50' : '' }}">
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">{{ $order->order_number }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">{{ $order->user->name }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                                        @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                        @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">${{ number_format($order->total_amount, 2) }}</td>
                                <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">No orders found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4 flex justify-between">
                    @if($pendingOrdersCount > 0)
                        <a href="{{ route('orders.index', ['status' => 'pending']) }}" class="text-yellow-600 hover:text-yellow-800 font-medium">
                            <i class="fas fa-exclamation-circle mr-1"></i> View {{ $pendingOrdersCount }} Pending {{ Str::plural('Order', $pendingOrdersCount) }}
                        </a>
                    @else
                        <span></span>
                    @endif
                    <a href="{{ route('orders.index') }}" class="text-blue-600 hover:text-blue-800 font-medium">View All Orders <i class="fas fa-arrow-right ml-1"></i></a>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Popular Designs -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4">Popular Designs</h2>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <img src="https://res.cloudinary.com/dmygblav6/image/upload/v1738328684/3_emrheh.png" alt="Design 1" class="w-16 h-16 object-cover rounded">
                            <div class="ml-4">
                                <h4 class="font-medium">Cool T-Shirt Design</h4>
                                <p class="text-gray-500">45 sales this month</p>
                            </div>
                        </div>
                        <!-- Add more designs -->
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
                    <div class="grid grid-cols-2 gap-4">
                        {{-- Use admin prefixed routes for actions within the admin dashboard --}}
                        <a href="{{ route('admin.designs.create') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100">
                            <i class="fas fa-plus text-blue-500 mr-3"></i>
                            <span>Add New Design</span>
                        </a>
                        {{-- Note: orders.index is outside admin prefix, controller handles auth --}}
                        <a href="{{ route('orders.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 relative">
                            <i class="fas fa-shopping-cart text-green-500 mr-3"></i>
                            <span>View Orders</span>
                            @if(isset($pendingOrdersCount) && $pendingOrdersCount > 0)
                                <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">{{ $pendingOrdersCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100">
                            <i class="fas fa-users text-purple-500 mr-3"></i>
                            <span>Manage Users</span>
                        </a>
                        <a href="{{ route('admin.reports.index') }}" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100">
                            <i class="fas fa-chart-line text-yellow-500 mr-3"></i>
                            <span>View Reports</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
