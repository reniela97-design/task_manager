<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL; // Importante para sa HTTPS fix
use Carbon\Carbon;

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
        config(['app.timezone' => 'Asia/Manila']);
        date_default_timezone_set('Asia/Manila');
        Carbon::setLocale('en');

        // 2. PREVENT MIGRATION ERRORS
        Schema::defaultStringLength(191);

        // 3. FORCE HTTPS IN PRODUCTION
        // Kini ang mopa-wala sa "Not Secure" warning inig submit og forms sa Render
        if (app()->environment('production') || env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
    }
}