<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items.design'])->latest()->paginate(10);
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
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