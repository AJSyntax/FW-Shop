<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Design;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Get top products
        $topProducts = Design::withCount(['orderItems as sales_count'])
            ->withSum('orderItems as revenue', DB::raw('order_items.price * order_items.quantity'))
            ->orderByDesc('sales_count')
            ->limit(5)
            ->get();

        return view('reports.index', compact('topProducts'));
    }

    public function sales()
    {
        $salesData = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_amount) as total_sales'),
            DB::raw('COUNT(*) as orders_count')
        )
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->limit(30)
        ->get();

        return view('reports.sales', compact('salesData'));
    }

    public function orders()
    {
        $orderStats = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        return view('reports.orders', compact('orderStats'));
    }

    public function users()
    {
        $userStats = [
            'total' => User::count(),
            'active' => User::where('is_blocked', false)->count(),
            'blocked' => User::where('is_blocked', true)->count(),
            'new_this_month' => User::whereMonth('created_at', now()->month)->count(),
        ];

        return view('reports.users', compact('userStats'));
    }
} 