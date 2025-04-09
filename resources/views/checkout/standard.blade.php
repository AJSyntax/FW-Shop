<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8">
                <h3 class="text-2xl font-semibold mb-6">Complete Your Order</h3>

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Digital Product Notice -->
                <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded">
                    <h4 class="font-medium text-blue-800 mb-2">Digital Product Information</h4>
                    <p class="text-sm text-blue-700 mb-2">You are purchasing digital design files that will be available for download after your order is confirmed by our team.</p>
                    <p class="text-sm text-blue-700">No physical products will be shipped. You'll receive access to download your designs from your order history.</p>
                </div>

                <!-- Order Summary -->
                <div class="mb-6 border-b pb-4">
                    <h4 class="text-lg font-medium mb-3">Order Summary</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="px-4 py-2 text-left">Product</th>
                                    <th class="px-4 py-2 text-left">Quantity</th>
                                    <th class="px-4 py-2 text-left">Price</th>
                                    <th class="px-4 py-2 text-left">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cart as $id => $details)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $details['title'] }}</td>
                                        <td class="border px-4 py-2">{{ $details['quantity'] }}</td>
                                        <td class="border px-4 py-2">${{ number_format($details['price'], 2) }}</td>
                                        <td class="border px-4 py-2">${{ number_format($details['price'] * $details['quantity'], 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="border px-4 py-2 text-center">Your cart is empty</td>
                                    </tr>
                                @endforelse
                                <tr class="bg-gray-50">
                                    <td colspan="3" class="border px-4 py-2 text-right font-bold">Total:</td>
                                    <td class="border px-4 py-2 font-bold">${{ number_format($total, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Checkout Form -->
                <form action="{{ route('checkout.process') }}" method="POST">
                    @csrf

                    <!-- Customer Information -->
                    <div class="mb-6">
                        <h4 class="text-lg font-medium mb-3">Customer Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                <input type="text" id="name" name="name" value="{{ $user->name }}" class="w-full px-3 py-2 border rounded-md" readonly>
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" id="email" name="email" value="{{ $user->email }}" class="w-full px-3 py-2 border rounded-md" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Order Notes -->
                    <div class="mb-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-1">Order Notes (Optional)</label>
                        <textarea id="notes" name="notes" rows="3" class="w-full px-3 py-2 border rounded-md" placeholder="Any special instructions or notes for your order"></textarea>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="mb-6">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="terms" name="terms" type="checkbox" required class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="terms" class="font-medium text-gray-700">I agree to the terms and conditions</label>
                                <p class="text-gray-500">By placing this order, you agree to our terms of service and privacy policy.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Place Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
