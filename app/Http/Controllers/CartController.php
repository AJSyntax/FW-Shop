<?php

namespace App\Http\Controllers;

use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        $total = 0;

        // Calculate total price
        foreach ($cart as $id => $details) {
            $total += $details['price'] * $details['quantity'];
        }

        return view('cart.index', compact('cart', 'total'));
    }

    /**
     * Add a design to the shopping cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id Design ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Request $request, $id) // Keep Request for potential future use, but don't use quantity input
    {
        $design = Design::findOrFail($id);
        $cart = Session::get('cart', []);

        // Check stock
        if ($design->stock <= 0) {
            return redirect()->back()->with('error', 'This item is out of stock.');
        }

        // Check if item already exists in cart
        if (isset($cart[$id])) {
            return redirect()->back()->with('info', 'This design is already in your cart.');
        }

        // Add item to cart with quantity = 1
        $cart[$id] = [
            "title" => $design->title,
            "quantity" => 1, // Always add quantity 1
            "price" => $design->price,
            "image_path" => $design->image_path
        ];

        Session::put('cart', $cart);
        return redirect()->back()->with('success', 'Design added to cart successfully!');
    }

    /**
     * Remove a specific design from the shopping cart.
     *
     * @param  int  $id Design ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove($id)
    {
        $cart = Session::get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put('cart', $cart);
            return redirect()->back()->with('success', 'Design removed from cart successfully!');
        }

        return redirect()->back()->with('error', 'Design not found in cart.');
    }

     /**
     * Remove multiple designs from the shopping cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeMultiple(Request $request)
    {
        $idsToRemove = $request->input('ids', []);
        $cart = Session::get('cart', []);
        $removedCount = 0;

        if (empty($idsToRemove)) {
            return redirect()->back()->with('info', 'No designs selected for removal.');
        }

        foreach ($idsToRemove as $id) {
            if (isset($cart[$id])) {
                unset($cart[$id]);
                $removedCount++;
            }
        }

        if ($removedCount > 0) {
            Session::put('cart', $cart);
            return redirect()->back()->with('success', $removedCount . ' ' . \Str::plural('design', $removedCount) . ' removed from cart successfully!');
        }

        return redirect()->back()->with('error', 'Selected designs not found in cart or none selected.');
    }
}
