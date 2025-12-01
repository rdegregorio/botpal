<?php

namespace App\Providers;

use App\Models\ChatLog;
use App\Models\Subscription;
use App\Models\User;
use App\Observers\ChatLogObserver;
use App\Observers\SubscriptionObserver;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Blade;
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
        User::observe(UserObserver::class);
        Subscription::observe(SubscriptionObserver::class);
        ChatLog::observe(ChatLogObserver::class);

        // Temporarily allow all users full access (Stripe not configured)
        // When Stripe is ready, restore original logic
        Blade::directive('paid', function () {
            // Original logic would output 'data-premium' for free users
            return "";  // Don't disable any buttons for now
        });

        Blade::if('freeUser', function () {
            return false;  // Treat everyone as paid for now
        });

        Blade::if('paidUser', function () {
            return true;  // Treat everyone as paid for now
        });
    }
}
