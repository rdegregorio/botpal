<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('subscribe-trial', static function (User $user) {
            if(!$user->trial_ends_at) {
                return true;
            }

            return $user->getAvailableTrialDaysToSubscribe() > 0;
        });

        Gate::define('delete-account', static function (User $user) {
            $s = $user->getCurrentActiveSubscription();

            return !$s || $s->ends_at;
        });
    }
}
