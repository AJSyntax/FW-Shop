<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
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

        // TODO: Add logic to fetch user address, payment methods if needed

        // Return the checkout view (we'll create this next)
        return view('checkout.index', compact('cart', 'total'));
    }

    // TODO: Add store method for processing checkout form
    // TODO: Add summary method/view for final confirmation step
}
