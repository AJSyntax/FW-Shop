<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth; // Import Auth facade

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Order::with(['user', 'items.design'])->latest();

        if ($user->isBuyer()) {
            // Buyers only see their own orders
            $query->where('user_id', $user->id);
        }
        // Admins see all orders (no additional where clause)

        $orders = $query->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $user = Auth::user();

        // Check if the user is an admin or if the order belongs to the buyer
        if (!$user->isAdmin() && $order->user_id !== $user->id) {
            abort(403, 'Unauthorized action.'); // Or redirect with an error
        }

        $order->load(['user', 'items.design']);
        return view('orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $order->update($validated);
        return redirect()->back()->with('success', 'Order status updated successfully!');
    }

    public function history()
    {
        $orders = Order::where('user_id', auth()->id())
                      ->latest()
                      ->paginate(10);
        return view('orders.history', compact('orders'));
    }

    public function track(Request $request)
    {
        $order = null;
        if ($request->has('order_number')) {
            $order = Order::where('order_number', $request->order_number)
                         ->where('user_id', auth()->id())
                         ->first();
        }
        return view('orders.track', compact('order'));
    }
}
