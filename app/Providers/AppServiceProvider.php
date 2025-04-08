<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http; // Import Http facade

class AppServiceProvider extends ServiceProvider // Ensure class definition is present and correct
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Reverted: Remove global HTTP client SSL bypass
    }
}
