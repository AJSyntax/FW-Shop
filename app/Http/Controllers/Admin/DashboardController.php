<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Design;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with analytics.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Total sales
        $totalSales = Order::where('status', '!=', 'cancelled')
            ->sum('total_amount');
            
        // Sales this month
        $salesThisMonth = Order::where('status', '!=', 'cancelled')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total_amount');
            
        // Sales last month
        $salesLastMonth = Order::where('status', '!=', 'cancelled')
            ->whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->sum('total_amount');
            
        // Calculate percentage change
        $salesPercentChange = $salesLastMonth > 0 
            ? round((($salesThisMonth - $salesLastMonth) / $salesLastMonth) * 100, 1) 
            : 100;
            
        // Total orders
        $totalOrders = Order::count();
        
        // Orders this month
        $ordersThisMonth = Order::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
            
        // Orders last month
        $ordersLastMonth = Order::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();
            
        // Calculate percentage change
        $ordersPercentChange = $ordersLastMonth > 0 
            ? round((($ordersThisMonth - $ordersLastMonth) / $ordersLastMonth) * 100, 1) 
            : 100;
            
        // Active users (users who have placed at least one order)
        $activeUsers = User::whereHas('orders')->count();
        
        // Users who placed orders this month
        $activeUsersThisMonth = User::whereHas('orders', function($query) {
                $query->whereMonth('created_at', Carbon::now()->month)
                    ->whereYear('created_at', Carbon::now()->year);
            })->count();
            
        // Users who placed orders last month
        $activeUsersLastMonth = User::whereHas('orders', function($query) {
                $query->whereMonth('created_at', Carbon::now()->subMonth()->month)
                    ->whereYear('created_at', Carbon::now()->subMonth()->year);
            })->count();
            
        // Calculate percentage change
        $usersPercentChange = $activeUsersLastMonth > 0 
            ? round((($activeUsersThisMonth - $activeUsersLastMonth) / $activeUsersLastMonth) * 100, 1) 
            : 100;
            
        // Total designs
        $totalDesigns = Design::count();
        
        // Designs added this month
        $designsThisMonth = Design::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
            
        // Popular designs (based on order items)
        $popularDesigns = Design::select('designs.*', DB::raw('COUNT(order_items.id) as sales_count'))
            ->join('order_items', 'designs.id', '=', 'order_items.design_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', '!=', 'cancelled')
            ->groupBy('designs.id')
            ->orderBy('sales_count', 'desc')
            ->take(5)
            ->get();
            
        // Count pending orders for admin notification
        $pendingOrdersCount = Order::where('status', 'pending')->count();

        // Get recent orders with a focus on pending ones
        $recentOrders = Order::with('user')
            ->latest()
            ->take(5)
            ->get();
            
        return view('dashboard', compact(
            'totalSales', 
            'salesPercentChange',
            'totalOrders', 
            'ordersPercentChange',
            'activeUsers', 
            'usersPercentChange',
            'totalDesigns', 
            'designsThisMonth',
            'popularDesigns',
            'pendingOrdersCount', 
            'recentOrders'
        ));
    }
}
