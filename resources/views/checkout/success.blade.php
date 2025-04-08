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
                        <p>We have received your order and will process it shortly. You can view your order details in your <a href="{{ route('orders.history') }}" class="text-indigo-600 hover:text-indigo-900">Order History</a>.</p>
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
