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

                {{-- Section for Shipping Address (Keep or modify as needed) --}}
                <div class="mb-6 border-b pb-4">
                    <h4 class="text-lg font-medium mb-3">Shipping Address</h4>
                    <p class="text-gray-600">Placeholder for shipping address form fields... (Collect if needed)</p>
                    {{-- Add address fields here if required before payment --}}
                </div>

                {{-- Order Summary --}}
                <div class="mb-6 border-b pb-4">
                    <h4 class="text-lg font-medium mb-3">Order Summary</h4>
                    <ul class="list-disc list-inside text-sm text-gray-700">
                        @forelse($cart as $id => $details)
                            <li>{{ $details['title'] }} (x{{ $details['quantity'] }}) - ${{ number_format($details['price'] * $details['quantity'], 2) }}</li>
                        @empty
                            <li>Your cart is empty.</li>
                        @endforelse
                    </ul>
                    @if(!empty($cart))
                        <p class="mt-2 font-semibold">Total: ${{ number_format($total, 2) }}</p>
                    @endif
                </div>

                {{-- Payment Section --}}
                <div class="mb-6">
                    <h4 class="text-lg font-medium mb-3">Payment Method</h4>

                    {{-- PayPal Button Container --}}
                    <div id="paypal-button-container" class="mt-4">
                        {{-- PayPal button will be rendered here by the SDK --}}
                    </div>
                    <div id="paypal-error" class="text-red-600 text-sm mt-2" style="display: none;"></div> {{-- Error message display --}}

                </div>

            </div>
        </div>
    </div>

    {{-- Add PayPal SDK Script --}}
    {{-- Make sure the client ID is correctly passed from the controller --}}
    @isset($paypalClientId)
    <script src="https://www.paypal.com/sdk/js?client-id={{ $paypalClientId }}&currency={{ config('paypal.currency', 'USD') }}&intent=capture"></script>

    <script>
        // Check if cart is empty before rendering button
        const cartIsEmpty = {{ empty($cart) ? 'true' : 'false' }};
        const paypalErrorDiv = document.getElementById('paypal-error');

        function showPayPalError(message) {
            if (paypalErrorDiv) {
                paypalErrorDiv.textContent = 'Error: ' + message + ' Please refresh the page or contact support.';
                paypalErrorDiv.style.display = 'block';
            }
            console.error('PayPal Error:', message);
        }

        if (!cartIsEmpty) {
            paypal.Buttons({
                // Sets up the transaction when a payment button is clicked
                createOrder: function(data, actions) {
                    return fetch('{{ route('checkout.paypal.create') }}', { // Use the named route
                        method: 'post',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token
                        },
                        // No body needed if cart is read from session on backend
                    }).then(function(res) {
                        if (!res.ok) {
                            // Handle non-JSON error responses or network errors
                            return res.text().then(text => { throw new Error(text || 'Server error during order creation') });
                        }
                        return res.json();
                    }).then(function(orderData) {
                        if (orderData.id) {
                            return orderData.id;
                        } else {
                            // Handle errors returned in JSON, e.g., { error: '...' }
                            throw new Error(orderData.error || 'Could not retrieve PayPal order ID.');
                        }
                    }).catch(function(err) {
                        showPayPalError(err.message);
                        // Disable button or provide feedback
                        // Example: actions.disable();
                    });
                },

                // Finalize the transaction after payer approval
                onApprove: function(data, actions) {
                    // data.orderID contains the PayPal Order ID
                    // Redirect the user to the success route, passing the order ID
                    // The backend will capture the payment on this route
                    window.location.href = '{{ route('checkout.paypal.success') }}?token=' + data.orderID;
                },

                // Handle cancellation by the user
                onCancel: function(data) {
                    // data.orderID contains the PayPal Order ID
                    // Redirect the user to the cancel route
                    window.location.href = '{{ route('checkout.paypal.cancel') }}';
                },

                // Handle errors from PayPal button actions
                onError: function(err) {
                    console.error('PayPal Button onError:', err);
                    showPayPalError('An error occurred with the PayPal button.');
                    // Optionally, re-enable the button or provide specific feedback
                    // actions.enable();
                }
            }).render('#paypal-button-container').catch(function (err) {
                 // Handle errors during button rendering
                 console.error('PayPal Button Rendering Error:', err);
                 showPayPalError('Could not render the PayPal button.');
            });
        } else {
            // Optional: Display a message if cart is empty where the button would be
            document.getElementById('paypal-button-container').innerHTML = '<p class="text-gray-500">Add items to your cart to proceed with payment.</p>';
        }
    </script>
    @else
        <p class="text-red-600 p-6">PayPal Client ID not configured. Payment cannot proceed.</p>
    @endisset

</x-app-layout>
