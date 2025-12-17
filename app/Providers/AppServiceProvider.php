<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\BlockchainObserver;
use App\Models\Booking;
use App\Models\User;


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
        Booking::observe(BlockchainObserver::class);
        User::observe(BlockchainObserver::class);
    }
}
