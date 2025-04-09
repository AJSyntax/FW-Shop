<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-6">Reports Dashboard</h2>

                <!-- Report Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Sales Report Card -->
                    <a href="{{ route('admin.reports.sales') }}" class="block p-6 bg-blue-50 rounded-lg hover:bg-blue-100 transition">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-500 text-white">
                                <i class="fas fa-chart-line text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-blue-700">Sales Report</h3>
                                <p class="text-blue-600">View detailed sales analytics</p>
                            </div>
                        </div>
                    </a>

                    <!-- Orders Report Card -->
                    <a href="{{ route('admin.reports.orders') }}" class="block p-6 bg-green-50 rounded-lg hover:bg-green-100 transition">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-500 text-white">
                                <i class="fas fa-shopping-cart text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-green-700">Orders Report</h3>
                                <p class="text-green-600">Track order statistics</p>
                            </div>
                        </div>
                    </a>

                    <!-- Users Report Card -->
                    <a href="{{ route('admin.reports.users') }}" class="block p-6 bg-purple-50 rounded-lg hover:bg-purple-100 transition">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-500 text-white">
                                <i class="fas fa-users text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-purple-700">Users Report</h3>
                                <p class="text-purple-600">Analyze user activity</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Recent Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Recent Sales Chart -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-4">Recent Sales</h3>
                        <div class="h-64 bg-gray-50 rounded flex items-center justify-center">
                            <p class="text-gray-500">Sales chart will be displayed here</p>
                        </div>
                    </div>

                    <!-- Top Products -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-4">Top Products</h3>
                        <div class="space-y-4">
                            @forelse($topProducts ?? [] as $product)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <img src="{{ $product->image_path }}" alt="{{ $product->title }}" class="w-12 h-12 object-cover rounded">
                                        <div class="ml-4">
                                            <p class="text-sm font-medium text-gray-900">{{ $product->title }}</p>
                                            <p class="text-sm text-gray-500">{{ $product->sales_count }} sales</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">${{ number_format($product->revenue, 2) }}</span>
                                </div>
                            @empty
                                <p class="text-gray-500 text-center py-4">No product data available</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>