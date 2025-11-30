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

        Blade::directive('paid', function () {
            return "<?php if(auth()->check() && auth()->user()->getCurrentActiveSubscription()?->isFree()) { echo 'data-premium'; } ?>";
        });

        Blade::if('freeUser', function () {
            return auth()->check() && auth()->user()->getCurrentActiveSubscription()?->isFree();
        });

        Blade::if('paidUser', function () {
            return auth()->check() && !auth()->user()->getCurrentActiveSubscription()?->isFree();
        });
    }
}
