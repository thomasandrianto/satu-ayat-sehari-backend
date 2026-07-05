<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

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

        /*
        |--------------------------------------------------------------------------
        | Public API Rate Limit
        |--------------------------------------------------------------------------
        | Endpoint devotion hanya read-only.
        | Batasi abuse dari satu IP.        
        */
        RateLimiter::for('api-public', function (Request $request) {
            return Limit::perMinute(60)
                ->by($request->ip());
        });

        /*
        |--------------------------------------------------------------------------
        | Device Register Limit
        |--------------------------------------------------------------------------        
        | Register hanya terjadi saat install/login device.
        */
        RateLimiter::for('device-register', function (Request $request) {
            return Limit::perMinute(10)
                ->by($request->ip());
        });

        /*
        |--------------------------------------------------------------------------
        | Device Ping Limit
        |--------------------------------------------------------------------------
        | Heartbeat device.    
        */
        RateLimiter::for('device-ping', function (Request $request) {
            return Limit::perMinute(5)
                ->by($request->ip());
        });

    }
}