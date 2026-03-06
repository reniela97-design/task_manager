<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; // Gidugang para safe
use Carbon\Carbon; // Gidugang para sa Time

class AppServiceProvider extends ServiceProvider
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
        // 1. SET GLOBAL TIMEZONE (Asia/Manila)
        // Kini ang mopugos sa tibuok system nga mosunod sa oras sa Pilipinas
        config(['app.timezone' => 'Asia/Manila']);
        date_default_timezone_set('Asia/Manila');
        Carbon::setLocale('en');

        // 2. PREVENT MIGRATION ERRORS (Optional pero recommended)
        Schema::defaultStringLength(191);
    }
}