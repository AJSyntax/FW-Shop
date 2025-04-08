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
    public function add(Request $request, $id)
    {
        $design = Design::findOrFail($id);
        $cart = Session::get('cart', []);

        // If cart is empty then this the first product
        if (!$cart) {
            $cart = [
                $id => [
                    "title" => $design->title,
                    "quantity" => 1,
                    "price" => $design->price,
                    "image_path" => $design->image_path
                ]
            ];
            Session::put('cart', $cart);
            return redirect()->back()->with('success', 'Design added to cart successfully!');
        }

        // If cart not empty then check if this product exist then increment quantity
        if (isset($cart[$id])) {
            $cart[$id]['quantity']++;
            Session::put('cart', $cart);
            return redirect()->back()->with('success', 'Design quantity updated in cart!');
        }

        // If item not exist in cart then add to cart with quantity = 1
        $cart[$id] = [
            "title" => $design->title,
            "quantity" => 1,
            "price" => $design->price,
            "image_path" => $design->image_path
        ];

        Session::put('cart', $cart);
        return redirect()->back()->with('success', 'Design added to cart successfully!');
    }

    // TODO: Implement update and remove methods
}
