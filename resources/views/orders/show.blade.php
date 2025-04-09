<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Order #{{ $order->order_number }}</h2>
                    <div class="flex space-x-2">
                        <button onclick="window.history.back()" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                            <i class="fas fa-arrow-left mr-2"></i>Back
                        </button>
                    </div>
                </div>

                <!-- Order Status -->
                <div class="mb-6 p-4 {{ $order->status === 'pending' ? 'bg-yellow-50 border border-yellow-200' : ($order->status === 'delivered' ? 'bg-blue-50 border border-blue-200' : 'bg-green-50 border border-green-200') }} rounded">
                    <div class="flex items-center">
                        <div class="mr-3">
                            @if($order->status === 'pending')
                                <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @elseif($order->status === 'delivered')
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @else
                                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-medium {{ $order->status === 'pending' ? 'text-yellow-700' : ($order->status === 'delivered' ? 'text-blue-700' : 'text-green-700') }}">
                                Order Status: {{ ucfirst($order->status) }}
                            </h3>
                            <p class="text-sm {{ $order->status === 'pending' ? 'text-yellow-600' : ($order->status === 'delivered' ? 'text-blue-600' : 'text-green-600') }}">
                                @if($order->status === 'pending')
                                    Your order is awaiting confirmation. Downloads will be available once confirmed.
                                @elseif($order->status === 'processing')
                                    Your order has been confirmed. You can now download your design files.
                                @elseif($order->status === 'shipped')
                                    Your order is being processed. You can download your design files.
                                @elseif($order->status === 'delivered')
                                    Your order is complete. Thank you for your purchase!
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-lg font-medium mb-2">Order Information</h3>
                        <p><span class="font-medium">Order Date:</span> {{ $order->created_at->format('M d, Y') }}</p>
                        <p><span class="font-medium">Payment Method:</span> {{ ucfirst($order->payment_method) }}</p>
                        <p><span class="font-medium">Payment Status:</span> {{ ucfirst($order->payment_status) }}</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium mb-2">Customer Information</h3>
                        <p><span class="font-medium">Name:</span> {{ $order->user->name }}</p>
                        <p><span class="font-medium">Email:</span> {{ $order->user->email }}</p>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium mb-2">Order Items</h3>
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-2 px-4 border-b text-left">Item</th>
                                <th class="py-2 px-4 border-b text-left">Quantity</th>
                                <th class="py-2 px-4 border-b text-left">Price</th>
                                <th class="py-2 px-4 border-b text-left">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="py-2 px-4 border-b">{{ $item->design->title }}</td>
                                    <td class="py-2 px-4 border-b">{{ $item->quantity }}</td>
                                    <td class="py-2 px-4 border-b">${{ number_format($item->price, 2) }}</td>
                                    <td class="py-2 px-4 border-b">${{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Order Summary -->
                <div class="flex justify-end">
                    <div class="w-full md:w-1/3">
                        <div class="border-t pt-4">
                            <div class="flex justify-between mb-2">
                                <span class="font-medium">Total:</span>
                                <span class="font-bold">${{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Download Section -->
                <div id="downloads" class="mt-8 pt-6 border-t">
                    <h3 class="text-lg font-medium mb-2">Design Downloads</h3>
                    <p class="text-sm text-gray-600 mb-4">Click the buttons below to download your design files directly to your device.</p>

                    @if($order->status === 'pending')
                        <div class="p-4 bg-yellow-50 border border-yellow-200 rounded">
                            <p class="text-yellow-700">
                                <i class="fas fa-info-circle mr-2"></i>
                                Downloads will be available once your order is confirmed by our team.
                            </p>
                        </div>
                    @elseif($order->status === 'delivered')
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded mb-4">
                            <p class="text-blue-700">
                                <i class="fas fa-check-circle mr-2"></i>
                                You have already downloaded your design files. If you need to download them again, please use the buttons below.
                            </p>
                        </div>
                        @if($order->items->count() > 1)
                            <div class="mb-4">
                                <a href="{{ route('orders.designs.download-all', ['order' => $order->id]) }}"
                                   class="flex items-center justify-center w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded"
                                   download>
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                    </svg>
                                    Download All Designs as ZIP
                                </a>
                                <p class="text-sm text-gray-600 mt-2 text-center">Contains all {{ $order->items->count() }} designs as PNG files</p>
                            </div>
                        @endif
                    @else
                        @if($order->items->count() > 1)
                            <div class="mb-4">
                                <a href="{{ route('orders.designs.download-all', ['order' => $order->id]) }}"
                                   class="flex items-center justify-center w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded"
                                   download>
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                                    </svg>
                                    Download All Designs as ZIP
                                </a>
                                <p class="text-sm text-gray-600 mt-2 text-center">Contains all {{ $order->items->count() }} designs as PNG files</p>
                            </div>
                        @endif
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($order->items as $item)
                                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-center mb-2">
                                        <img src="{{ $item->design->image_url }}" alt="{{ $item->design->title }}" class="w-16 h-16 object-cover rounded mr-3">
                                        <div>
                                            <h4 class="font-medium">{{ $item->design->title }}</h4>
                                            <p class="text-sm text-gray-600">{{ $item->quantity }} x ${{ number_format($item->price, 2) }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('orders.designs.download', ['order' => $order->id, 'design' => $item->design->id]) }}"
                                       class="mt-2 flex items-center justify-center w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded"
                                       download>
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        Download Design
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>