<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Track Order</h2>

                <!-- Search Form -->
                <div class="mb-8">
                    <form action="{{ route('orders.track') }}" method="GET" class="flex gap-4">
                        <div class="flex-1">
                            <input type="text" name="order_number" 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                   placeholder="Enter Order Number"
                                   value="{{ request('order_number') }}">
                        </div>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            Track Order
                        </button>
                    </form>
                </div>

                <!-- Order Status -->
                @if(isset($order))
                    <div class="border rounded-lg p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold">Order #{{ $order->order_number }}</h3>
                            <span class="px-3 py-1 rounded-full text-sm 
                                @if($order->status === 'delivered') bg-green-100 text-green-800
                                @elseif($order->status === 'processing') bg-yellow-100 text-yellow-800
                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                @else bg-blue-100 text-blue-800 @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        <!-- Order Timeline -->
                        <div class="relative">
                            <!-- Add timeline steps here based on your order statuses -->
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout> 