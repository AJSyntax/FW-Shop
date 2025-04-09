<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Order Successful') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="mt-8 text-2xl">
                        Thank you for your order!
                    </div>

                    <div class="mt-6 text-gray-500">
                        <p>Your order has been placed successfully.</p>
                        <p>Your Order Number is: <strong>{{ $order->order_number }}</strong></p>
                        <p>We have received your order and it is now <span class="font-semibold text-yellow-600">pending confirmation</span> by our team. You can view your order details and track its status in your <a href="{{ route('orders.history') }}" class="text-indigo-600 hover:text-indigo-900">Order History</a>.</p>

                        <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded">
                            <p class="text-yellow-700"><i class="fas fa-info-circle mr-2"></i> Your order is currently awaiting confirmation by our team. Once confirmed, you will be able to download your design files from your order history.</p>
                        </div>

                        <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded">
                            <h4 class="font-medium text-blue-800 mb-2">How to Access Your Designs</h4>
                            <ol class="list-decimal list-inside text-sm text-blue-700 space-y-1">
                                <li>Wait for admin confirmation (usually within 24 hours)</li>
                                <li>Visit your <a href="{{ route('orders.history') }}" class="underline font-semibold">Order History</a></li>
                                <li>Find this order and click on "View Details"</li>
                                <li>Download your design files from the order details page</li>
                            </ol>
                        </div>
                        {{-- You can add more details here if needed, like a summary of items --}}
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('buyer.home') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
