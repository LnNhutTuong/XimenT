<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\User;
use App\Models\Orders;

use App\Observers\UserObserver;
use App\Observers\OrderObserver;

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
        User::observe(UserObserver::class);
        Orders::observe(OrderObserver::class);
    }


}
