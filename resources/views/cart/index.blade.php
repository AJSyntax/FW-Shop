<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shopping Cart') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h3 class="text-2xl font-semibold mb-6">Your Cart</h3>

                {{-- Flash Messages --}}
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif
                 @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif
                 @if(session('info'))
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('info') }}</span>
                    </div>
                @endif

                @if(empty($cart))
                    <p class="text-gray-600">Your cart is currently empty.</p>
                    <a href="{{ route('buyer.home') }}" class="mt-4 inline-block bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                        Continue Shopping
                    </a>
                @else
                    {{-- Form for removing multiple items --}}
                    <form action="{{ route('cart.removeMultiple') }}" method="POST" id="remove-multiple-form">
                        @csrf
                        @method('DELETE')

                        <table class="min-w-full divide-y divide-gray-200 mb-6">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <input type="checkbox" id="select-all-items" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Product
                                    </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Price
                                </th>
                                {{-- Quantity column removed --}}
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subtotal
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($cart as $id => $details)
                                <tr>
                                     <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" name="ids[]" value="{{ $id }}" class="cart-item-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-16 w-16">
                                                <img class="h-16 w-16 rounded object-cover" src="{{ $details['image_path'] ?? asset('placeholder.jpg') }}" alt="{{ $details['title'] }}">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $details['title'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">${{ number_format($details['price'], 2) }}</div>
                                    </td>
                                    {{-- Quantity cell removed --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{-- Subtotal is just the price now since quantity is 1 --}}
                                        <div class="text-sm text-gray-900">${{ number_format($details['price'], 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        {{-- Individual Remove Form --}}
                                        <form action="{{ route('cart.remove', $id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to remove this item?')">Remove</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                     {{-- Remove Selected Button --}}
                    <div class="mb-6 text-right">
                         <button type="submit" form="remove-multiple-form" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600 disabled:opacity-50" id="remove-selected-btn" disabled>
                            Remove Selected
                        </button>
                    </div>
                    </form> {{-- End of remove-multiple-form --}}


                    <div class="text-right mb-6">
                        <h4 class="text-xl font-semibold">Total: ${{ number_format($total, 2) }}</h4>
                    </div>

                    <div class="flex justify-between items-center">
                        <a href="{{ route('buyer.home') }}" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-arrow-left mr-2"></i>Continue Shopping
                        </a>
                        {{-- Link to Checkout --}}
                        <a href="{{ route('checkout.index') }}" class="bg-green-500 text-white py-2 px-6 rounded hover:bg-green-600">
                            Proceed to Checkout<i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Custom Alpine.js Modal for Bulk Remove Confirmation --}}
    <div
        x-data="{ show: false }"
        x-show="show"
        x-on:open-bulk-remove-modal.window="show = true"
        x-on:keydown.escape.window="show = false"
        style="display: none;" {{-- Hide initially to prevent flash --}}
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
    >
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            {{-- Background overlay --}}
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="show = false" aria-hidden="true"></div>

            {{-- Modal panel --}}
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="show" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                <div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                        <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                            Confirm Removal
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" id="bulk-remove-confirmation-message">
                                Are you sure you want to remove the selected items?
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button type="button" id="confirm-bulk-remove-button"
                            class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Remove Items
                    </button>
                    <button type="button" @click="show = false"
                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- End Custom Modal --}}

</x-app-layout>

{{-- Include the external JavaScript file using a direct path --}}
<script src="/js/cart-modal.js"></script>
