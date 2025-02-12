<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Order #12345</h2>
                    <div class="flex space-x-2">
                        <button onclick="window.history.back()" class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                            <i class="fas fa-arrow-left mr-2"></i>Back
                        </button>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="text-lg font-medium mb-2">Customer Information</h3>
                        <p>Name: John Doe</p>
                        <p>Email: john@example.com</p>
                        <p>Phone: (555) 123-4567</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium mb-2">Shipping Address</h3>
                        <p>123 Main St</p>
                        <p>Apt 4B</p>
                        <p>New York, NY 10001</p>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium mb-2">Order Items</h3>
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b">Item</th>
                                <th class="py-2 px-4 border-b">Quantity</th>
                                <th class="py-2 px-4 border-b">Price</th>
                                <th class="py-2 px-4 border-b">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="py-2 px-4 border-b">Cool T-Shirt</td>
                                <td class="py-2 px-4 border-b">2</td>
                                <td class="py-2 px-4 border-b">$20.00</td>
                                <td class="py-2 px-4 border-b">$40.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Order Summary -->
                <div class="flex justify-end">
                    <div class="w-1/3">
                        <div class="border-t pt-4">
                            <div class="flex justify-between mb-2">
                                <span>Subtotal:</span>
                                <span>$40.00</span>
                            </div>
                            <div class="flex justify-between mb-2">
                                <span>Shipping:</span>
                                <span>$5.00</span>
                            </div>
                            <div class="flex justify-between font-bold">
                                <span>Total:</span>
                                <span>$45.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 