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
                        <tr>
                            <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">#12345</td>
                            <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">John Doe</td>
                            <td class="px-6 py-4 whitespace-no-wrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                            </td>
                            <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-900">$125.00</td>
                            <td class="px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-500">2024-01-20</td>
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
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
                        <a href="{{ route('designs.create') }}" class="flex items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100">
                            <i class="fas fa-plus text-blue-500 mr-3"></i>
                            <span>Add New Design</span>
                        </a>
                        <a href="{{ route('orders.index') }}" class="flex items-center p-4 bg-green-50 rounded-lg hover:bg-green-100">
                            <i class="fas fa-shopping-cart text-green-500 mr-3"></i>
                            <span>View Orders</span>
                        </a>
                        <a href="{{ route('users.index') }}" class="flex items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100">
                            <i class="fas fa-users text-purple-500 mr-3"></i>
                            <span>Manage Users</span>
                        </a>
                        <a href="{{ route('reports.index') }}" class="flex items-center p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100">
                            <i class="fas fa-chart-line text-yellow-500 mr-3"></i>
                            <span>View Reports</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
