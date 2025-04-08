<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DesignController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CartController; // Import CartController
use App\Http\Controllers\CheckoutController; // Import CheckoutController
use Illuminate\Support\Facades\Auth; // Import Auth facade
use App\Models\Design; // Import Design model

// Public routes
Route::get('/', function () {
    // If authenticated, redirect based on role, otherwise show welcome
    if (Auth::check()) {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard'); // Redirect admin to admin dashboard
        } else {
            return redirect()->route('buyer.home'); // Redirect buyer to buyer home
        }
    }
    return view('welcome');
})->name('welcome');

// Routes for authenticated users (Buyers and Admins)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Buyer specific home page
    Route::get('/home', function () {
        $user = Auth::user();
        // Ensure only buyers access this, or handle redirection if admin lands here
        if ($user->isBuyer()) {
            // Fetch latest designs (adjust query as needed, e.g., limit, order)
            $designs = Design::latest()->take(12)->get(); // Get latest 12 designs
            return view('buyer.home', compact('designs')); // Pass designs to the view
        } else {
            // Optional: Redirect admin if they somehow land here
             return redirect()->route('admin.dashboard');
        }
    })->name('buyer.home');

    // Routes accessible by both buyers (filtered in controller) and admins
    Route::get('/designs', [DesignController::class, 'index'])->name('designs.index'); // Public/Buyer view of designs
    Route::get('/designs/{design}', [DesignController::class, 'show'])->name('designs.show'); // Public/Buyer view of single design

    // Order routes accessible by buyers (their own) and admins (all)
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index'); // Admins see all, buyers see own (controller logic needed)
    Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history'); // Buyers see own history (controller logic needed)
    Route::get('/orders/track', [OrderController::class, 'track'])->name('orders.track'); // Buyers track own orders (controller logic needed)
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show'); // Admins see any, buyers see own (controller logic needed)

    // Cart Routes (Buyers)
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{design}', [CartController::class, 'add'])->name('cart.add'); // Use POST for adding items

    // Checkout Routes (Buyers)
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    // TODO: Add POST route for submitting checkout form

    // Generic dashboard - might be replaced by role-specific ones or redirect logic
    // Route::get('/dashboard', function () {
    //     if (Auth::user()->isAdmin()) {
    //         return redirect()->route('admin.dashboard');
    //     } else {
    //         return redirect()->route('buyer.home');
    //     }
    // })->name('dashboard'); // Keep original dashboard route name for compatibility if needed, but redirect

});

// Routes specifically for Admins
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'admin', // Apply the admin middleware
])->prefix('admin')->name('admin.')->group(function () { // Prefix routes with 'admin/' and names with 'admin.'

    Route::get('/dashboard', function () {
        // This could be the original dashboard view or a specific admin one
        return view('dashboard'); // Or redirect to a specific admin dashboard view if created
    })->name('dashboard'); // admin.dashboard

    // Design Management (Admin only)
    Route::get('/designs/manage', [DesignController::class, 'manage'])->name('designs.manage'); // admin.designs.manage
    Route::get('/designs/create', [DesignController::class, 'create'])->name('designs.create'); // admin.designs.create
    Route::post('/designs', [DesignController::class, 'store'])->name('designs.store'); // admin.designs.store
    // Edit route is needed as linked from manage view
    Route::get('/designs/{design}/edit', [DesignController::class, 'edit'])->name('designs.edit'); // admin.designs.edit
    Route::put('/designs/{design}', [DesignController::class, 'update'])->name('designs.update'); // admin.designs.update
    Route::delete('/designs/{design}', [DesignController::class, 'destroy'])->name('designs.destroy'); // admin.designs.destroy

    // Categories (Admin only)
    Route::resource('categories', CategoryController::class)->except(['show']); // admin.categories.* (show might be public?)

    // Order Management (Admin only actions)
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update.status'); // admin.orders.update.status

    // User Management (Admin only)
    Route::get('/users', [UserController::class, 'index'])->name('users.index'); // admin.users.index
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show'); // admin.users.show
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update'); // admin.users.update
    Route::put('/users/{user}/block', [UserController::class, 'toggleBlock'])->name('users.toggle-block'); // admin.users.toggle-block

    // Reports (Admin only)
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index'); // admin.reports.index
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales'); // admin.reports.sales
    Route::get('/reports/orders', [ReportController::class, 'orders'])->name('reports.orders'); // admin.reports.orders
    Route::get('/reports/users', [ReportController::class, 'users'])->name('reports.users'); // admin.reports.users

    // Settings (Admin only)
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index'); // admin.settings.index
    Route::put('/settings', [SettingController::class, 'update'])->name('settings.update'); // admin.settings.update

});

// Note: The original Route::resource('designs', DesignController::class); was removed as routes are now defined explicitly.
// Note: Logic within controllers (e.g., OrderController, DesignController) will need to be updated
//       to handle authorization for viewing specific resources (e.g., buyers only seeing their own orders).
