<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config; // Import Config facade
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment; // Use SandboxEnvironment for testing
use PayPalCheckoutSdk\Core\ProductionEnvironment; // Use ProductionEnvironment for live
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;
use Exception; // Import base Exception class

class CheckoutController extends Controller
{
    private $payPalClient;

    /**
     * Constructor to set up PayPal client.
     */
    public function __construct()
    {
        $config = Config::get('paypal');
        $environment = $config['mode'] === 'sandbox'
            ? new SandboxEnvironment($config['sandbox']['client_id'], $config['sandbox']['client_secret'])
            : new ProductionEnvironment($config['live']['client_id'], $config['live']['client_secret']);

        $this->payPalClient = new PayPalHttpClient($environment);
    }
    /**
     * Display the checkout page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to proceed to checkout.');
        }

        // Get cart contents
        $cart = Session::get('cart', []);

        // If cart is empty, redirect back to cart page with a message
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty. Please add items before checking out.');
        }

        // Calculate total
        $total = 0;
        foreach ($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        // Get user information
        $user = Auth::user();

        return view('checkout.standard', compact('cart', 'total', 'user'));
    }

    /**
     * Process the checkout form and create the order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function payWithPayPal(Request $request)
    {
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return response()->json(['error' => 'Your cart is empty.'], 400);
        }

        $total = 0;
        foreach ($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        $paypalRequest = new OrdersCreateRequest();
        $paypalRequest->prefer('return=representation');
        $paypalRequest->body = [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "amount" => [
                    "currency_code" => Config::get('paypal.currency', 'USD'),
                    "value" => number_format($total, 2, '.', '') // Format total correctly
                ]
            ]],
            "application_context" => [
                "cancel_url" => route('checkout.paypal.cancel'),
                "return_url" => route('checkout.paypal.success')
            ]
        ];

        try {
            // Call PayPal to set up a transaction
            $response = $this->payPalClient->execute($paypalRequest);

            if ($response->statusCode == 201 && isset($response->result->id)) {
                 // Store cart in session temporarily with PayPal order ID association if needed for verification later
                 Session::put('paypal_order_id', $response->result->id);
                 Session::put('paypal_cart', $cart); // Store cart state at time of payment initiation
                 Session::put('paypal_total', $total); // Store total at time of payment initiation

                // Return the Order ID to the client.
                return response()->json(['id' => $response->result->id]);
            } else {
                 \Log::error('PayPal Order Creation Failed: Status ' . $response->statusCode, (array)$response->result);
                 return response()->json(['error' => 'Failed to create PayPal order.'], 500);
            }

        } catch (Exception $e) {
            \Log::error('PayPal API Error: ' . $e->getMessage());
            // Consider more specific error handling based on PayPalHttp\HttpException if needed
            return response()->json(['error' => 'Could not connect to PayPal. Please try again later.'], 500);
        }
    }

    /**
     * Handle successful payment return from PayPal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function paypalSuccess(Request $request)
    {
        $paypalOrderId = $request->input('token'); // PayPal returns the Order ID as 'token' in the query string
        $storedPaypalOrderId = Session::get('paypal_order_id');
        $cart = Session::get('paypal_cart'); // Retrieve cart state from session
        $total = Session::get('paypal_total'); // Retrieve total from session
        $user = Auth::user();

        // Basic validation
        if (!$paypalOrderId || $paypalOrderId !== $storedPaypalOrderId || empty($cart) || !$user) {
            \Log::warning('PayPal Success - Invalid state:', [
                'request_token' => $paypalOrderId,
                'session_token' => $storedPaypalOrderId,
                'cart_empty' => empty($cart),
                'user_id' => $user->id ?? null
            ]);
            // Clear potentially compromised session data
            Session::forget(['paypal_order_id', 'paypal_cart', 'paypal_total']);
            return redirect()->route('checkout.index')->with('error', 'Invalid payment details or session expired. Please try again.');
        }

        $captureRequest = new OrdersCaptureRequest($paypalOrderId);
        $captureRequest->prefer('return=representation');

        try {
            // Capture the payment
            $response = $this->payPalClient->execute($captureRequest);

            // Check if capture was successful
            if ($response->statusCode == 201 && isset($response->result->status) && $response->result->status == 'COMPLETED') {
                // Payment successful, create the order in DB
                DB::beginTransaction();
                try {
                    $order = Order::create([
                        'user_id' => $user->id,
                        'order_number' => 'ORD-' . time() . '-' . $user->id, // Consider a more robust unique ID
                        'total_amount' => $total,
                        'status' => 'pending', // Set to pending for admin confirmation
                        'payment_status' => 'paid',
                        'payment_method' => 'paypal',
                        'transaction_id' => $paypalOrderId, // Store PayPal Order ID
                        // Add address details if collected
                    ]);

                    foreach ($cart as $id => $details) {
                        OrderItem::create([
                            'order_id' => $order->id,
                            'design_id' => $id,
                            'quantity' => $details['quantity'],
                            'price' => $details['price'],
                        ]);
                        // No stock management needed for digital products
                    }

                    DB::commit();

                    // Clear session data related to this transaction
                    Session::forget(['cart', 'paypal_order_id', 'paypal_cart', 'paypal_total']);

                    return redirect()->route('checkout.success', ['order' => $order->id])
                                     ->with('success', 'Payment successful! Your order has been placed.');

                } catch (\Exception $e) {
                    DB::rollBack();
                    \Log::error('Order creation failed after PayPal success: ' . $e->getMessage(), ['paypal_order_id' => $paypalOrderId]);
                    // Potentially try to refund the PayPal transaction here if possible/necessary
                    return redirect()->route('checkout.index')->with('error', 'Payment was successful, but there was an issue creating your order. Please contact support.');
                }
            } else {
                 \Log::error('PayPal Capture Failed: Status ' . $response->statusCode, (array)$response->result);
                 // Clear potentially compromised session data
                 Session::forget(['paypal_order_id', 'paypal_cart', 'paypal_total']);
                 return redirect()->route('checkout.index')->with('error', 'Failed to capture PayPal payment. Please try again.');
            }

        } catch (Exception $e) {
            \Log::error('PayPal Capture API Error: ' . $e->getMessage(), ['paypal_order_id' => $paypalOrderId]);
             // Clear potentially compromised session data
             Session::forget(['paypal_order_id', 'paypal_cart', 'paypal_total']);
            return redirect()->route('checkout.index')->with('error', 'Could not finalize payment with PayPal. Please try again later.');
        }
    }

    /**
     * Handle payment cancellation from PayPal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function paypalCancel(Request $request)
    {
        // Clear any temporary session data if needed
        Session::forget(['paypal_order_id', 'paypal_cart', 'paypal_total']);

        return redirect()->route('checkout.index')->with('info', 'You cancelled the PayPal payment.');
    }

    /**
     * Process the standard checkout form and create the order.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function processStandardCheckout(Request $request)
    {
        // Get cart contents
        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty. Please add items before checking out.');
        }

        // Calculate total
        $total = 0;
        foreach ($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to proceed to checkout.');
        }

        // Create the order
        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . time() . '-' . $user->id,
                'total_amount' => $total,
                'status' => 'pending', // Set to pending for admin confirmation
                'payment_status' => 'pending', // Since we're not using PayPal, mark as pending
                'payment_method' => 'standard',
                'shipping_address' => 'Digital delivery', // For digital products
                'billing_address' => 'Digital delivery', // For digital products
                'transaction_id' => 'STD-' . Str::random(10), // Generate a random transaction ID
            ]);

            foreach ($cart as $id => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'design_id' => $id,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ]);
                // No stock management needed for digital products
            }

            DB::commit();

            // Clear cart after successful order creation
            Session::forget('cart');

            return redirect()->route('checkout.success', ['order' => $order->id])
                             ->with('success', 'Your order has been placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Order creation failed: ' . $e->getMessage());
            return redirect()->route('checkout.index')->with('error', 'There was an issue processing your order. Please try again.');
        }
    }

    /**
     * Display the order success page.
     *
     * @param  Order $order
     * @return \Illuminate\View\View
     */
    public function success(Order $order)
    {
         // Ensure the user viewing the success page is the one who placed the order
         if ($order->user_id !== Auth::id()) {
            // Or redirect to their order history, or show a generic message
            abort(403, 'Unauthorized action.');
        }
        // We'll create the view 'checkout.success' next
        return view('checkout.success', compact('order'));
    }
}
