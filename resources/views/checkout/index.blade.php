<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-8">
                <h3 class="text-2xl font-semibold mb-6">Checkout Details</h3>

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                @endif

                {{-- Alpine.js State for Modal --}}
                <div x-data="{ confirmingOrder: false }">

                    {{-- Checkout Form --}}
                    {{-- TODO: Replace with actual checkout form (address, payment, etc.) --}}
                    <form action="{{ route('order.store') }}" method="POST" id="checkout-form"> {{-- Placeholder action, will likely change --}}
                        @csrf

                        {{-- Section for Shipping Address --}}
                    <div class="mb-6 border-b pb-4">
                        <h4 class="text-lg font-medium mb-3">Shipping Address</h4>
                        <p class="text-gray-600">Placeholder for shipping address form fields...</p>
                        {{-- Example fields:
                        <input type="text" name="address_line1" placeholder="Address Line 1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <input type="text" name="city" placeholder="City" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        ... etc ...
                        --}}
                    </div>

                    {{-- Section for Payment Details --}}
                    <div class="mb-6 border-b pb-4">
                        <h4 class="text-lg font-medium mb-3">Payment Details</h4>
                        <p class="text-gray-600">Placeholder for payment details form fields...</p>
                         {{-- Example fields:
                        <input type="text" name="card_number" placeholder="Card Number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        ... etc ...
                        --}}
                    </div>

                    {{-- Order Summary (Optional Preview) --}}
                    <div class="mb-6">
                        <h4 class="text-lg font-medium mb-3">Order Summary Preview</h4>
                        <ul class="list-disc list-inside text-sm text-gray-700">
                            @foreach($cart as $id => $details)
                                <li>{{ $details['title'] }} (x{{ $details['quantity'] }}) - ${{ number_format($details['price'] * $details['quantity'], 2) }}</li>
                            @endforeach
                        </ul>
                        <p class="mt-2 font-semibold">Total: ${{ number_format($total, 2) }}</p>
                    </div>

                    {{-- Proceed Button --}}
                        {{-- Proceed Button - Triggers Modal --}}
                        <div class="text-right">
                            <button type="button" @click="confirmingOrder = true" class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition duration-150 ease-in-out">
                                Proceed to Confirmation
                            </button>
                        </div>

                    </form>

                    {{-- Confirmation Modal --}}
                    <x-confirmation-modal wire:model.live="confirmingOrder" x-show="confirmingOrder" @close="confirmingOrder = false">
                        <x-slot name="title">
                            Confirm Order
                        </x-slot>

                        <x-slot name="content">
                            Are you sure you want to place this order? Please review your details before confirming.
                            <br><br>
                            {{-- Optionally show summary again here --}}
                            <strong>Total: ${{ number_format($total, 2) }}</strong>
                        </x-slot>

                        <x-slot name="footer">
                            <x-secondary-button @click="confirmingOrder = false">
                                Cancel
                            </x-secondary-button>

                            <x-danger-button class="ms-3" type="submit" form="checkout-form"> {{-- Submits the form --}}
                                Place Order
                            </x-danger-button>
                        </x-slot>
                    </x-confirmation-modal>
                    {{-- End Confirmation Modal --}}

                </div> {{-- End Alpine.js x-data --}}

            </div>
        </div>
    </div>
</x-app-layout>
