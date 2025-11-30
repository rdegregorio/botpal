<?php

namespace App\Listeners;

use App\Services\Subscriptions\SubscriptionService;
use Illuminate\Auth\Events\Verified;

class VerifiedListener
{
    public function __construct()
    {
    }

    public function handle(Verified $event): void
    {
        $user = $event->user;
        $user->chatConfigLatest()->firstOrCreate();

        try {
            SubscriptionService::createFreeSubscription($user);
        } catch (\Exception $exception) {
            \Log::error($exception->getMessage());
        }
    }
}
